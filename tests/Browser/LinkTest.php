<?php

use App\Models\Link;

it('can show add link modal', function () {
    loginAsUser();

    // Because migration inserted 5 links by default,
    // We need to delete them to make sure we can add a new one
    Link::truncate();

    $page = visit('/links');

    $page->click('Add Link');

    $page->assertVisible('#link-form-dialog');
});

it('can add link', function () {
    loginAsUser();

    Link::truncate();

    $page = visit('/links');

    $page->click('Add Link');
    $page->type('title', 'New Link');
    $page->type('url', 'https://example.com');
    $page->click('Create');

    $page->assertSee('Link created successfully.');

    $this->assertDatabaseHas('links', [
        'title' => 'New Link',
        'url'   => 'https://example.com',
    ]);
});
