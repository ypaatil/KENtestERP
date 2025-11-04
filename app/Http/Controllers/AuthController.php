<?php

namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
  function __construct()
    {
        
        $this->middleware('database');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function auth(Request $request)
    {
        
     
        $username=$request->post('username');
        $password=$request->post('password');

               
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);


     $userInfo = Login::where('username','=', $request->username)->where('password','=', $request->password)->first();  
//   Auth::logoutOtherDevices($userInfo->password);
//         return redirect()->route('/dashboard2nd');
       if(isset($userInfo->userId)){
            $request->session()->put('ADMIN_LOGIN',true);
            $request->session()->put('userId',$userInfo->userId);
            $request->session()->put('user_type',$userInfo->user_type);
            $request->session()->put('vendorId',$userInfo->vendorId);
            $request->session()->put('username',$userInfo->username);
            $request->session()->put('password',$userInfo->password);
            $request->session()->put('gpo_approval_id',$userInfo->gpo_approval_id); 
            $request->session()->put('year_id',$request->year_id);  
            
            auth()->loginUsingId($userInfo->userId);
            
            return redirect('/dashboard2nd');
        }else{
            $request->session()->flash('error','Please enter valid login details');
            return redirect('login');
        }
           
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Login  $login
     * @return \Illuminate\Http\Response
     */
    public function show(Login $login)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Login  $login
     * @return \Illuminate\Http\Response
     */
    public function edit(Login $login)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Login  $login
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Login $login)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Login  $login
     * @return \Illuminate\Http\Response
     */
    public function destroy(Login $login)
    {
        //
    }
}
