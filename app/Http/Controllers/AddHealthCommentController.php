<?php

namespace App\Http\Controllers;

use App\Models\HealthRecordComment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddHealthCommentController extends Controller
{
    //
    public function addComment(Request $request){
        //add comment to health record
        $data = Validator::make($request->all(),[
            'record_id'=>'required',
            'comment'=>'required',
        ]);
        
        if($data->fails()){
            return response()->json([
                'message' => 'validation error',
                'error' => $data->errors(),
            ],422);
        }
        $comment = HealthRecordComment::create([
            'user_id' => "1",
            'record_id' => $request->input('record_id'),
            'comment' => $request->input('comment'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        if(!$comment){
            return response()->json([
                'message' => 'error adding comment',
                'error' => $comment->error(),
            ],500);
        }
        return response()->json([
            'message' => 'comment added successfully',
        ],201);
    }
}
