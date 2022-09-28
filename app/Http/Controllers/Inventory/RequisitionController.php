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
        return RequisitionResource::make($services->create());
    }

    public function show(RequisitionServices $services,  int $id)
    {
        return RequisitionResource::make($services->show($id));
    }

}