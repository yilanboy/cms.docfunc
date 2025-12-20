<?php

use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('link feature', function () {
    test('list links in page', function () {
        // Arrange
        loginAsUser();
        Link::factory()->count(3)->create();

        // Act
        $response = $this->get(route('links.index'));

        // Assert
        $response->assertSuccessful();
    });

    test('create link', function () {
        // Arrange
        loginAsUser();
        // Ensure the below limit for deterministic behavior
        Link::query()->delete();

        // Act
        $response = $this->post(route('links.store'), [
            'title' => 'New Link',
            'url'   => 'https://example.com',
        ]);

        // Assert
        $response->assertRedirect();
        $this->assertDatabaseHas('links', [
            'title' => 'New Link',
            'url'   => 'https://example.com',
        ]);
    });

    test("if links count is over 5, user can't create a link", function () {
        // Arrange
        loginAsUser();
        // Reset links and seed exactly 5
        Link::query()->delete();
        Link::factory()->count(5)->create();

        // Act
        $response = $this->post(route('links.store'), [
            'title' => 'Should Not Create',
            'url'   => 'https://blocked.example.com',
        ]);

        // Assert
        $response->assertRedirect();
        $this->assertDatabaseCount('links', 5);
        $this->assertDatabaseMissing('links', [
            'title' => 'Should Not Create',
            'url'   => 'https://blocked.example.com',
        ]);
    });

    test('edit link', function () {
        // Arrange
        loginAsUser();
        $link = Link::factory()->create([
            'title' => 'Old Title',
            'url'   => 'https://old.example.com',
        ]);

        // Act
        $response = $this->patch(route('links.update', $link), [
            'title' => 'Updated Title',
            'url'   => 'https://updated.example.com',
        ]);

        // Assert
        $response->assertRedirect();
        $this->assertDatabaseHas('links', [
            'id'    => $link->id,
            'title' => 'Updated Title',
            'url'   => 'https://updated.example.com',
        ]);
    });

    test('delete link', function () {
        // Arrange
        loginAsUser();
        $link = Link::factory()->create([
            'title' => 'Title',
            'url'   => 'https://example.com',
        ]);

        // Act
        $response = $this->delete(route('links.destroy', $link));

        // Assert
        $response->assertRedirect();
        $this->assertDatabaseMissing('links', [
            'id' => $link->id,
        ]);
    });
});
