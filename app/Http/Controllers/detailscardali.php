<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserCardDetail;
use Illuminate\Support\Facades\DB;
use UAParser\Result\Device;

class detailscardali extends Controller
{
    //
    function wowgetdata(){
        // return  response()->json(['name'=>'aliwew']);
        return UserCardDetail::all();
    }

    // function loginuseremail (){
    //     return  Auth::check()?Auth::user()->email:"";
    // }


    function wowpostdata(Request $req){

        $UserCardDetail = UserCardDetail::updateOrCreate(
            ['email'=> $req->email],
            ['token'=> $req->token],
            
        );
       
        // $CardDetails= new UserCardDetail;
        // $CardDetails->email=$req->email;
        // $CardDetails->token=$req->token;
        // $CardDetails->save();
        return  response()->json(['data'=>'send']);
         try{
          

         }catch(\Throwable $th){
            return  response()->json(['data'=>$th->getMessage()]);  
         }

    
    }


      public function updatedataput(Request $req){
        
       
        $CardDetailKs= UserCardDetail::find($req-> id);
        $CardDetailKs->token=$req->token;
        $CardDetailKs->save();
        return  response()->json(['data'=>'send']);
         try{
          

         }catch(\Throwable $th){
            return  response()->json(['data'=>$th->getMessage()]);  
         }
    }

  


        public function findemailtokexxn($email)
    {
       
        $useremail = UserCardDetail::where('email', $email)->first();


        //     if (is_null($useremail)) {
        //       return $useremail;
        // }
                

        if($useremail){
            return $useremail;
        }else{
            return response()->json(null);
        }
        }


            public function sendResponse($result, $message)
            {
            $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
            ];
            return response()->json($response, 200);
            }




          
             
}


