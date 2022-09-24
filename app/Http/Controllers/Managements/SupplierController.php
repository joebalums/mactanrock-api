<?php

namespace App\Http\Controllers\Managements;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        return SupplierResource::collection(
            Supplier::query()->latest()->get()
        );
    }

    public function store(SupplierRequest $request)
    {
        return SupplierResource::make(
            Supplier::query()->create($request->validated())
        );
    }

    public function update(SupplierRequest $request, int $id)
    {
        $branch = Supplier::query()->findOrFail($id);
        $branch->fill($request->validated());
        $branch->save();
        return SupplierResource::make($branch);
    }

    public function destroy(int $id)
    {
        $branch = Supplier::query()->findOrFail($id);
        $branch->delete();
        return SupplierResource::make($branch);
    }
}