<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactUs;
use Illuminate\Support\Facades\Validator;
use App\Mail\ContactUsMail;
use Illuminate\Support\Facades\Mail;
class ContactUsController extends Controller
{
    public function createRequest(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'message' => 'required',
        ]);

        if($validator->fails()) {
            $response = $validator->messages();
            return $response;
        }

        $newReq = new ContactUs;
        $newReq->name = $request->name;
        $newReq->email = $request->email;
        $newReq->phone = $request->phone;
        $newReq->message = $request->message;
        $newReq->save();

        $mail = 'praveshyadav@virtualemployee.com';
        Mail::to($mail)->send(new ContactUsMail($newReq));
        return response(['message' =>'Request added successfully.', 'status' => 200]);
    }
}
