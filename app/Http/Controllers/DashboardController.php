<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Link;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $totalLinks = Link::count();
        $totalTags = Link::count();

        return Inertia::render('dashboard/Page', [
            'title'      => 'Dashboard',
            'totalLinks' => $totalLinks,
            'totalTags'  => $totalTags,
        ]);
    }
}
