<?php

namespace App\Http\Controllers;

class RootController extends Controller
{
    public function __invoke()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('login');
    }
}
