<?php

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('category feature', function () {
    test('list categories in page', function () {
        // Arrange
        loginAsUser();
        Category::factory()->count(3)->create();

        // Act
        $response = $this->get(route('categories.index'));

        // Assert
        $response->assertSuccessful();
    });

    test('create category', function () {
        // Arrange
        loginAsUser();

        // Act
        $response = $this->post(route('categories.store'), [
            'name'        => 'New Category',
            'icon'        => '<svg></svg>',
            'description' => 'A fresh category',
        ]);

        // Assert
        $response->assertRedirect();
        $this->assertDatabaseHas('categories', [
            'name'        => 'New Category',
            'icon'        => '<svg></svg>',
            'description' => 'A fresh category',
        ]);
    });

    test('create category requires a name', function () {
        // Arrange
        loginAsUser();

        // Act
        $response = $this->post(route('categories.store'), [
            'name' => '',
        ]);

        // Assert
        $response->assertSessionHasErrors('name');
    });

    test('edit category', function () {
        // Arrange
        loginAsUser();
        $category = Category::factory()->create([
            'name' => 'Old Name',
        ]);

        // Act
        $response = $this->patch(route('categories.update', $category), [
            'name'        => 'Updated Name',
            'description' => 'Updated description',
        ]);

        // Assert
        $response->assertRedirect();
        $this->assertDatabaseHas('categories', [
            'id'          => $category->id,
            'name'        => 'Updated Name',
            'description' => 'Updated description',
        ]);
    });

    test('delete category', function () {
        // Arrange
        loginAsUser();
        $category = Category::factory()->create([
            'name' => 'Name',
        ]);

        // Act
        $response = $this->delete(route('categories.destroy', $category));

        // Assert
        $response->assertRedirect();
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    });
});
