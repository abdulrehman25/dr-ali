<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserReport;

class UserReportController extends Controller
{
    public function storeUserReport(Request $request, UserReport $userReport){
        return $userReport->saveUserReport($request);
    }

    public function getUserReport(UserReport $userReport,$user_id)
    {
        return $userReport->getUserReport($user_id);
        
    }
}
