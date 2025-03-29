<?php

use App\Http\Controllers\AddHealthCommentController;
use App\Http\Controllers\HealthRecordHistoryController;
use App\Http\Controllers\MedicineReportController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\UserController;
use App\Models\HealthRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// user routes
Route::post('/signup',[UserController::class,'signup']);
Route::post('/login',[UserController::class,'login'])->name('login');
Route::post('/logout', [UserController::class,'logout'])->name('logout')->middleware('auth:sanctum');

// profile routes
// update profile user details
Route::post('/update-profile',[UserController::class,'updateProfile'])->middleware('auth:sanctum');

// change password
Route::post('/change-password',[UserController::class,'changePassword'])->middleware('auth:sanctum');

//see all health record with paginate
Route::get('/health-record',[RecordController::class,'index'])->middleware('auth:sanctum');

// add record
Route::post('/health-record',[RecordController::class,'store'])->middleware('auth:sanctum');

// delete record
Route::delete('/health-record/{id}',[RecordController::class,'delete'])->middleware('auth:sanctum');

// get record history by name
Route::get('/record-history/{name}',[RecordController::class,'getRecordHistory'])->middleware('auth:sanctum');

// search record
Route::get('/search-record/{name}',[RecordController::class,'searchRecord'])->middleware('auth:sanctum');


// medicine report routes
Route::post('/medicine-report',[MedicineReportController::class,'store'])->middleware('auth:sanctum');


// get single record by date in accending order
Route::post('/record-by-type',[RecordController::class,'recordByType']);
// ->middleware('auth:sanctum');
// generate code 
Route::post('/generate-code',[RecordController::class,'generateCode']);
// ->middleware('auth:sanctum');
Route::post('/add-relation',[RecordController::class,'addFamilyMember']);
// ->middleware('auth:sanctum');

// 



// health_record_comments routes
Route::post('/add-comment',[AddHealthCommentController::class,'addComment']);

// health_record_history routes
Route::post('/record-history',[HealthRecordHistoryController::class,'recordHistory']);
