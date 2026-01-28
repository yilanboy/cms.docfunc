<?php

describe('passkey options', function () {
    test('it generates passkey registration options', function () {
        loginAsUser();

        $response = $this->get(route('passkeys.register-options'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $this->assertNotNull(session('passkey-registration-options'));
    });
});

describe('passkey store', function () {
    test('it validates the name and passkey', function () {
        loginAsUser();

        $response = $this->post(route('settings.passkey.store'), [
            'name'    => '',
            'passkey' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'passkey']);
    });

    test('it fails with invalid passkey', function () {
        loginAsUser();

        $response = $this->post(route('settings.passkey.store'), [
            'name'    => 'New Passkey',
            'passkey' => json_encode(['invalid' => 'data']),
        ]);

        $response->assertSessionHasErrors('passkey');
    });
});
