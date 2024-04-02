<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Services\ProjectPlantService;
use App\Http\Resources\RequisitionResource;
use App\Models\RequisitionItem;
use Illuminate\Support\Facades\DB;
use App\Services\InventoryServices;

class ProjectPlantOrdersController extends Controller
{
    public function index(ProjectPlantService $services)
    {
        return RequisitionResource::collection($services->get());
    }
    public function show(ProjectPlantService $services, int $id)
    {
        return RequisitionResource::make($services->show($id));
    }
    public function consumeItems(InventoryServices $inventory_services)
    {
        try {
            DB::beginTransaction();
            $user = request()->user();
            $data = [
                'transacted_by_id' => $user->id,
                'accepted_by_id' => $user->id,
                'from_branch_id' => $user->branch_id,
                'to_branch_id' => $user->branch_id,
                'description' => 'used/consumed items'
            ];

            $product_ids = request()->get('product_id');
            $qtys = request()->get('qty');
            $requisition_item_ids = request()->get('requisition_items_id');
            $stock_outs = [];
            $rq_items = [];

            foreach ($product_ids as $key => $id) {
                $amt = $qtys[$key];
                if ($amt > 0) {
                    $stock_outs[] = $inventory_services->stockOut($id, (int)$amt, $data);
                    $request_item = RequisitionItem::query()->where('id', $requisition_item_ids[$key])->first();
                    $request_item->used_qty = (int)$request_item->used_qty + (int)$amt;
                    $rq_items[] = $request_item->save();
                }
            }

            DB::commit();
            return response(['$product_ids' => $product_ids, 'stock_outs' => $stock_outs, 'items' => $rq_items], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['error' => $e->getMessage(), 'data' => request()->all(), 'type' => 'error', 'message' => 'Error processing your action.'], 200);
        }
    }

    public function returnItems(InventoryServices $inventory_services)
    {
        try {
            DB::beginTransaction();
            $user = request()->user();
            $dataOut = [
                'transacted_by_id' => $user->id,
                'accepted_by_id' => $user->id,
                'from_branch_id' => $user->branch_id,
                'to_branch_id' => $user->branch_id,
                'description' => 'return materials to main warehouse'
            ];
            $dataIn = [
                'transacted_by_id' => $user->id,
                'accepted_by_id' => $user->id,
                'from_branch_id' => $user->branch_id,
                'returned_by_user_id' => $user->id,
                'returned_by_branch_id' => $user->branch_id,
                'to_branch_id' => 1,
                'description' => 'materials returned by warehouse'
            ];

            $product_ids = request()->get('product_id');
            $qtys = request()->get('qty');
            $requisition_item_ids = request()->get('requisition_items_id');
            $stock_outs = [];
            $stock_ins = [];
            $rq_items = [];

            foreach ($product_ids as $key => $id) {
                $amt =  $qtys[$key];
                if ((int)$amt > 0) {
                    $stock_out[] = $inventory_services->stockOut($id, (int)$amt, $dataOut);
                    $stock_ins[] = $inventory_services->stockIn($id, (int)$amt, $dataIn, 1);
                    $request_item = RequisitionItem::query()->where('id', $requisition_item_ids[$key])->first();
                    $request_item->returned_qty = (int)$request_item->returned_qty + (int)$amt;
                    $rq_items[] = $request_item->save();
                }
            }

            DB::commit();
            return response(['stock_outs' => $stock_outs, 'stock_ins' => $stock_ins, 'items' => $rq_items], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['error' => $e->getMessage(), 'data' => request()->all(), 'type' => 'error', 'message' => 'Error processing your action.'], 200);
        }
    }
}
