<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

ini_set('json.exceptions', '1');

use App\Http\Controllers\Controller;
use App\Http\Requests\PasskeyLoginRequest;
use App\Models\Admin;
use App\Models\Passkey;
use App\Services\Serializer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Webauthn\AuthenticatorAssertionResponse;
use Webauthn\AuthenticatorAssertionResponseValidator;
use Webauthn\CeremonyStep\CeremonyStepManagerFactory;
use Webauthn\Exception\AuthenticatorResponseVerificationException;
use Webauthn\PublicKeyCredential;
use Webauthn\PublicKeyCredentialRequestOptions;
use Webauthn\PublicKeyCredentialSource;

class PasskeyLoginController extends Controller
{
    public function __invoke(PasskeyLoginRequest $request, Serializer $serializer)
    {
        $request->ensureIsNotRateLimited();
        $validated = $request->validated();

        try {
            $publicKeyCredential = $serializer->fromJson($validated['answer'], PublicKeyCredential::class);

            if (! $publicKeyCredential->response instanceof AuthenticatorAssertionResponse) {
                return Inertia::flash('toast', [
                    'type'        => 'danger',
                    'message'     => 'Login failed',
                    'description' => 'Passkey is invalid',
                ])->back();
            }

            $rawId = json_decode($validated['answer'], true)['rawId'];

            $passkey = Passkey::query()
                ->where('credential_id', $rawId)
                ->where('owner_type', Admin::class)
                ->first();

            if (! $passkey) {
                return Inertia::flash('toast', [
                    'type'        => 'danger',
                    'message'     => 'Login failed',
                    'description' => 'Passkey is invalid',
                ])->back();
            }

            $publicKeyCredentialSource = $serializer->fromJson(json_encode($passkey->data),
                PublicKeyCredentialSource::class);

            $options = Session::get('passkey-authentication-options');

            if (! $options) {
                return Inertia::flash('toast', [
                    'type'        => 'danger',
                    'message'     => 'Login failed',
                    'description' => 'Passkey is invalid',
                ])->back();
            }

            $publicKeyCredentialRequestOptions = $serializer->fromJson($options,
                PublicKeyCredentialRequestOptions::class);

            AuthenticatorAssertionResponseValidator::create(new CeremonyStepManagerFactory()->requestCeremony())
                ->check(
                    publicKeyCredentialSource: $publicKeyCredentialSource,
                    authenticatorAssertionResponse: $publicKeyCredential->response,
                    publicKeyCredentialRequestOptions: $publicKeyCredentialRequestOptions,
                    host: request()->getHost(),
                    userHandle: null
                );
        } catch (SerializerExceptionInterface|AuthenticatorResponseVerificationException) {
            return Inertia::flash('toast', [
                'type'        => 'danger',
                'message'     => 'Login failed',
                'description' => 'Passkey is invalid',
            ])->back();
        }

        $passkey->update([
            'last_used_at' => now(),
        ]);

        Auth::loginUsingId(id: $passkey->owner_id, remember: true);

        $request->clearRateLimit();
        Inertia::clearHistory();
        Session::regenerate();

        Inertia::flash('toast', [
            'type'        => 'success',
            'message'     => 'Welcome back!',
            'description' => 'You have successfully logged in.',
        ]);

        return redirect()->intended(route('dashboard'));
    }
}
