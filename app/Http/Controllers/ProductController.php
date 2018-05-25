<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Requests\InventoryRequest;
use App\Http\Requests\ProductRequest;
use App\Inventory;
use App\Product;
use App\Supply;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductController
 * @package App\Http\Controllers
 * @resource Products
 *
 * Product related endpoints
 */
class ProductController extends Controller
{

    private $folderUrl = 'uploads/products/';

    public function __construct()
    {
        $this->setResourceName('product');
    }

    /**
     * Display a listing of the resource.
     *
     * @param null $category
     * @return \Illuminate\Http\Response
     */
    public function index($category = null)
    {
        if ($category) {
            $products = Product::with(['creator', 'category'])->inCategory($category)->paginate(30);
        } else {
            $products = Product::with(['creator', 'category'])->paginate(30);
        }

        return $this->paginated($products);
    }

    /**
     * Display a listing of active resources
     *
     * @param null $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function active($category = null)
    {
        if ($category) {
            $products = Product::with(['creator', 'category'])->inCategory($category)->active();
        } else {
            $products = Product::with(['creator', 'category'])->active();
        }

        $products = $products->paginate(30);

        return $this->paginated($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated();

        $img = $request->file('image');

        $product = new Product($data);

        $product->category_id = $data['category'];
        $product->avatar = Helper::saveImage($this->folderUrl, $img);
        $product->created_by = Helper::getUserId();

        try {
            $product->save();
        } catch (Exception $bug) {
            return $this->exception($bug);
        }

        return $this->success("$this->resourceName created successfully.", $product);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $resource = Product::find($id);

        if (!$resource) {
            return $this->notFound();
        }

        return $this->success("$this->resourceName fetched successfully", $resource);

    }

    public function showByBarcode($barcode)
    {
        $resource = Product::whereBarcode($barcode)->first();

        if (!$resource) {
            return $this->notFound();
        }

        return $this->success("$this->resourceName fetched successfully", $resource);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $data = $request->validated();

        $product = new Product($data);

        $product->name = $data['name'];
        $product->size = $data['size'];
        $product->price = $data['price'];
        $product->barcode = $data['barcode'];
        $product->description = $data['description'];

        if ($request->hasFile('image')) {
            $img = $request->file('image');
            $product->avatar = Helper::saveImage($this->folderUrl, $img);
        }

        try {
            $product->save();
        } catch (Exception $bug) {
            return $this->exception($bug);
        }

        return $this->success("$this->resourceName updated successfully.", $product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $resource = Product::find($id);

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

        $resource = Product::find($id);

        if (!$resource) {
            return $this->notFound();
        }

        $resource->is_active = $status;
        $resource->save();

        $action = $status ? 'activated' : 'deactivated';

        return $this->success("$this->resourceName successfully {$action}");
    }

    /**
     * Add a new product arrival
     * @param $id
     * @param InventoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addArrival($id, InventoryRequest $request)
    {
        $data = $request->validated();

        $product = Product::find($id);

        if (!$product) {
            return $this->notFound();
        }

        $inventory = Inventory::inBranch($data['branch'], $id)->first();

        if (!$inventory) {
            $inventory = new Inventory([
                'branch_id' => $data['branch'],
                'product_id' => $data['product'],
                'quantity' => $data['quantity']
            ]);
        } else {
            $inventory->quantity += $data['quantity'];
        }

        $arrival = new Supply([
            'branch_id' => $data['branch'],
            'product_id' => $id,
            'supplied_by' => isset($data['supplier']) ? $data['supplier'] : null,
            'created_by' => Helper::getUserId(),
            'quantity' => $data['quantity'],
            'is_arrival' => true
        ]);

        try {
            DB::transaction(function () use ($inventory, $arrival) {
                $inventory->save();
                $arrival->save();
            });

            return $this->success('Arrival added to product successful');
        } catch (Exception $bug) {
            return $this->exception($bug);
        }
    }

    /**
     * Get product arrivals details by product or not
     * @param null $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArrivals($id = null)
    {
        if ($id) {
            $arrivals = Supply::arrival()->ofProduct($id);
        } else {
            $arrivals = Supply::arrival();
        }

        $arrivals = $arrivals
            ->with(['supplier', 'branch', 'creator', 'product'])
            ->paginate(30);

        return $this->paginated($arrivals);
    }

    /**
     * Create a product return
     * @param $id
     * @param InventoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addReturn($id, InventoryRequest $request)
    {
        $data = $request->validated();

        $product = Product::find($id);

        if (!$product) {
            return $this->notFound();
        }

        $inventory = Inventory::inBranch($data['branch'], $id)->first();

        if (!$inventory || $inventory->quantity < $data['quantity']) {
            return $this->failure('You have less than required quantity in stock.');
        } else {
            $inventory->quantity -= $data['quantity'];
        }

        $return = new Supply([
            'branch_id' => $data['branch'],
            'product_id' => $id,
            'supplied_by' => isset($data['supplier']) ? $data['supplier'] : null,
            'created_by' => Helper::getUserId(),
            'quantity' => $data['quantity'],
            'is_arrival' => false
        ]);

        try {
            DB::transaction(function () use ($inventory, $return) {
                $inventory->save();
                $return->save();
            });

            return $this->success('Arrival added to product successful');
        } catch (Exception $bug) {
            return $this->exception($bug);
        }
    }

    /**
     * Get product return details by product or not
     * @param null $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReturns($id = null)
    {
        if ($id) {
            $arrivals = Supply::return()->ofProduct($id);
        } else {
            $arrivals = Supply::return();
        }

        $arrivals = $arrivals
            ->with(['supplier', 'branch', 'creator', 'product'])
            ->paginate(30);

        return $this->paginated($arrivals);
    }
}
