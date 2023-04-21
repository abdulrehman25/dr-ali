<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResetCodePassword;
use App\Mail\SendCodeResetPassword;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function testMail()
    {
        $mail = 'test@gmail.com';
        Mail::to($mail)->send(new TestMail);

        dd('Mail Send Successfully !!');
    }

    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users',
        ]);

        if($validator->fails()) {
            $response = $validator->messages();
            return $response;
        }

        ResetCodePassword::where('email', $request->email)->delete();

        $data['email'] = $request->email;

        $data['code'] = mt_rand(100000, 999999);
        
        $codeData = ResetCodePassword::create($data);
        
        Mail::to($request->email)->send(new SendCodeResetPassword($codeData->code));

        return response(['message' => trans('passwords.sent')], 200);
    }

}
