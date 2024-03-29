<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Payment extends Model
{
    use HasFactory;
	
	protected $table  = "payments";
	
	const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    protected $fillable = [
        'amount',
        'user_email',
		'transaction_id',
		'package_id',
        'status',

    ];
    
}
