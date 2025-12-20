<?php

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('tag feature', function () {
    test('list tags in page', function () {
        // Arrange
        loginAsUser();
        Tag::factory()->count(3)->create();

        // Act
        $response = $this->get(route('tags.index'));

        // Assert
        $response->assertSuccessful();
    });

    test('create tag', function () {
        // Arrange
        loginAsUser();

        // Act
        $response = $this->post(route('tags.store'), [
            'name' => 'New Tag',
        ]);

        // Assert
        $response->assertRedirect();
        $this->assertDatabaseHas('tags', [
            'name' => 'New Tag',
        ]);
    });

    test('edit tag', function () {
        // Arrange
        loginAsUser();
        $tag = Tag::factory()->create([
            'name' => 'Old Name',
        ]);

        // Act
        $response = $this->patch(route('tags.update', $tag), [
            'name' => 'Updated Name',
        ]);

        // Assert
        $response->assertRedirect();
        $this->assertDatabaseHas('tags', [
            'id'   => $tag->id,
            'name' => 'Updated Name',
        ]);
    });

    test('delete tag', function () {
        // Arrange
        loginAsUser();
        $tag = Tag::factory()->create([
            'name' => 'Name',
        ]);

        // Act
        $response = $this->delete(route('tags.destroy', $tag));

        // Assert
        $response->assertRedirect();
        $this->assertDatabaseMissing('links', [
            'id' => $tag->id,
        ]);
    });
});
