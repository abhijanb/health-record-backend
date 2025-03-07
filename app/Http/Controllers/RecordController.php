<?php

namespace App\Http\Controllers;

use App\Models\HealthRecord;
use Carbon\Carbon;
use \Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class RecordController extends Controller
{
    //
    public  function index(){
        //display all the recordstc
        $record = HealthRecord::all();
        if($record<=0){
            return response()->json([
                "message"=>"no data available",
            ],201);
        }
        if(!$record){
            return response()->json([
                "message" => "error feching data",
                "error" => $record->error(),
            ],500);
        }
        return response()->json([
            "message"=> "record get successfully",
            "data"=> $record,

            ],201);
    }
    
    public function store(Request $request){
        // return response()->json([
        //     "hello"=>"back",
        //     'file' => $request->file('file_path')

        // ]);
        $data = Validator::make($request->all(),[
            'record_type'=>'required',
            'file_path' => $request->hasFile('file_path') ? 'image|mimes:jpeg,png,jpg' : 'nullable',
            'value'=>'nullable',
            
        ]);
        $recorded_at = Carbon::now();
        if($data->fails()){
            return response()->json([
                'message' => 'validation error',
                'error' => $data->errors(),
                'file' => $request->input('record_type')
            ],422);
        }
        $imageName = null;
        if($request->hasFile('file_path')){
            $imageName = time().'.'.$request->file('file_path')->getClientOriginalExtension();
            
            $request->file('file_path')->move(public_path("public"),$imageName);
         
        }
         HealthRecord::create([
            'user_id' => Auth::user()->id,
            'record_type'=>$request->input('record_type',null),
            'file_path'=>$imageName,
            'value'=>$request->input('value',null),
            'recorded_at' => $recorded_at,
        ]);
      return response()->json([
        'message'=> 'health record stored successfully',
      ],200);
        
    }

    public function delete(Request $request){
        $id = $request->input("id");
        $user_id = Auth::id();
        try{
            $healthRecord = HealthRecord::where('user_id' ,$user_id)->where('id', $id)->firstOrFail();
            $healthRecord->delete();
            return response()->json([
                'message' => 'deleted successfully',
                
            ],200);
        }
        catch(Exception $e){

            return response()->json([
                'message' => 'something went wrong',
                'error' => $e.getMessage()
            ],500);
        }
        }

        public function displayRecord(Request $request){
            $user = Auth::user();
            $record = HealthRecord::where('user_id',$user->id)->get();
            if($record->isEmpty()){
                return response()->json([
                    'message' => 'no record',

                ],404);
            }
            return response()->json([
                'message' => 'record found successfully',
                'data' => $record
            ],200);
        }
      

        public function recordByType(Request $request){
            $user = Auth::user();
            $recordType = $request->input('recordType');
            
            $record = HealthRecord::where('record_type', $recordType)
            ->orderBy('recorded_at', 'asc') // Sort in ascending order
            ->get();
        if($record->isEmpty()){
            return response()->json([
                'message'=>'no record found',
                'error' => 'data not found'
            ],404);
        }
        return response()->json([
            'message' => 'record found successfully',
            'data' => $record
        ]);
        }
}

