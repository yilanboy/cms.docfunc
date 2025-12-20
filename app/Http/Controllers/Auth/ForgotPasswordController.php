<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;

class ForgotPasswordController extends Controller
{
    public function create()
    {
        return Inertia::render('forgot-password/Page');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::ResetLinkSent
            ? Inertia::flash('toast', [
                'type'        => 'success',
                'message'     => 'Reset link sent',
                'description' => __($status)
            ])->back()
            : Inertia::flash('toast', [
                'type'        => 'danger',
                'message'     => 'Failed to send reset link',
                'description' => __($status)
            ])->back();
    }
}
