<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use App\Models\UserVerifyCode;
use App\Mail\UserVerificationCode;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\UserRegistration;
use App\Mail\RadiologyFinalReport;

class AuthController extends Controller
{
    public function __construct() {
        //$this->middleware('auth:sanctum',['except' => ['login', 'register']]);
        //$this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function generateUserVerifyCode(Request $request){

        if (User::where('email', '=', $request->email)->exists()) {
            return response(["status" => "error",'data'=>'Email Already Exists']);
        }

        UserVerifyCode::where('email', $request->email)->delete();

        $data['email'] = $request->email;

        $data['code'] = mt_rand(100000, 999999);
        
        $codeData = UserVerifyCode::create($data);
    
        Mail::to($request->email)->send(new UserVerificationCode($codeData->code));

        return response(['message' => 'Verification code sent!','status' => 200]);
    }

    public function verifyUserCode(Request $request){

        $validator = Validator::make($request->all(),[
            'code' => 'required|string|exists:user_verify_code',
            'email' => 'required|email'
        ]);

        if($validator->fails()) {
            $response = $validator->messages();
            return response(['message' => $response, 'status' => 404 ]);
        }

        $userData = UserVerifyCode::firstWhere('code', $request->code);
        
        if($userData->email == $request->email){
            return response([
                'code' => $userData->code,
                'message' => 'Code Verified!',
                'status' => 200
            ]);
        }else{
            return response(['data' => 'Input Code email does not match', 'status' => 200]);
        }
        
    }

    public function register(Request $request, User $user)
    {
        if (User::where('email', '=', $request->email)->exists()) {
            return response(["status" => "error",'data'=>'Email Already Exists']);
        } else {
            $user->saveUser($request);
            $this->sendWelcomeMail($user);
            return response(['data'=>$user, 'status' => 200]);
        }
    }

    public function sendWelcomeMail($user){

        Mail::to($user->email)->send(new UserRegistration($user));
    }

    public function updateUserData(Request $request, User $user){

        $userData = $user->updateUserData($request);
       
       // Mail::to($userData->email)->send(new RadiologyFinalReport($userData));
        return response(['data'=>$userData, 'status'=>200]);
    }
    public function login(Request $request)
    {
        if(!Auth::attempt($request->only('email','password'))){
            return response([
                "message" => "Invalid Credentials !",'status'=>'error'
            ]);
        }

        $user =Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        $cookies = cookie('jwt', $token, 60*24);//set for 1 day
        return response(["message" => "success",'data'=>$user])->withCookie($cookies);

    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return response(['message'=>'success'])->withCookie($cookie);
    }

    public function user(){
        return Auth::user();
    }

    public function getUser(User $user){

        return response()->json($user->getAllUser(),200);
    }
    public function getOpenCloseUser(User $user,$status=null){
        $status=($status==1)?'true':'false';
        return response()->json($user->getOpenCloseAllUser($status),200);
    }

}
