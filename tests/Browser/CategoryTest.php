<?php

it('can show add category modal', function () {
    loginAsUser();

    $page = visit('/categories');

    $page->click('Add Category');

    $page->assertVisible('#category-form-dialog');
});

it('can add category', function () {
    loginAsUser();

    $page = visit('/categories');

    $page->click('Add Category');

    $page->type('category-name-input', 'New Category');
    $page->type('category-icon-input', '<svg></svg>');
    $page->type('category-description-input', 'New Category Description');
    $page->click('Create');

    $page->assertSee('Category created successfully.');

    $this->assertDatabaseHas('categories', [
        'name' => 'New Category',
    ]);
});

it('can delete category', function () {
    loginAsUser();

    $page = visit('/categories');

    $page->click("#delete-category-1");
    $page->click("#delete-category-confirmation");

    $page->assertSee('Category deleted successfully.');
    $this->assertDatabaseMissing('categories', [
        'id' => 1,
    ]);
});
