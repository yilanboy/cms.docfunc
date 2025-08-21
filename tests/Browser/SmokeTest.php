<?php

it('may see the app name', function () {
    $page = visit('/');

    $page->assertSee(config('app.name'));
});
