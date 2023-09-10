<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {  
        $inputVal = $request->all();
   
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
   
        if(auth()->attempt(array('email' => $inputVal['email'], 'password' => $inputVal['password']))){

            switch (auth()->user()->role) {
                case 'admin':
                    return redirect('/user');
                    break;
                case 'manager':
                    return redirect('/manager');
                    break;
                case 'moderator':
                    return redirect('/moderator');
                    break;
                case 'agent':
                    return redirect('/agent');
                    break;
                case 'creator':
                    return redirect('/pagelist');
                    break;
                case 'viewer':
                    return redirect('/viewerdashboard');
                    break;
                default:
                    return redirect()->route('home');
              }
        } else {
            return redirect()->route('login')
                ->with('error','Email & Password are incorrect.');
        }

    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect('/login');
    }
}
