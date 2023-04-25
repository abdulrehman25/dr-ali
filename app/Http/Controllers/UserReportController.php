<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserReport;

class UserReportController extends Controller
{
    public function storeUserReport(Request $request, UserReport $userReport){
        return $userReport->saveUserReport($request);
    }
}
