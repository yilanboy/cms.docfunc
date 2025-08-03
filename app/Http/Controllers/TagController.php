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
}
