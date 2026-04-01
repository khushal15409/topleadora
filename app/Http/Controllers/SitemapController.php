<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $base = url('/');
        $now = now()->toAtomString();

        $static = [
            ['loc' => $base.'/', 'lastmod' => $now],
            ['loc' => url('/features'), 'lastmod' => $now],
            ['loc' => url('/pricing'), 'lastmod' => $now],
            ['loc' => route('blog.index'), 'lastmod' => $now],
        ];

        $posts = BlogPost::published()
            ->get(['slug', 'updated_at'])
            ->map(fn (BlogPost $p) => [
                'loc' => route('blog.show', $p->slug),
                'lastmod' => ($p->updated_at ?? now())->toAtomString(),
            ])
            ->all();

        $urls = array_merge($static, $posts);

        $xml = view('sitemap.xml', compact('urls'))->render();

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}

