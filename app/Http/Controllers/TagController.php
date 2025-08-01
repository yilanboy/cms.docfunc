<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Inertia\Inertia;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::all();


        return Inertia::render('tags/Page', [
            'title' => 'Tags',
            'tags'  => $tags,
        ]);
    }
}
