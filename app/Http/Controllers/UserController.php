<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{
    public function index(){
        return view('index', [
            'title' => 'Register'
        ]);
    }

    public function registerUser(Request $request){
        $formInputs = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed']
        ]);
        $formInputs['password'] = bcrypt($formInputs['password']);
        $user = User::create($formInputs);
        return redirect('/user/login')->with('flash-message', 'Hello ' . ucfirst($user->name) . ', you have registered.');
    }

    public function showLogin(){
        return view('login', [
            'title' => 'Log In'
        ]);
    }

    public function loginUser(Request $request){
        $formInputs = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
        $checkUser = User::where('email', $request->email)->first();
        if($checkUser){
            if(auth()->attempt($formInputs)){
                $request->session()->regenerate();
                return redirect('/')->with('flash-message', 'Hello ' . ucfirst(auth()->user()->name) .', you have logged in.');
            }
        }if(!$checkUser){
            return back()->withErrors([
                'email' => 'Email does not exist.',
                'password' => ''
            ]);
        }else{
            return back()->withErrors([
                'email' => 'Invalid Credentials'
            ])->onlyInput('email');
        }
    }

    // log user out
    public function logoutUser(Request $request){
        // removes authentication from the users session
        auth()->logout();
        // recomended to invalidate the users session
        $request->session()->invalidate();
        // and regen their @csrf token
        $request->session()->regenerateToken();
        return redirect('/')->with('flash-message', 'You have now logged out.');
    }

    // redirect to GitHub log in authorization
    public function githubSignIn(){
        return Socialite::driver('github')->redirect();
    }

    // redirect from GitHub authorization back to app
    public function githubRedirect(){
        $githubUser = Socialite::driver('github')->user();
        // if they dont exist by email, add them
        $user = User::firstOrCreate([
            'email' => $githubUser->email
        ], [
            'name' => $githubUser->name,
            'password' => Hash::make(Str::random(24))
        ]);
        // log in and redirect back home
        Auth::login($user);
        return redirect('/')->with('flash-message', 'Hello ' . ucfirst(auth()->user()->name) .' you have used GitHub to log in.');
    }

    // redirect to Spotify log in authorization
    public function spotifySignIn(){
        return Socialite::driver('spotify')->redirect();
    }

    // redirect from Spotify authorization back to app
    public function spotifyRedirect(){
        $spotifyUser = Socialite::driver('spotify')->user();
        // dd($spotifyUser);
        if($spotifyUser->email === null){
            return redirect('/')->with('flash-message', 'Sorry, there is no email associated with this media. Please register to continue.');
        }else{
            // if they dont exist by email, add them
            $user = User::firstOrCreate([
                'email' => $spotifyUser->email
            ], [
                'name' => $spotifyUser->name,
                'password' => Hash::make(Str::random(24))
            ]);
            // log in and redirect back home
            Auth::login($user);
            return redirect('/')->with('flash-message', 'Hello ' . ucfirst(auth()->user()->name) .' you have used Spotify to log in.');
        }
    }
}
