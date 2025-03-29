<?php

namespace App\Http\Controllers;

use App\Models\MedicineReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MedicineReportController extends Controller
{
    //
    // store medicine report
    public function store(Request $request)
    {

        

        $validator = Validator::make($request->all(), [
            
            'medicine_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'frequency' => 'required|string:once,twice,thrice,daily',
            'end_date' => 'required|date|after:today',
            'price' => 'required|numeric',
            'store_name' => 'required|string|max:255',
            'prescription' => 'nullable|string|max:255',
        ]);
     
// check if already present in 

if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }
        // Store the medicine report in the database
        $medicineReport = MedicineReport::create([
            'user_id' => Auth::user()->id,
            'medicine_name' => $request->medicine_name,
            'dosage' => $request->dosage,
            'frequency' => $request->frequency,
            'start_date' => Carbon::now(),
            'end_date' => $request->end_date,
            'price' => $request->price,
            'store_name' => $request->store_name,
            'prescription' => $request->file('prescription') ? $request->file('prescription')->store('prescriptions') : null,
        ]);
        

        return response()->json(['message' => 'Medicine report created successfully', 'data' => $medicineReport], 201);
    }
}
