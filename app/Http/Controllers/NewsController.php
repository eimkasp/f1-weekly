<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->get('category');
        
        $query = News::published()
            ->with(['drivers', 'teams', 'races', 'tags'])
            ->latest('published_at');

        if ($category) {
            $query->where('category', $category);
        }

        $news = $query->paginate(12);

        return view('pages.news.index', [
            'news' => $news,
            'category' => $category,
        ]);
    }

    public function category(string $category): View
    {
        $news = News::published()
            ->where('category', $category)
            ->with(['drivers', 'teams', 'races', 'tags'])
            ->latest('published_at')
            ->paginate(12);

        $categoryName = str_replace('_', ' ', ucfirst($category));

        return view('pages.news.category', [
            'news' => $news,
            'category' => $category,
            'categoryName' => $categoryName,
        ]);
    }

    public function show(News $news): View
    {
        // Increment view count
        $news->increment('view_count');

        // Load relationships
        $news->load(['drivers.team', 'teams', 'races.circuit', 'tags']);

        // Get related articles
        $related = News::published()
            ->where('id', '!=', $news->id)
            ->where(function ($query) use ($news) {
                $query->where('category', $news->category)
                    ->orWhereHas('drivers', function ($q) use ($news) {
                        $q->whereIn('drivers.id', $news->drivers->pluck('id'));
                    })
                    ->orWhereHas('teams', function ($q) use ($news) {
                        $q->whereIn('teams.id', $news->teams->pluck('id'));
                    });
            })
            ->latest('published_at')
            ->limit(4)
            ->get();

        return view('pages.news.show', [
            'article' => $news,
            'related' => $related,
        ]);
    }

    public function rssFeed()
    {
        $news = News::published()
            ->latest('published_at')
            ->limit(50)
            ->get();

        return response()
            ->view('feeds.rss', compact('news'))
            ->header('Content-Type', 'application/rss+xml');
    }

    public function atomFeed()
    {
        $news = News::published()
            ->latest('published_at')
            ->limit(50)
            ->get();

        return response()
            ->view('feeds.atom', compact('news'))
            ->header('Content-Type', 'application/atom+xml');
    }
}
