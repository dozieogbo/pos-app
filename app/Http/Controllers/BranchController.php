<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Helper;
use App\Http\Requests\BranchRequest;
use Exception;
use Illuminate\Http\Request;

/**
 * Class BranchController
 * @package App\Http\Controllers
 * @resource Branches
 *
 * Branch related endpoints
 */
class BranchController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth.role:super,admin');
        $this->setResourceName('branch');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resources = Branch::with('state')->get();

        return $this->success(null, $resources);
    }

    /**
     * Display a listing of active resources
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function active()
    {
        $resources = Branch::with('state')->active()->get();

        return $this->success(null, $resources);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param BranchRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(BranchRequest $request)
    {
        $data = $request->validated();

        $resource = Branch::create([
            'code' => $data['code'],
            'address' => $data['address'],
            'state_id' => $data['state'],
            'created_by' => Helper::getUserId()
        ]);

        return $this->success("{$this->resourceName} created successfully.", $resource);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $resource = Branch::find($id);

        if(!$resource){
            return $this->notFound();
        }

        return $this->success("$this->resourceName fetched successfully", $resource);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param BranchRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(BranchRequest $request, $id)
    {
        $data = $request->validated();

        $resource = Branch::find($id);

        if(!$resource){
            return $this->notFound();
        }

        $resource->code = $data['code'];
        $resource->address = $data['address'];
        $resource->state_id = $data['state'];

        try {

            $resource->save();

        }catch(Exception $bug){

            return $this->exception($bug);

        }

        return $this->success("$this->resourceName successfully updated", $resource);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $resource = Branch::find($id);

        if(!$resource){
            return $this->notFound();
        }

        $resource->delete();

        return $this->success("$this->resourceName successfully deleted");
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

        $resource = Branch::find($id);

        if(!$resource){
            return $this->notFound();
        }

        $resource->is_active = $status;
        $resource->save();

        $action = $status ? 'activated' : 'deactivated';

        return $this->success("$this->resourceName successfully {$action}");
    }
}
