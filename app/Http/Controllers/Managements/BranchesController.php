<?php

namespace App\Http\Controllers\Managements;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchRequest;
use App\Http\Resources\BranchResource;
use App\Models\Branch;

class BranchesController extends Controller
{

    public function index()
    {
        return BranchResource::collection(
            Branch::query()->latest()->get()
        );
    }

    public function store(BranchRequest $request)
    {
        return BranchResource::make(
            Branch::query()->create($request->validated())
        );
    }

    public function update(BranchRequest $request, int $id)
    {
        $branch = Branch::query()->findOrFail($id);
        $branch->fill($request->validated());
        $branch->save();
        return BranchResource::make($branch);
    }

    public function destroy(int $id)
    {
        $branch = Branch::query()->findOrFail($id);
        $branch->delete();
        return BranchResource::make($branch);
    }
}