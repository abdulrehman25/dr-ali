<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResetCodePassword;
use Illuminate\Support\Facades\Validator;

class CodeCheckController extends Controller
{
    public function __invoke(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'code' => 'required|string|exists:reset_code_passwords',
        ]);

        if($validator->fails()) {
            $response = $validator->messages();
            return response(['message' => 'The selected code is invalid.', 'status' => 404 ]);
        }

        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

        if ($passwordReset->isExpire()) {

            return response(['message' => trans('passwords.code_is_expire'), 'status' => 404]);
        }

        return response([
            'code' => $passwordReset->code,
            'message' => trans('passwords.code_is_valid'),
            'status' => 200
        ], 200);
    }
}
