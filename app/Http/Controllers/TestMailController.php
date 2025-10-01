<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;

class TestMailController
{

    public function send()
    {
        $data = ['name' => 'Lera'];
        Mail::to('dashkina.lera@yandex.ru')->send(New TestMail($data));
        echo "Mail has been sent";
    }

    public function receive()
    {



    }
}
