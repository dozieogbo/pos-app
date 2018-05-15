<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Requests\PurchaseRequest;
use App\PaymentType;
use App\Purchase;
use App\PurchaseDetail;
use Exception;
use Illuminate\Support\Facades\DB;


/**
 * Class PurchaseController
 * @package App\Http\Controllers
 * @resource Purchases
 *
 * Purchases related endpoints for POS
 */
class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->setResourceName('purchase');
    }

    /**
     * Display a listing of the resource.
     *
     * @param null $branch
     * @return \Illuminate\Http\Response
     */
    public function index($branch = null)
    {
        if ($branch) {
            $purchases = Purchase::with(['branch', 'customer'])->inBranch($branch)->paginate(30);
        } else {
            $purchases = Purchase::with(['branch', 'customer'])->paginate(30);
        }

        return $this->paginated($purchases);
    }

    public function active($branch = null)
    {
        if ($branch) {
            $purchases = Purchase::with(['branch', 'customer'])->active()->inBranch($branch)->paginate(30);
        } else {
            $purchases = Purchase::with(['branch', 'customer'])->active()->paginate(30);
        }

        return $this->paginated($purchases);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PurchaseRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PurchaseRequest $request)
    {
        $data = $request->validated();

        $payType = PaymentType
            ::hasName($data['name']);

        if (!$payType) {
            return $this->failure("Payment type doesn't exist");
        }

        try {
            $purchase = $this->createPurchase($data, $payType);
        } catch (Exception $bug) {
            return $this->exception($bug);
        }

        return $this->success("$this->resourceName created successfully", $purchase);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $resource = Purchase::with('details')->find($id);

        if (!$resource) {
            return $this->notFound();
        }

        return $this->success("$this->resourceName fetched successfully", $resource);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $resource = Purchase::find($id);

        if (!$resource) {
            return $this->notFound();
        }

        $resource->details()->delete();
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

        $resource = Purchase::find($id);

        if (!$resource) {
            return $this->notFound();
        }

        $resource->is_active = $status;
        $resource->save();

        $action = $status ? 'activated' : 'deactivated';

        return $this->success("$this->resourceName successfully {$action}");
    }

    /**
     * @param $data
     * @return array
     */
    protected function formatDetails($data): array
    {
        $details = $data['details'];

        $details = array_map(function ($detail) {
            return new PurchaseDetail($detail);
        }, $details);
        return $details;
    }

    /**
     * @param $data
     * @param $payType
     * @return Purchase
     */
    protected function createPurchase($data, $payType): Purchase
    {
        $details = $this->formatDetails($data);

        $purchase = new Purchase([
            'created_by' => Helper::getUserId(),
            'customer_id' => $data['customer'],
            'branch_id' => $data['branch'],
            'is_active' => true,
            'amount_paid' => $data['amount_paid'],
            'payment_type_id' => $payType->id
        ]);

        DB::transaction(function () use ($purchase, $details) {
            $purchase->save();
            $purchase->details()->saveMany($details);
        });
        return $purchase;
    }
}
