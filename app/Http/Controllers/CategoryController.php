<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\CategoryRequest;
use Exception;
use Illuminate\Http\Request;

/**
 * Class CategoryController
 * @package App\Http\Controllers
 * @resource Categories
 *
 * Category related endpoints
 */
class CategoryController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth.role:super,admin')->except(['active']);
        $this->setResourceName('category');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resources = Category::all();

        return $this->success(null, $resources);
    }


    /**
     * Display a listing of active resources
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function active()
    {
        $resources = Category::active()->get();

        return $this->success(null, $resources);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $data = $request->validated();

        $resource = Category::create([
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
        $resource = Category::find($id);

        if(!$resource){
            return $this->notFound();
        }

        return $this->success("$this->resourceName fetched successfully", $resource);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param CategoryRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        $data = $request->validated();

        $resource = Category::find($id);

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
        $resource = Category::find($id);

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

        $resource = Category::find($id);

        if(!$resource){
            return $this->notFound();
        }

        $resource->is_active = $status;
        $resource->save();

        $action = $status ? 'activated' : 'deactivated';

        return $this->success("$this->resourceName successfully {$action}");
    }
}
