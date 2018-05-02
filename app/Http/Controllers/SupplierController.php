<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Requests\SupplierRequest;
use App\Supplier;
use Exception;
use Illuminate\Http\Request;

/**
 * Class SupplierController
 * @package App\Http\Controllers
 * @resource Suppliers
 *
 * Endpoints for managements of suppliers
 */
class SupplierController extends Controller
{
    public function __construct()
    {
        $this->setResourceName('supplier');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::all();

        return $this->success(null, $suppliers);
    }

    /**
     * Display a listing of active resources
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function active()
    {
        $suppliers = Supplier::active()->get();

        return $this->success(null, $suppliers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SupplierRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierRequest $request)
    {
        $data = $request->validated();

        $supplier = Supplier::create([
            'name' => $data['name'],
            'code' => $data['code'],
            'created_by' => Helper::getUserId()
        ]);

        return $this->success("{$this->resourceName} created successfully.", $supplier);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $supplier = Supplier::find($id);

        if(!$supplier){
            return $this->notFound();
        }

        return $this->success("$this->resourceName fetched successfully", $supplier);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SupplierRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(SupplierRequest $request, $id)
    {
        $data = $request->validated();

        $supplier = Supplier::find($id);

        if(!$supplier){
            return $this->notFound();
        }

        $supplier->code = $data['code'];
        $supplier->name = $data['name'];

        try {

            $supplier->save();

        }catch(Exception $bug){

            return $this->exception($bug);

        }

        return $this->success("$this->resourceName successfully updated", $supplier);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $supplier = Supplier::find($id);

        if(!$supplier){
            return $this->notFound();
        }

        $supplier->delete();

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

        $supplier = Supplier::find($id);

        if(!$supplier){
            return $this->notFound();
        }

        $supplier->is_active = $status;
        $supplier->save();

        $action = $status ? 'activated' : 'deactivated';

        return $this->success("$this->resourceName successfully {$action}");
    }
}
