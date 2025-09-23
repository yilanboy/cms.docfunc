<?php

describe('page test', function () {
    test('if the user is not login, redirect to login page', function () {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    });

    test('if the user is login, redirect to dashboard page', function () {
        loginAsUser();

        $response = $this->get('/');

        $response->assertRedirect('/dashboard');
    });

    test('the guest can visit login page', function () {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    });

    test('the user can visit dashboard page', function () {
        loginAsUser();

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
    });

    test('if the user is not login, he can not visit links page', function () {
        $response = $this->get(route('links.index'));

        $response->assertRedirect('/login');
    });

    test('the user can visit links page', function () {
        loginAsUser();

        $response = $this->get(route('links.index'));

        $response->assertStatus(200);
    });

    test('if the user is not login, he can not visit tags page', function () {
        $response = $this->get(route('tags.index'));

        $response->assertRedirect('/login');
    });

    test('the user can visit tags page', function () {
        loginAsUser();

        $response = $this->get(route('tags.index'));

        $response->assertStatus(200);
    });

    test('the user can visit passkeys page', function () {
        loginAsUser();

        $response = $this->get(route('settings.passkey.index'));

        $response->assertStatus(200);
    });
});
