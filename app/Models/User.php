<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'type_of_scan',
        'what_part_of_body',
        'scan',
        'report',
        'comment',
        'selected_package',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function saveUser($request) : self
    {   
        $this->email = $request->email;
        $this->password = Hash::make($request->password);
        $this->save();
        
        return $this;
    }

    public function updateUserData($request){
        $scanData =NULL;
        $reportData =NULL;
       
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

        $user = User::find($request->id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->type_of_scan = json_encode($request->type_of_scan);
        $user->what_part_of_body = json_encode($request->what_part_of_body);
        $user->scan = $scanData;
        $user->report = $reportData;
        $user->comment = $request->comment;
        $user->selected_package = $request->selected_package;
        $user->phone = $request->phone;
        $user->save();
        
        return $user;
    }

    public function getAllUser(){
        return User::all();
    }

}
