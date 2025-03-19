<?php

use App\Http\Controllers\RecordController;
use App\Http\Controllers\UserController;
use App\Models\HealthRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/signup',[UserController::class,'signup']);
Route::post('/login',[UserController::class,'login'])->name('login');
Route::post('/logout', [UserController::class,'logout'])->middleware('auth:sanctum');
Route::post('/health-record',[RecordController::class,'store'])->middleware('auth:sanctum');
// display record
Route::post('/display-record',[RecordController::class,'displayRecord'])->middleware('auth:sanctum');
// get single record by date in accending order
Route::post('/record-by-type',[RecordController::class,'recordByType'])->middleware('auth:sanctum');
// generate code 
Route::post('/generate-code',[RecordController::class,'generateCode'])->middleware('auth:sanctum');
Route::post('/add-relation',[RecordController::class,'addFamilyMember'])->middleware('auth:sanctum');
// 
