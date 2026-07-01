<?php

use App\Models\Category;

test('the delete button will be disabled if the category is default', function () {
    loginAsUser();

    $category = Category::factory()->create([
        'is_default' => true,
    ]);

    $page = visit('/categories');

    $page->assertAttribute("#delete-category-$category->id", 'disabled', '');
});

test('the delete button will be enabled on not default category', function () {
    loginAsUser();

    $category = Category::factory()->create([
        'is_default' => false,
    ]);

    $page = visit('/categories');

    $page->assertAttributeMissing("#delete-category-$category->id", 'disabled', '');
});

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
