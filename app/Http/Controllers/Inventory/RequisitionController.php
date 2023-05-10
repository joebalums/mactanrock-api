<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequisitionRequest;
use App\Http\Resources\RequisitionResource;
use App\Services\RequisitionServices;

class RequisitionController extends Controller
{
    public function index(RequisitionServices $services)
    {
        return RequisitionResource::collection($services->get());
    }
    public function store(RequisitionServices $services, RequisitionRequest $request)
    {
        return $services->create();
        // return RequisitionResource::make($services->create());
    }

    public function show(RequisitionServices $services, int $id)
    {
        return RequisitionResource::make($services->show($id));
    }
    public function accept(RequisitionServices $requisitionServices, int $id)
    {
        $requisitionServices->updateStatus($id, 'accepted', '');
        $requisitionServices->updateIssuanceStatus($id, 'pending', '');

        return response()->noContent();
    }

    public function issuances(RequisitionServices $services)
    {
        return RequisitionResource::collection($services->getIssuances());
    }

    public function receivingIssuances(RequisitionServices $services)
    {
        return RequisitionResource::collection($services->getReceivingIssuances());
    }

    public function issuancesForApproval(RequisitionServices $services)
    {
        return RequisitionResource::collection($services->getIssuancesForApproval());
    }

    public function createIssuances(RequisitionServices $services, int $id)
    {
        return $services->saveIssuance($id);
    }

    public function receivedIssuances(RequisitionServices $services, int $id)
    {
        return $services->receivedIssuance($id);
    }

    public function approveIssuance(RequisitionServices $requisitionServices, int $id)
    {
        $requisitionServices->updateIssuanceStatus($id, 'approved', '');

        return response()->noContent();
    }
}
