<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Http\Resources\SettingResource;
use App\Setting;
use Exception;
use Illuminate\Http\Request;

/**
 * Class SettingsController
 * @package App\Http\Controllers
 * @resource Settings
 *
 * Endpoints used to set system wide settings
 */
class SettingsController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth.role:super,admin')->except(['index', 'show']);
        $this->setResourceName('setting');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resources = SettingResource::collection(Setting::all());

        return $this->success(null, $resources);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param SettingRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(SettingRequest $request)
    {
        $data = $request->validated();

        $resource = Setting::create([
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
        $resource = Setting::find($id);

        if(!$resource){
            return $this->notFound();
        }

        return $this->success("$this->resourceName fetched successfully", $resource);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param SettingRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(SettingRequest $request, $id)
    {
        $data = $request->validated();

        $resource = Setting::find($id);

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
        $resource = Setting::find($id);

        if(!$resource){
            return $this->notFound();
        }

        $resource->delete();

        return $this->success("$this->resourceName successfully deleted");
    }
}
