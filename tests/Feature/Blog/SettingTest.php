<?php

describe('blog settings', function () {
    test('it can view blog settings page if authenticated', function () {
        loginAsUser();

        $response = $this->get(route('blog.settings.edit'));

        $response->assertStatus(200);
    });

    test('it cannot view blog settings page if guest', function () {
        $response = $this->get(route('blog.settings.edit'));

        $response->assertRedirect('/login');
    });

    test('it can update the allow_register setting', function () {
        loginAsUser();

        $response = $this->put(route('blog.settings.update'), [
            'allow_register' => true,
        ]);

        $response->assertRedirectBack();
        $this->assertDatabaseHas('settings', [
            'key' => 'allow_register',
            'value' => 'true',
        ]);

        $response = $this->put(route('blog.settings.update'), [
            'allow_register' => false,
        ]);

        $this->assertDatabaseHas('settings', [
            'key'   => 'allow_register',
            'value' => 'false',
        ]);
    });
});
