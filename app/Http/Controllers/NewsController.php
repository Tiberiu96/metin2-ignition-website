<?php

namespace App\Http\Controllers;

use App\Models\Web\News;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        $news = News::query()
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->paginate(10);

        return view('pages.news', compact('news'));
    }
}
