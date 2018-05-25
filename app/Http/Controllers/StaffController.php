<?php

namespace App\Http\Controllers;

use App\Branch;
use App\BranchUser;
use App\Helper;
use App\Http\Requests\StaffRequest;
use App\Role;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class StaffController
 * @package App\Http\Controllers
 * @resource Staffs
 *
 * Endpoints required for managing staff
 */
class StaffController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth.role:super,admin');
        $this->setResourceName('staff');
    }

    /**
     * Display a listing of the resource.
     *
     * @param null $branch
     * @return \Illuminate\Http\Response
     */
    public function index($branch = null)
    {
        if ($branch) {
            $branch = Branch::find($branch);

            if (!$branch) {
                return $this->notFound();
            }

            $staffs = $branch->staffs()->paginate(30);
        } else {
            $staffs = User::isStaff()->paginate(30);
        }

        return $this->paginated($staffs);
    }

    /**
     * Display list of active staffs
     *
     * @param null $branch
     * @return \Illuminate\Http\JsonResponse
     */
    public function active($branch = null)
    {
        if ($branch) {
            $branch = Branch::find($branch);

            if (!$branch) {
                return $this->notFound();
            }

            $staffs = $branch->staffs()->active()->paginate(30);
        } else {
            $staffs = User::isStaff()->active()->paginate(30);
        }

        return $this->paginated($staffs);
    }

    public function types()
    {
        $types = array_map(function($type){
            return ucfirst($type);
        }, Helper::getStaffRoles());

        return $this->success($types);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param StaffRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StaffRequest $request)
    {
        $data = $request->validated();

        $staff = new User($data);

        $staff->password = bcrypt('password');

        $role = Role::whereName($data['role'])->first();

        $branch = new BranchUser([
            'branch_id' => $data['branch']
        ]);

        try {
            DB::transaction(function () use ($staff, $role, $branch) {
                $staff->save();
                $branch->user_id = $staff->id;
                $branch->save();
                $staff->roles()->save($role);
            });

            $staff->type = $role->name;
        }catch (Exception $bug){
            return $this->exception($bug);
        }

        return $this->success("$this->resourceName was created successfully", $staff);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $staff = User::isStaff()->find($id);

        if (!$staff) {
            return $this->notFound();
        }

        return $this->success("$this->resourceName has been fetched successfully", $staff);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StaffRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StaffRequest $request, $id)
    {
        $data = $request->validated();

        $staff = User::isStaff()->find($id);

        if (!$staff) {
            return $this->notFound();
        }

        $staff->name = $data['name'];
        $staff->gender = $data['gender'];
        $staff->staff_id = $data['staff_id'];
        $role = Role::whereName($data['role'])->first();

        try {
            DB::transaction(function () use ($staff, $role) {
                $staff->save();
                $staff->roles()->sync([$role->id]);
            });

            $staff->type = $role->name;
        }catch (Exception $bug){
            return $this->exception($bug);
        }

        return $this->success("$this->resourceName has been updated successfully", $staff);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $staff = User::isStaff()->find($id);

        if (!$staff) {
            return $this->notFound();
        }

        $staff->delete();

        return $this->success("$this->resourceName has been deleted successfully");
    }

    /**
     * Activate or deactivate specified resource
     * @param $id
     * @param $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus($id, $status)
    {
        $status = $status == 'true' ;

        $resource = User::isStaff()->find($id);

        if(!$resource){
            return $this->notFound();
        }

        $resource->is_active = $status;
        $resource->save();

        $action = $status ? 'activated' : 'deactivated';

        return $this->success("$this->resourceName successfully {$action}");
    }
}
