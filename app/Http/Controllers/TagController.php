<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use App\Models\Tag;
use Inertia\Inertia;

class TagController extends Controller
{
    public function index()
    {
        $tags = TagResource::collection(
            Tag::paginate(10)
        );

        return Inertia::render('tags/Page', [
            'title' => 'Tags',
            'tags'  => $tags,
        ]);
    }
}
