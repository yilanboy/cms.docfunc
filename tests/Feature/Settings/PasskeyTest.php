<?php

use App\Models\Admin;
use App\Models\Passkey;

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
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'passkey']);
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

describe('passkey destroy', function () {
    test('it deletes the passkey', function () {
        $user = loginAsUser();

        $passkey = Passkey::factory()->create([
            'owner_id'   => $user->id,
            'owner_type' => $user->getMorphClass(),
        ]);

        $response = $this->delete(route('settings.passkey.destroy', $passkey));

        $response->assertRedirect();
        $this->assertDatabaseMissing('passkeys', [
            'id' => $passkey->id,
        ]);
    });

    test('it cannot delete someone else\'s passkey', function () {
        $user = loginAsUser();

        $otherUser = Admin::factory()->create();
        $passkey = Passkey::factory()->create([
            'owner_id'   => $otherUser->id,
            'owner_type' => $otherUser->getMorphClass(),
        ]);

        $response = $this->delete(route('settings.passkey.destroy', $passkey));

        $response->assertStatus(403);
        $this->assertDatabaseHas('passkeys', [
            'id' => $passkey->id,
        ]);
    });
});
