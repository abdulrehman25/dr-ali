<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookAppointment;

class BookAppointmentController extends Controller
{
    public function bookAppointment(Request $request, BookAppointment $appointment){
        return $appointment->saveAppointment($request);
    }
}
