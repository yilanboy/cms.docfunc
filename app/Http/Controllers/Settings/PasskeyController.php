<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Services\Serializer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;
use Webauthn\AuthenticatorAttestationResponse;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\CeremonyStep\CeremonyStepManagerFactory;
use Webauthn\PublicKeyCredential;
use Webauthn\PublicKeyCredentialCreationOptions;

class PasskeyController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/passkeys/Page', [
            'title'    => 'Manage passkeys',
            'passkeys' => $request->user()->passkeys,
        ]);
    }

    public function store(Request $request, Serializer $serializer): RedirectResponse
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'min:3', 'max:255'],
            'passkey' => ['required', 'json'],
        ]);

        try {
            $publicKeyCredential = $serializer->fromJson($data['passkey'], PublicKeyCredential::class);

            if (! $publicKeyCredential->response instanceof AuthenticatorAttestationResponse) {
                return back()->withErrors(['passkey' => 'Invalid passkey.']);
            }

            $options = Session::get('passkey-registration-options');

            if (! $options) {
                return back()->withErrors(['passkey' => 'Invalid passkey.']);
            }

            $publicKeyCredentialCreationOptions = $serializer->fromJson(
                $options,
                PublicKeyCredentialCreationOptions::class
            );

            $csmFactory = new CeremonyStepManagerFactory;

            $publicKeyCredentialSource = AuthenticatorAttestationResponseValidator::create($csmFactory->requestCeremony())
                ->check(
                    authenticatorAttestationResponse: $publicKeyCredential->response,
                    publicKeyCredentialCreationOptions: $publicKeyCredentialCreationOptions,
                    host: $request->getHost()
                );
        } catch (Throwable) {
            return back()->withErrors(['passkey' => 'Invalid passkey.']);
        }

        $publicKeyCredentialSourceArray = $serializer->toArray($publicKeyCredentialSource);

        $request->user()->passkeys()->create([
            'name'          => $data['name'],
            'credential_id' => $publicKeyCredentialSourceArray['publicKeyCredentialId'],
            'data'          => $publicKeyCredentialSourceArray,
        ]);

        return back();
    }
}
