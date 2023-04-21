<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerifyCode extends Model
{
    use HasFactory;
    protected $table ='user_verify_code';
    protected $fillable =['email','code'];
}
