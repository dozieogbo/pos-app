<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Requests\UnitRequest;
use App\Unit;
use Exception;
use Illuminate\Http\Request;

/**
 * Class UnitController
 * @package App\Http\Controllers
 * @resource Units
 *
 * Endpoints for management of units of measurement.
 */
class UnitController extends Controller
{

    public function __construct()
    {
        $this->setResourceName('unit');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = Unit::all();

        return $this->success(null, $units);
    }

    /**
     * Display a listing of active resources
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function active()
    {
        $units = Unit::active()->get();

        return $this->success(null, $units);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UnitRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UnitRequest $request)
    {
        $data = $request->validated();

        $unit = Unit::create([
            'name' => $data['name'],
            'quantity' => $data['quantity'],
            'created_by' => Helper::getUserId()
        ]);

        return $this->success("$this->resourceName created successfully.", $unit);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $unit = Unit::find($id);

        if(!$unit){
            return $this->notFound();
        }

        return $this->success($this->resourceName.' fetched successfully', $unit);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UnitRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UnitRequest $request, $id)
    {
        $data = $request->validated();

        $unit = Unit::find($id);

        if(!$unit){
            return $this->notFound();
        }

        $unit->quantity = $data['quantity'];
        $unit->name = $data['name'];

        try {
            $unit->save();
        }catch(Exception $bug){
            return $this->exception($bug);
        }

        return $this->success("$this->resourceName successfully updated", $unit);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $unit = Unit::find($id);

        if(!$unit){
            return $this->notFound();
        }

        $unit->delete();

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

        $unit = Unit::find($id);

        if(!$unit){
            return $this->notFound();
        }

        $unit->is_active = $status;
        $unit->save();

        $action = $status ? 'activated' : 'deactivated';

        return $this->success("$this->resourceName successfully {$action}");
    }
}
