<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeUserMail;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Rules\HasValidMx;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.signup');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email' => ['required','string','email:rfc','max:255','unique:users,email', new HasValidMx],
            'password' => 'required|string|confirmed|min:8',
        ], [
            'email.email'  => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        // dd($user);
        event(new Registered($user));
        // send mail to user
        Mail::to($user->email)->send(new WelcomeUserMail($user));

        Auth::login($user);

        return redirect('index')->with('success', 'You have successfully registered!');
    }
}
