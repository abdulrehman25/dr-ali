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
      
}
