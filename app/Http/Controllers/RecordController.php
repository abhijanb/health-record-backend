<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\FamilyMember;
use App\Models\HealthRecord;
use App\Models\HealthRecordHistory;
use Carbon\Carbon;
use \Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class RecordController extends Controller
{
    //
    public  function index(){
        //display all the recordst
        $records = HealthRecord::where('user_id', Auth::user()->id)->paginate(1);
        // dd($record);
        try{

            if($records->isEmpty()){
                return response()->json([
                    "message"=>"no data available",
                ],201);
            }
            if(!$records){
                return response()->json([
                    "message" => "error feching data",
                    "error" => $records->error(),
                ],500);
            }
            return response()->json([
                // "message"=> "record get successfully",
                // "data"=> $records,
                'records' => $records->items(), // Records for the current page
        'current_page' => $records->currentPage(), // Current page number
        'next_page_url' => $records->nextPageUrl(), // URL for the next page (if available)
        'total' => $records->total(), // Total number of records
                
            ],201);
        }
        catch(Exception $e){
            return response()->json([
                "message"=> "error",
                "error"=> $e->getMessage(),
            ],500);
        }
    }
    
    public function store(Request $request){
       
        $data = Validator::make($request->all(),[
            'name'=>'required|string|max:255',
            'record_type'=>'required|string|in:file,text',
            'record_details'=>'required|string|max:2000',
            'record_file'=>$request->hasFile('file_path') ? 'image|mimes:jpeg,png,jpg' : 'nullable',
            
            'visibility'=>'required|in:public_all,public_connected,private,',   
            'value'=>'required|numeric'
            
        ]);


// check if the record name is already present in the table then move that to history table and delete it from main table and add new record to the main table
$record = HealthRecord::where('user_id', Auth::user()->id)->where('name', $request->input('name'))->first();
if($record){
    HealthRecordHistory::create([
        'user_id' => Auth::user()->id,
        'name' => $record->name,
        'record_type' => $record->record_type,
        'record_details' => $record->record_details,
        'record_file' => $record->record_file,
        'visibility' => $record->visibility,
        'value' => $record->value,
        'changed_at' => Carbon::now(),
    ]);
    // update the record in the history table
    $record->update([
        'name' => $request->input('name'),
        'record_type' => $request->input('record_type'),
        'record_details' => $request->input('record_details'),
        'record_file' => $request->input('record_file'),
        'visibility' => $request->input('visibility'),
        'value' => $request->input('value'),
    ]);
    return response()->json([
        'message' => 'record updated successfully',
    ], 200);
}
if($data->fails()){
    return response()->json([
        'message' => 'validation error',
        'error' => $data->errors(),
    ],422);
}


        $imageName = null;
        if($request->hasFile('record_file')){
            $imageName = time().'.'.$request->file('record_file')->getClientOriginalExtension();
            
            $request->file('record_file')->move(public_path("public"),$imageName);
         
        }
        // dd(Auth::user());
         HealthRecord::create([
            'user_id' => Auth::user()->id,
            'name' => $request->input('name'),
            'record_type'=>$request->input('record_type'),
            'record_details'=>$request->input('record_details'),
            'record_file'=>$request->input('record_file'),
            'date_recorded'=>Carbon::now(),
            'visibility'=>$request->input('visibility'),
            'value'=>$request->input('value')
        ]);
      return response()->json([
        'message'=> 'health record stored successfully',
      ],200);
        
    }

    public function delete(Request $request, $id){
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
                'error' => $e->getMessage()
            ],500);
        }
        }


        public function getRecordHistory(Request $request, $name){
            $user = Auth::user();
            $record = HealthRecordHistory::where('user_id', $user->id)->where('name', $name)->orderBy('changed_at', 'desc')->get();
            // dd($record);
            if($record->isEmpty()){
                return response()->json([
                    'message' => 'no record found',
                    'error' => 'data not found'
                ],404);
            }
            return response()->json([
                'message' => 'record found successfully',
                'data' => $record
            ],200);
        }

        public function searchRecord($name){
            $user = Auth::user();
            $record = HealthRecord::where('user_id',$user->id)->where('name',$name)->get();
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
            $date = $record->pluck('recorded_at')->toArray();
            $value = $record->pluck('value')->toArray();
        if(empty($date || $value)){
            return response()->json([
                'message'=>'no record found',
                'error' => 'data not found'
            ],404);
        }
        return response()->json([
            'message' => 'record found successfully',
            'data' => $date,
            'value' => $value

        ]);
        }


public function generateCode(Request $request){
    $user = Auth::user();
    $randomCode = rand(100000,999999);
    Code::create([
        'user_id' => $user->id,
        'code' => $randomCode,
        'expires_at'=> Carbon::now()->addMinute(5)
    ]);
    return response()->json([
               'message' => 'code generated successfully',
               'code'=> $randomCode

    ]);
}
        // add family member with their respected relation to the user
        public function addFamilyMember(Request $request){
            $user = Auth::user();
            $relation = $request->input('relation');
            $code = $request->input('code');
            // if the user code is equal to the code of the user_id in the code table
            // if the code is eequal to the code in code table then add the user to the family members
            if(Code::where('code', $code)->get()){
                // get relation_id from the code table
                $relation_id = Code::where('code',$code)->firstOrFail()->user_id;
                
                // delete the code from code table 
                Code::where('code', $code)->delete();
                // add the user to the family members
                FamilyMember::create([
                    'user_id' => $user->id,
                    'relation' => $relation,
                    'relation_id' => $relation_id
                    ]);
                    return response()->json([
                        'message' => 'family member added successfully',
                        'relation' => $relation
                        ]);
            }
        
        }
    
    }

