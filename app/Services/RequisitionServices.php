<?php

namespace App\Services;

use App\Enums\RequisitionStatus;
use App\Models\InventoryLocation;
use App\Models\Product;
use App\Models\Requisition;
use App\Models\RequisitionDetail;
use App\Models\RequisitionItem;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Services\InventoryServices;
class RequisitionServices
{

    protected $inventoryServices;
    
    public function __construct(InventoryServices $inventoryServices)
    {
        $this->inventoryServices = $inventoryServices;
    }

    public function get()
    {
        return Requisition::query()
            ->with(['requester','acceptor'])
            ->where('branch_id', request()->user()->branch_id)
            ->when( request('keyword'),
                function(Builder $q){
                    $keyword = request('keyword');
                    return $q->whereRaw("CONCAT_WS(' ',project_code,account_code,purpose,status,project_name) like '%{$keyword}%' ");
                })
            ->when( request('type'), fn($q,$type) => $q->where('status', $type))
            ->latest()
            ->paginate(request('paginate') ?? 12);
    }

    public function requestList()
    {
        return RequisitionDetail::query()
            ->whereHas('requisition' , fn($q) => $q->where('status', RequisitionStatus::Approved))
            ->with(['requisition' => [
                'location',
                'requester',
                'acceptor'
            ]])
            ->where('location_id', request()->user()->branch_id)
            ->latest()
            ->paginate(request('paginate') ?? 12);
    }

    public function showRequest(int $id)
    {
        return RequisitionDetail::query()
            ->with(['requisition' => [
                'location',
                'requester',
            ],
                'items' => [
                    'product'
                ]
            ])
            ->where('location_id', request()->user()->branch_id)
            ->findOrFail($id);
    }

    public function show(int $id)
    {
        return Requisition::query()->with([
            'details' => [
                'location',
                'items' => [
                    'product'
                ]
            ],
            'requester'
        ])->where('branch_id', request()->user()->branch_id)->findOrFail($id);
    }
    public function create()
    {
        $user = request()->user();
        $requisition = new Requisition();
        $requisition->project_code = request()->get('project_code');
        $requisition->account_code = request()->get('account_code');
        $requisition->purpose = request()->get('purpose');
        $requisition->branch_id = $user->branch_id;
        $requisition->needed_at = request()->get('date_needed');
        $requisition->user_id = $user->id;
        $requisition->save();


        $products = InventoryLocation::query()->whereIn('id', request('inventory_id'))->get();

        $groupByLocations = $products->groupBy('branch_id')->all();

        $qtyResolver = [];


        foreach (request('inventory_id') as $key => $product){
            $qtyResolver[$product] = request()->get('quantity')[$key] ?? 0;
        }

        foreach ($groupByLocations as $key => $data){
            $info = new RequisitionDetail();
            $info->location_id = $key;
            $info->requisition_id = $requisition->id;
            $info->save();
            $items = [];
            foreach ($data as $item) {
                $qty = $qtyResolver[$item->id] ?? 0;
                if($qty > 0){
                    $items[] = [
                        'requisition_detail_id' => $info->id,
                        'request_quantity' => $qty,
                        'full_filled_quantity' => 0,
                        'product_id' => $item->product_id,
                        'status' => 'incomplete'
                    ];

                    RequisitionItem::query()->insert($items);
                }

            }


        }




        return $this->show($requisition->id);
    }

    public function updateStatus(int $id, string $status, string $remarks = "")
    {
        $requisition = Requisition::query()->findOrFail($id);

        $requisition->status = $status;
        $requisition->remarks = $remarks;

        $requisition->save();

        return $requisition;
    }


    public function approvedRequisition(int $id): void
    { 
        $complete_if_in_array = array('production', 'sale', 'internal_use');

        $requisition = Requisition::query()->where('status', RequisitionStatus::Pending)->findOrFail($id);
        $requisition->status = in_array($requisition->purpose, $complete_if_in_array) ? RequisitionStatus::Completed:RequisitionStatus::Approved;
        $requisition->accepted_by_id = request()->user()->id;
        $requisition->date_approved = Carbon::now()->format('Y-m-d H:i:s');
       
        if(in_array($requisition->purpose, $complete_if_in_array)){
            $requisition->load('details');
            foreach ($requisition->details as $detail){
            $rd = RequisitionDetail::query()->findOrFail($detail->id);
                $rd->load('items'); 
                foreach($rd->items as $item){
                    $this->inventoryServices->out($item->product_id,$item->request_quantity,[ 
                        'user_id' =>  request()->user()->id,
                        'branch_id' => request()->user()->branch_id
                    ]);
                }
            }
        }

        $requisition->save();
    }
}