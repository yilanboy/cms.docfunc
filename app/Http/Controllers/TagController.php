<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use App\Models\Tag;
use Inertia\Inertia;

class TagController extends Controller
{
    public function index()
    {
        $tags = TagResource::collection(
            Tag::orderBy('id')->paginate(10)->onEachSide(1)
        );

        return Inertia::render('tags/Page', [
            'title' => 'Tags',
            'tags'  => $tags,
        ]);
    }
}
