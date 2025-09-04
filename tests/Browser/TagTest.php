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

    $this->assertDatabaseHas('tags', [
        'name' => 'New Tag',
    ]);
});

it('can delete tag', function () {
    loginAsUser();

    $page = visit('/tags');

    $deleteDialogId = "delete-tag-1";
    $deleteConfirmationId = "delete-tag-confirmation";

    $page->click($deleteDialogId);
    $page->click($deleteConfirmationId);

    $page->assertSee('Tag deleted successfully.');
    $this->assertDatabaseMissing('tags', [
        'id' => 1,
    ]);
});
