<?php

namespace App\Controllers;

class Home extends BaseController
{

    public function index(): string
    {
        // return view('welcome_message');
        return render('index');
    }
    public function about(): string
    {
        // return view('about');
        return render('about');
    }
}
