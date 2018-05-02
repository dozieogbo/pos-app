<?php

namespace App\Http\Controllers;

use App\Http\Requests\StateRequest;
use App\State;
use Exception;
use Illuminate\Http\Request;

/**
 * Class StateController
 * @package App\Http\Controllers
 * @resource States
 *
 * Endpoints required for management of  states/locations
 */
class StateController extends Controller
{
    public function __construct()
    {
        $this->setResourceName('state');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resources = State::all();

        return $this->success(null, $resources);
    }

    /**
     * Display a listing of active resources
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function active()
    {
        $resources = State::active()->get();

        return $this->success(null, $resources);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param StateRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StateRequest $request)
    {
        $data = $request->validated();

        $resource = State::create([
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
        $resource = State::find($id);

        if(!$resource){
            return $this->notFound();
        }

        return $this->success("$this->resourceName fetched successfully", $resource);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param StateRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StateRequest $request, $id)
    {
        $data = $request->validated();

        $resource = State::find($id);

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
        $resource = State::find($id);

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

        $resource = State::find($id);

        if(!$resource){
            return $this->notFound();
        }

        $resource->is_active = $status;
        $resource->save();

        $action = $status ? 'activated' : 'deactivated';

        return $this->success("$this->resourceName successfully {$action}");
    }
}
