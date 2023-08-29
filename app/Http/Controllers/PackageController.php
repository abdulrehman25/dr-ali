<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;

class PackageController extends Controller
{
    public function getPackage($id){
       
        $package = Package::find($id);
        if($package){
			return response()->json([
						'status' => true,
						'massage' => 'data fetched successfully.',
						'data' => $package
					], 200);
        }else{
           return response()->json([
						'status' => false,
						'massage' => 'no data found.',
					], 400);
        }
    }

   
}
