<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\LinkResource;
use App\Models\Link;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $links = LinkResource::collection(Link::orderBy('id')->get());

        return Inertia::render('links/Page', [
            'title' => 'Links',
            'links' => $links,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'url'   => ['required', 'string', 'max:255'],
        ]);

        Link::create($validated);

        return back();
    }

    public function update(Request $request, Link $link)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'url'   => ['required', 'string', 'max:255'],
        ]);

        $link->update($validated);

        return back();
    }
}
