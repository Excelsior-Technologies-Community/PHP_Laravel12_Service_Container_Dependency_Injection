<?php

namespace App\Http\Controllers;

use App\Contracts\GreetingInterface;

class GreetingController extends Controller
{
    protected GreetingInterface $greeting;

    public function __construct(GreetingInterface $greeting)
    {
        $this->greeting = $greeting;
    }

    public function index()
    {
        $message = $this->greeting->greet();
        return view('greeting', compact('message'));
    }
}
