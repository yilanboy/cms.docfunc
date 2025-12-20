<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $tags = Tag::orderBy('id')
            ->when($request->query('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString()
            ->onEachSide(1);

        $tags = TagResource::collection($tags);

        return Inertia::render('tags/Page', [
            'title' => 'Tags',
            'tags'  => $tags,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Tag::create($validated);

        $lastPage = Tag::paginate(10)->lastPage();

        Inertia::flash('toast', [
            'type'    => 'success',
            'message' => 'Tag created successfully.',
        ]);

        return to_route('tags.index', ['page' => $lastPage]);
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $tag->update($validated);


        return Inertia::flash('toast', [
            'type'    => 'success',
            'message' => 'Tag updated successfully.',
        ])->back();
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return Inertia::flash('toast', [
            'type'    => 'success',
            'message' => 'Tag deleted successfully.',
        ])->back();
    }
}
