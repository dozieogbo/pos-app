<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Helper;
use App\Http\Requests\CustomerRequest;
use Exception;
use Illuminate\Http\Request;

/**
 * Manage customers data
 *
 * Class CustomerController
 * @package App\Http\Controllers
 * @resource Customer
 */
class CustomerController extends Controller
{
    public function __construct()
    {
        $this->setResourceName('customer');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resources = Customer::with('creator')->get();

        return $this->success(null, $resources);
    }


    /**
     * Display a listing of active resources
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function active()
    {
        $resources = Customer::with('creator')->active()->get();

        return $this->success(null, $resources);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CustomerRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {
        $data = $request->validated();

        $customer = new Customer($data);

        $customer->created_by = Helper::getUserId();

        try {
            $customer->save();
        } catch (Exception $bug) {
            return $this->exception($bug);
        }

        return $this->success($customer);

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::with('creator')->find($id);

        if (!$customer) {
            return $this->notFound();
        }

        return $this->success($customer);
    }

    public function showByPhone($phone)
    {
        $customer = Customer::wherePhone($phone)
            ->first();

        if (!$customer) {
            return $this->notFound();
        }

        return $this->success($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CustomerRequest $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request, $id)
    {
        $data = $request->validated();

        $customer = Customer::find($id);

        if (!$customer) {
            return $this->notFound();
        }

        $customer->name = $data['name'];
        $customer->phone = $data['phone'];

        try {
            $customer->save();
        } catch (Exception $bug) {
            return $this->exception($bug);
        }

        return $this->success($customer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $resource = Customer::find($id);

        if (!$resource) {
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
        $status = $status == 'true';

        $resource = Customer::find($id);

        if (!$resource) {
            return $this->notFound();
        }

        $resource->is_active = $status;
        $resource->save();

        $action = $status ? 'activated' : 'deactivated';

        return $this->success("$this->resourceName successfully {$action}");
    }
}
