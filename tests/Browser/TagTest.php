<?php

it('can show add tag modal', function () {
    loginAsUser();

    $page = visit('/tags');

    $page->click('Add Tag');

    $page->assertVisible('#tag-form-dialog');
});

it('can add tag', function () {
    loginAsUser();

    $page = visit('/tags');

    $page->click('Add Tag');

    $page->type('name', 'New Tag');
    $page->click('Create');

    $page->assertSee('Tag created successfully.');
    // $page->assertSee('New Tag');
});
