<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Support\BlogRelatedLeadLinks;
use App\Support\MarketingInternalLinks;
use App\Support\SeoMeta;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = BlogPost::published()->paginate(9);
        $marketingLandings = MarketingInternalLinks::featuredLandings(6);
        $relatedServiceLeadLinks = BlogRelatedLeadLinks::forSidebar(8);

        return view('blog.index', compact('posts', 'marketingLandings', 'relatedServiceLeadLinks'));
    }

    public function show(string $slug): View
    {
        $post = BlogPost::published()->where('slug', $slug)->firstOrFail();
        $meta = SeoMeta::fallbackForBlogPost($post);
        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->orderByDesc('published_at')
            ->limit(4)
            ->get();
        $marketingLandings = MarketingInternalLinks::featuredLandings(6);
        $relatedServiceLeadLinks = BlogRelatedLeadLinks::forSidebar(8);

        return view('blog.show', compact('post', 'meta', 'relatedPosts', 'marketingLandings', 'relatedServiceLeadLinks'));
    }
}
