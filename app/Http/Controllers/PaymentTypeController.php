<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentTypeRequest;
use App\PaymentType;
use Exception;
use Illuminate\Http\Request;

/**
 * Class PaymentTypeController
 * @package App\Http\Controllers
 * @resource Payment Type
 *
 * Payment type related endpoints
 */
class PaymentTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.role:super,admin')->except(['active']);
        $this->setResourceName('payment type');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resources = PaymentType::all();

        return $this->success(null, $resources);
    }


    /**
     * Display a listing of active resources
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function active()
    {
        $resources = PaymentType::active()->get();

        return $this->success(null, $resources);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param PaymentTypeRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentTypeRequest $request)
    {
        $data = $request->validated();

        $resource = PaymentType::create([
            'name' => $data['name'],
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
        $resource = PaymentType::find($id);

        if(!$resource){
            return $this->notFound();
        }

        return $this->success("$this->resourceName fetched successfully", $resource);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param PaymentTypeRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentTypeRequest $request, $id)
    {
        $data = $request->validated();

        $resource = PaymentType::find($id);

        if(!$resource){
            return $this->notFound();
        }

        $resource->name = $data['name'];

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
        $resource = PaymentType::find($id);

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

        $resource = PaymentType::find($id);

        if(!$resource){
            return $this->notFound();
        }

        $resource->is_active = $status;
        $resource->save();

        $action = $status ? 'activated' : 'deactivated';

        return $this->success("$this->resourceName successfully {$action}");
    }
}
