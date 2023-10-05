<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookAppointment;
use Illuminate\Support\Facades\Validator;

class BookAppointmentController extends Controller
{
    protected $rules = [
        'name' => 'required',
        'appointment_reason' => 'required',
        'appointment_date' => 'required|date|date_format:Y-m-d',
        'appointment_time' => 'required|date_format:H:i',
        'appointment_email' => 'required',
        'appointment_number' => 'required',
    ];
    public function bookAppointment(Request $request, BookAppointment $appointment)
    {
        try {
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                $response = $validator->messages();
                return response(["data" => $response, 'status' => 'error']);
            } else {
                $appointmentArr = [
                    "appointment_number" => $request->appointment_number,
                    "name" => $request->name,
                    "appointment_reason" => $request->appointment_reason,
                    "appointment_email" => $request->appointment_email,
                    "appointment_date" => $request->appointment_date,
                    "appointment_time" => $request->appointment_time,
                    "appointment_status" => $request->appointment_status??'false'
                ];
                $responseData = $appointment->saveAppointment($appointmentArr, '', 'add');
                if ($responseData['status']) {
                    return response(["data" => $responseData['data'], 'status' => 'success']);
                } else {
                    return response()->json(['status' => false, 'massage' => $responseData['data']], 400);
                }

            }
        } catch (Exception $e) {
            return response()->json(['status' => false, 'massage' => 'Oops! Something went wrong. ' . $e->getMessage()], 400);
        }
    }
    public function editBookAppointment(Request $request, BookAppointment $appointment)
    {
        try {

            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                $response = $validator->messages();
                return response(["data" => $response, 'status' => 'error']);
            } else {
               
                $appointmentArr = [
                    "appointment_number" => $request->appointment_number,
                    "name" => $request->name,
                    "appointment_reason" => $request->appointment_reason,
                    "appointment_email" => $request->appointment_email,
                    "appointment_date" => $request->appointment_date,
                    "appointment_time" => $request->appointment_time,
                    "appointment_status" => $request->appointment_status??'false'
                ];
                $responseData = $appointment->saveAppointment($appointmentArr, $request->id, 'edit');

                if ($responseData['status']) {
                    return response(["data" => $responseData['data'], 'status' => 'success']);
                } else {
                    return response()->json(['status' => false, 'massage' => $responseData['data']], 400);
                }

            }
        } catch (Exception $e) {
            return response()->json(['status' => false, 'massage' => 'Oops! Something went wrong. ' . $e->getMessage()], 400);
        }
    }
    public function getBookAppointmentById($id, BookAppointment $appointment)
    {
        try {
            $responseData = $appointment->getAppointmentById($id);

            if ($responseData['status']) {
                return response(["data" => $responseData['data'], 'status' => 'success']);
            } else {
                return response()->json(['status' => false, 'massage' => $responseData['data']], 400);
            }


        } catch (Exception $e) {
            return response()->json(['status' => false, 'massage' => 'Oops! Something went wrong. ' . $e->getMessage()], 400);
        }
    }
    public function deleteAppointmentById($id, BookAppointment $appointment)
    {
        try {
            $responseData = $appointment->deleteAppointmentById($id);

            if ($responseData['status']) {
                return response(["data" => $responseData['data'], 'status' => 'success']);
            } else {
                return response()->json(['status' => false, 'massage' => $responseData['data']], 400);
            }


        } catch (Exception $e) {
            return response()->json(['status' => false, 'massage' => 'Oops! Something went wrong. ' . $e->getMessage()], 400);
        }
    }
    public function bookedAppointmentList(BookAppointment $appointment,$status=null)
    {
        try {
            $status=($status==1)?'true':'false';
            
            $responseData = $appointment->bookedAppointmentList($status);

            if ($responseData['status']) {
                return response(["data" => $responseData['data'], 'status' => 'success']);
            } else {
                return response()->json(['status' => false, 'massage' => $responseData['data']], 400);
            }


        } catch (Exception $e) {
            return response()->json(['status' => false, 'massage' => 'Oops! Something went wrong. ' . $e->getMessage()], 400);
        }
    }
}