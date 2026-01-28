<?php

use App\Models\Link;

beforeEach(function () {
    // Because migration inserted 5 links by default,
    // We need to delete them to make sure we can add a new one
    Link::truncate();
});

test('if link count is over 5, add link button will be disabled', function () {
    loginAsUser();

    Link::factory()->count(5)->create();

    $page = visit('/links');

    $page->assertSee('Add Link');
    $page->assertDisabled('Add Link');
});

it('can show add link modal', function () {
    loginAsUser();

    $page = visit('/links');

    $page->click('Add Link');

    $page->assertVisible('#link-form-dialog');
});

it('can add link', function () {
    loginAsUser();

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

it('can delete link', function () {
    loginAsUser();

    $link = Link::factory()->create([
        'title' => 'Title',
        'url'   => 'https://example.com',
    ]);

    $page = visit('/links');

    $page->click('#delete-link-'.$link->id);
    $page->click("#delete-link-confirmation");

    $page->assertSee('Link deleted successfully.');
    $this->assertDatabaseMissing('links', [
        'id' => $link->id,
    ]);
});
