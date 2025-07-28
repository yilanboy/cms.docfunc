<?php

describe('page test', function () {
    test('smoke test', function () {
        $response = $this->get('/');

        $response->assertStatus(200);
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
