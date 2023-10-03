<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
	protected $rules = [
        'name' => 'required',
        'price' => 'required',
    ];
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
	
    public function savePackage(Request $request, Package $package)
    {
        try {
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                $response = $validator->messages();
                return response(["data" => $response, 'status' => 'error']);
            } else {
                $packageArr = [                   
                    "name" => $request->name,
                    "price" => $request->price,
                    "message" => $request->message??null,
                    "feature" => $request->feature??null
                ];
                $responseData = $package->savePackage($packageArr, '', 'add');
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
    public function editPackage(Request $request, Package $package)
    {
        try {

            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                $response = $validator->messages();
                return response(["data" => $response, 'status' => 'error']);
            } else {
                $packageArr = [                   
                    "name" => $request->name,
                    "price" => $request->price,
                    "message" => $request->message??null,
                    "feature" => $request->feature??null
                ];
                $responseData = $package->savePackage($packageArr, $request->id, 'edit');

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
    
    public function deleteAPackageById($id, Package $package)
    {
        try {
            $responseData = $package->deleteAPackageById($id);

            if ($responseData['status']) {
                return response(["data" => $responseData['data'], 'status' => 'success']);
            } else {
                return response()->json(['status' => false, 'massage' => $responseData['data']], 400);
            }


        } catch (Exception $e) {
            return response()->json(['status' => false, 'massage' => 'Oops! Something went wrong. ' . $e->getMessage()], 400);
        }
    }
	public function packageList(Package $package)
    {
        try {
            $responseData = $package->packageList();

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
