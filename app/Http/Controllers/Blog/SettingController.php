<?php

declare(strict_types=1);

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function edit()
    {
        $allowRegister = Setting::where('key', 'allow_register')->first();

        return Inertia::render('blog/settings/Page', [
            'allowRegister' => $allowRegister && $allowRegister->value === 'true',
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'allow_register' => ['required', 'boolean'],
        ]);

        Setting::updateOrCreate(
            ['key' => 'allow_register'],
            [
                'name' => '開放註冊',
                'value' => $validated['allow_register'] ? 'true' : 'false',
            ]
        );

        return Inertia::flash('toast', [
            'type'        => 'success',
            'message'     => 'Blog settings updated successfully.',
        ])->back();
    }
}
