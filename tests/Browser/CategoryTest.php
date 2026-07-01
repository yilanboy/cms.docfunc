<?php

use App\Models\Category;

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

it('cannot delete default category', function () {
    loginAsUser();

    $category = Category::factory()->create([
        'is_default' => true,
    ]);

    $page = visit('/categories');

    $page->click("#delete-category-$category->id");
    $page->click('#delete-category-confirmation');

    $page->assertSee('Default category cannot be deleted.');
    $this->assertDatabaseHas('categories', [
        'id'         => $category->id,
        'is_default' => true,
    ]);
});

it('can delete non default category', function () {
    loginAsUser();

    $category = Category::factory()->create();

    $page = visit('/categories');

    $page->click("#delete-category-$category->id");
    $page->click('#delete-category-confirmation');

    $page->assertSee('Category deleted successfully.');
    $this->assertDatabaseMissing('categories', [
        'id' => $category->id,
    ]);
});
