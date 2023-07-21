<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;

use App\Mail\SendContactMessage;

class Contactus extends Component
{
    public $step = 1;
    public $name;
    public $email;
    public $message;

    public function sendMessage(){
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        Mail::to(env('MAIL_FROM_ADDRESS'))->send(new SendContactMessage($this->name, $this->email, $this->message));

        $this->step = 2;
    }

    public function render()
    {
        return view('livewire.frontend.contactus');
    }
}
