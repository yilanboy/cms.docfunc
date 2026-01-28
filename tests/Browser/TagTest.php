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

    $page->type('tag-name-input', 'New Tag');
    $page->click('Create');

    $page->assertSee('Tag created successfully.');

    $this->assertDatabaseHas('tags', [
        'name' => 'New Tag',
    ]);
});

it('can delete tag', function () {
    loginAsUser();

    $page = visit('/tags');

    $page->click("#delete-tag-1");
    $page->click("#delete-tag-confirmation");

    $page->assertSee('Tag deleted successfully.');
    $this->assertDatabaseMissing('tags', [
        'id' => 1,
    ]);
});
