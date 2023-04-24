<?php 

use App\Http\Controllers\Inventory\RequisitionController;
use Illuminate\Support\Facades\Route;


Route::get('requisition/{id}',[RequisitionController::class,'show']);