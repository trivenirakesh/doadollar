<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        return view('admin/home');
    }

    public function login()
    {
        return view('admin/auth/login');
    }

    public function checkLogin(Request $request){
        $apiUrl = CommonHelper::getConfigValue('api_url');
        $apiUrl = $apiUrl . 'login';
        $formRequest['email'] = $request->mobile;
        $formRequest['password'] = $request->password;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $formRequest);
        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($server_output,true);
        $responseArr = [];
        if($response['status'] == 'success'){
            $successMessage = $response['message'];
            $loginToken = $response['data']['token'];
            $entityId = $response['data']['id'];
            $userName = $response['data']['username'];
            session(['admin_login_token' => $loginToken]);
            session(['entity_id' => $entityId]);
            session(['entity_name' => $userName]);
            session()->save();
            $responseArr = array('status'=>200,"message" => $successMessage);
        }else{
            $messageArr = $response['message'];
            if(is_array($messageArr)){

                $responseValidation = [];
                foreach($messageArr as $key => $val){
                    $responseValidation[$key] = implode(', ',$val);
                }
                $responseArr = array('status'=>404,"message" => $responseValidation);
            }else{
                $responseArr = array('status'=>404,"message" => $messageArr);
            }
        }
        return response()->json($responseArr);
    }

    public function logout(){
        $authToken = session('admin_login_token');
        $authId = session('entity_id');
        $apiUrl = CommonHelper::getConfigValue('api_url');
        $apiUrl = $apiUrl.'logout';
        $response = Http::withToken($authToken)->post($apiUrl,['id'=>$authId]);
        $responseArr = json_decode($response,true);
        $successMessage = $responseArr['message'];
        if($response->ok()){
            session()->flush();
        }
        return redirect('adminlogin')->with('success',$successMessage);
    }

    public function form(){
        return view('admin/sampleform');
    }

    public function list(){
        return view('admin/samplelist');
    }
}
