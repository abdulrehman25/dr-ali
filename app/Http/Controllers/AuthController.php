<?php

namespace App\Http\Controllers;
use DB;
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
    public function openCloseAppointmentUser(User $user,Request $request){
        $user = User::find($request->id);
        if($user){
                
            $user->appointment_status = $request->appointment_status;
            $user->save();
            return response(["status" => "200",'data'=>$user, 'message' => 'Appointment Updated Successfully !']);
        }else{
            return response(["status" => "404",'data'=>'User not Exists']);
        }
        
    }
    public function getUserById(User $user,$id){
        $userData=$user->getUserById($id);
        return response(["status" => "200",'data'=>$userData, 'message' => 'Success']);        
    }
    public function getUserAppointmentById(User $user,$id){
        $userData=$user->getUserAppointmentById($id);
        return response(["status" => "200",'data'=>$userData, 'message' => 'Success']);        
    }

    public function saveAppointment(Request $request){
        $scanData =NULL;
        $reportData =NULL;
       try{
            if ($scan = $request->file('scan')) {
                $destinationPath = 'scan/';
                $scanData = date('YmdHis').$scan->getClientOriginalName();
                $scan->move($destinationPath, $scanData);
            }
            if ($report = $request->file('report')) {
                $destinationPath = 'report/';
                $reportData = date('YmdHis').$report->getClientOriginalName();
                $report->move($destinationPath, $reportData);
            }

            $type_of_scan = json_encode($request->type_of_scan);
            $what_part_of_body = json_encode($request->what_part_of_body);
            
            DB::table('second_opinion')
            ->insert(array(
                'user_id'=> $request->user_id,
                'type_of_scan'=>$type_of_scan??null,
                'what_part_of_body'=>$what_part_of_body??null,
                'scan'=>$scanData??null,
                'report'=>$reportData??null,
                'comment'=>$request->comment??null,
                'selected_package'=> $request->selected_package??null
                
            ));

            return response(["status" => "200",'data'=>[], 'message' => 'Appointment Saved Successfully !']);

        }catch (Exception $e) {
            return response()->json(['status' => false, 'massage' => 'Oops! Something went wrong. ' . $e->getMessage()], 400);
        }
        
    }
    public function closeAppointment($id){
        
       try{
            DB::table('second_opinion')
            ->where('id',$id)
            ->update([
                'appointment_status' => 'true',
            ]);
            return response(["status" => "200",'data'=>[], 'message' => 'Appointment closed Successfully !']);

        }catch (Exception $e) {
            return response()->json(['status' => false, 'massage' => 'Oops! Something went wrong. ' . $e->getMessage()], 400);
        }
        
    }

}
