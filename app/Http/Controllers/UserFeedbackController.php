<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\UserFeedback;

class UserFeedbackController extends Controller
{
    public function storeUserFeedback(Request $request, UserFeedback $userfeedback){
        return $userfeedback->saveUserFeedBack($request);
    }

    public function getUserFeedback(UserFeedback $userfeedback){
        return $userfeedback->getUserFeedback();
    }
     
    public function approveUserFeedback(Request $request)
    {
        $feedback = UserFeedback::find($request->id);
        $feedback->status = '1';
        $feedback->save();

        return response(["data" => 'Feedback Approved Successfully.','status'=>'200']);
    }

    public function disApproveUserFeedback(Request $request)
    {
        $feedback = UserFeedback::find($request->id);
        $feedback->status = '0';
        $feedback->save();

        return response(["data" => 'Feedback Dis-Approved Successfully.','status'=>'200']);
    }

    

    public function deleteUserFeedback($id){
        
        $feedback = UserFeedback::find($id);

        if(is_null($feedback)) {
            return response()->json(['message' => 'No data found', 'status' => 404]);
        }
        $feedback->delete();
        return response()->json(['message' => 'Feedback deleted Successfully', 'status' => '200']);
    }
}
