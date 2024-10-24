<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserAuthenticationController extends Controller
{
    public function PinLogin(Request $request){
        $validator = Validator::make($request->all(),
        [
            //input feild name is "UserPin"
            'UserPin'=>'required|digits:5',
        ]);

        if ($validator->fails()) {
            $data =[
                'status'=>422,
                'message'=>$validator->errors()
            ];
            return response()->json($data,422);
        }else{
            //get user enter pin
            $UserPin = $request->input('UserPin');

            // Retrieve the user record by UserPin colum
            $user = DB::table('users')->where('UserPin', $UserPin)->first();

            if($user){
                $data = [
                    'status'=> 200,
                    'message'=>'User Login successful'
                ];
                return response()->json($data, 200);
            }else{
                $data =[
                    'status'=>401,
                    'message'=>'Invalid PIN, authentication failed'
                ];
                return response()->json($data, 401);
            }
        }
        
        
    }//end User Pin login



    public function userNameLogin(Request $request){
        $validator=Validator::make($request->all(),
        [
            'UserName'=> 'required',
            'mobile_password'=>'required'
        ]);

        if ($validator->fails()) {
            $data=[
                'status'=>422,
                'message'=>$validator->errors()
            ];
            return response()->json($data, 422);
        }else{
            //get user input details
            $UserName = $request->input('UserName');
            $mobile_password = $request->input('mobile_password');

            //get user deatils from DB
            $user=DB::table('users')->where('UserName', $UserName)->first();
            
        if ($UserName === $user->UserName) {
            //check the user provide password ok or not
            if (md5($mobile_password) === $user->mobile_password) {

                $data=[
                    'status'=>200,
                    'message'=>'User Login successful'
                ];
                return response()->json($data, 200);
            }else{
                $data=[
                    'status'=>422,
                    'message'=>'Invalid Password'
                ];
                return response()->json($data, 422);
            }     
        }else{
            // User not found
            $data = [
                'status' => 404,
                'message' => 'Invalid Username'
            ];
            return response()->json($data, 404);
        }
            
        }
    }//end login with user name and password function


    

}