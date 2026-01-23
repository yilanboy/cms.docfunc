<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Serializer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Uri;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Webauthn\AuthenticatorSelectionCriteria;
use Webauthn\Exception\InvalidDataException;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialUserEntity;

class GeneratePasskeyRegisterOptionsController extends Controller
{
    public function __invoke(Request $request, Serializer $serializer): JsonResponse|string
    {
        $relatedPartyEntity = new PublicKeyCredentialRpEntity(
            name: config('app.name'),
            id: Uri::of(config('app.url'))->host()
        );

        try {
            $userEntity = new PublicKeyCredentialUserEntity(
                name: $request->user()->name,
                id: (string) $request->user()->id,
                displayName: $request->user()->name
            );
        } catch (InvalidDataException $e) {
            Log::error('Can not create Webauthn user entity.', [
                'user_id'   => $request->user()->id,
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Can not create authentication options, please try again later.',
            ], 500);
        }

        $authenticatorSelectionCriteria = AuthenticatorSelectionCriteria::create(
            authenticatorAttachment: AuthenticatorSelectionCriteria::AUTHENTICATOR_ATTACHMENT_NO_PREFERENCE,
            userVerification: AuthenticatorSelectionCriteria::USER_VERIFICATION_REQUIREMENT_REQUIRED,
            residentKey: AuthenticatorSelectionCriteria::RESIDENT_KEY_REQUIREMENT_REQUIRED,
        );

        try {
            $options = new PublicKeyCredentialCreationOptions(
                rp: $relatedPartyEntity,
                user: $userEntity,
                challenge: Str::random(),
                authenticatorSelection: $authenticatorSelectionCriteria
            );
        } catch (InvalidDataException $e) {
            Log::error('Can not create Webauthn registration options.', [
                'user_id'   => $request->user()->id,
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Can not create authentication options, please try again later.',
            ], 400);
        }

        try {
            $optionsJson = $serializer->toJson($options);
        } catch (SerializerExceptionInterface  $e) {
            Log::error('Can not serialize Webauthn registration options.', [
                'user_id'   => $request->user()->id,
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Can not serialize authentication options.',
            ], 400);
        }

        Session::flash('passkey-registration-options', $optionsJson);

        return $optionsJson;
    }
}
