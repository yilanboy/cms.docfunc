<?php

describe('page test', function () {
    test('if user is not login, redirect to login page', function () {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    });

    test('if user is login, redirect to dashboard page', function () {
        loginAsUser();

        $response = $this->get('/');

        $response->assertRedirect('/dashboard');
    });

    test('guest can visit login page', function () {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    });

    test('guest can visit register page', function () {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
    });
});
