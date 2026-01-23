<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Serializer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Uri;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Webauthn\Exception\InvalidDataException;
use Webauthn\PublicKeyCredentialRequestOptions;

class GeneratePasskeyAuthenticationOptionsController extends Controller
{
    public function __invoke(Serializer $serializer)
    {
        try {
            $options = new PublicKeyCredentialRequestOptions(
                challenge: Str::random(),
                rpId: Uri::of(config('app.url'))->host(),
                allowCredentials: [],
            );
        } catch (InvalidDataException $e) {
            Log::error('Can not create Webauthn authentication options.', [
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Can not create authentication options, please try again later.',
            ], 400);
        }

        try {
            $optionsJson = $serializer->toJson($options);
        } catch (SerializerExceptionInterface $e) {
            Log::error('Can not serialize authentication options.', [
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Can not serialize authentication options.',
            ], 400);
        }

        Session::flash('passkey-authentication-options', $optionsJson);

        return $optionsJson;
    }
}
