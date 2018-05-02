<?php

namespace App\Http\Controllers;

use App\Flag;
use App\Http\Requests\FlagRequest;
use App\Http\Resources\SettingResource;
use Exception;
use Illuminate\Http\Request;

/**
 * Class FlagController
 * @package App\Http\Controllers
 * @resource Flags
 *
 * Endpoints used to set and retrieve application wide true/false values
 */
class FlagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.role:super,admin')->except(['index', 'show']);
        $this->setResourceName('setting');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resources = SettingResource::collection(Flag::all());

        return $this->success(null, $resources);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FlagRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(FlagRequest $request)
    {
        $data = $request->validated();

        $resource = Flag::create([
            'id' => $data['key'],
            'value' => $data['value'],
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
        $resource = Flag::find($id);

        if(!$resource){
            return $this->notFound();
        }

        return $this->success("$this->resourceName fetched successfully", $resource);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param FlagRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(FlagRequest $request, $id)
    {
        $data = $request->validated();

        $resource = Flag::find($id);

        if(!$resource){
            return $this->notFound();
        }

        $resource->id = $data['key'];
        $resource->value = $data['value'];

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
        $resource = Flag::find($id);

        if(!$resource){
            return $this->notFound();
        }

        $resource->delete();

        return $this->success("$this->resourceName successfully deleted");
    }
}
