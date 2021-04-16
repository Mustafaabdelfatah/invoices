<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class AdminAuth extends Controller
{
    public function login()
    {
        return view('dashboard.auth.login');
    }
    public function dologin(Request $request)
    {

        // return $request->all();
        //$remember = request('rememberme') == 1 ? true : false;

        $validatedData = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        //$credential = ['email' => $request->email, 'password' => $request->password];

        $attempt = admin()->attempt($validatedData);

        if($attempt){ 
            return redirect('admin');
        }
        else{
            Session::flash('error', "errors");
            return redirect()->back();
        }
    }
    public function logout()
    {
        auth()->guard('admin')->logout();
        return redirect('admin/login');
    }
}
