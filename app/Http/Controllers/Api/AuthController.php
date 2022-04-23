<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponseHelper;

class AuthController extends Controller
{
      use ApiResponseHelper;
    public function register(Request $request){
     
     $validator=Validator::make($request->all(),[
         'name'=>'required',
        
         'email'=>'required|email|unique:users',
         'password'=>'required|min:8',
         'mobile'=>'required',


     ]);
     if($validator->fails()){
 return $this->errorResponse($validator->errors(),  $this->invalid());
     }

     $user=new User();

     $user->name=$request->name;
   
     $user->email=$request->email;
 
     $user->phone=$request->mobile;

     $user->password=Hash::make($request->password);
    
     $token=$user->createToken('acesstoken')->accessToken;
     $user->save();
   
    return response()->json(['status'=>true,'msg'=>'Register successful!!' ,'acesstoken'=>$token,'data'=>$user],200);

   

    }


 





    public function login(Request $request){
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
            $res=Auth::user();
            $token=$res->createToken('acesstoken')->accessToken;

            return response()->json(['acessToken'=>$token,'data'=>$res,'status'=>true,'msg'=>'login sucessful'],$this->success());

        }else{
             return response()->json(['msg'=>'unauthorized','status'=>false],$this->failed());
        }

    }

    public function myprofile(Request $request){
        $user=Auth::user();
      
        if($user){
 return response()->json(['msg'=>'success','status'=>true,'data'=>$user],$this->success());
        }else{
            return response()->json(['msg'=>'unauthorized','status'=>false],$this->failed()); 
        }
       
    }

    public function addtask(Request $request){
        $user=Auth::user();
        if(!$user){
            return response()->json(['msg'=>'unauthorized','status'=>false],$this->failed());   
        }
     $validator=Validator::make($request->all(),[
         'title'=>'required',
         'description'=>'required',
     ]);
         if($validator->fails()){
 return $this->errorResponse($validator->errors(),  $this->invalid());
     }

     $task=new Task();
     $task->title=$request->title;
     $task->description=$request->description;
     $task->user_id=Auth::user()->id;
   $res=  $task->save();
if($res){
     return response()->json(['status'=>true,'data'=>$task],$this->success());
}else{
    return response()->json(['status'=>false,'msg'=>'Something went wrong']);
}
    }

    public function gettask(){
        $user=Auth::user();
         if(!$user){
            return response()->json(['msg'=>'unauthorized','status'=>false],$this->failed());   
        }
        $tasks=Task::where('user_id',$user->id)->get();
        if($tasks){
 return response()->json(['status'=>true,'data'=>$tasks],$this->success());
        }else{
    return response()->json(['status'=>false,'msg'=>'Something went wrong']);
        }
    }

    public function deletetask($id=null){
           $user=Auth::user();
         if(!$user){
            return response()->json(['msg'=>'unauthorized','status'=>false],$this->failed());   
        }
$task=Task::find($id);
if(!$task){
      return response()->json(['status'=>false,'msg'=>'No Task Found']);
}
$res=$task->delete();
$tasks=Task::where('user_id',$user->id)->get();
       if($res){
 return response()->json(['status'=>true,'data'=>$tasks],$this->success()); 
        }
        else{
                return response()->json(['status'=>false,'msg'=>'Something went wrong']);
        }
    }

    
}
