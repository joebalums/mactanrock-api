<?php

namespace App\Services;

use App\Enums\RequisitionStatus;
use App\Models\InventoryLocation;
use App\Models\Product;
use App\Models\Requisition;
use App\Models\RequisitionDetail;
use App\Models\RequisitionItem;
use Carbon\Carbon;

class ProjectPlantService
{
    public function get()
    {
        $user = request()->user();
        return Requisition::query()
            ->with(['requester','acceptor', 'location'])
            ->when(
                $user->branch_id != 1,
                fn ($q) => $q->where('branch_id', $user->branch_id)
            )
            ->where('purpose', 'project_plant')
            ->whereIn('status', ['approved', 'completed'])
            ->latest()
            ->paginate(is_integer(request()->get('paginate')) ?? 0);
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
}
