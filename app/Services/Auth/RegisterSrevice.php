<?php

namespace App\Services\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\User;
use App\Mail\Auth\VerifyMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Mail\Mailer;

class RegisterService
{
    private $mailer;
    private $dispatcher;

    public function __construct(Mailer $mailer, Dispatcher $dispatcher)
    {
        $this->mailer = $mailer;
        $this->dispatcher = $dispatcher;
    }
    
    public function register(RegisterRequest $request)
    {
        $user = User::register(
            $request['name'],
            $request['email'],
            $request['password']
        );

        //$this->mailer->to($user->email)->send(new VerifyMail($user));
        //$this->dispatcher->dispatch(new Registered($user));
    }

    public function verify($id)
    {
        $user = User::findOrFail($id);
        $user->verify();
    }
}
