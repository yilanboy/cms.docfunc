<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class PasskeyController extends Controller
{
    public function index()
    {
        return Inertia::render('settings/passkeys/Page', [
            'title' => 'Manage passkeys',
        ]);
    }
}
