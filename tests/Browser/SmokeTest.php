<?php

it('may see the app name', function () {
    $page = visit('/login');

    $page->assertSee(config('app.name'));
});
