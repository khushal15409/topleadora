<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PagesController extends Controller
{
    public function features(): View
    {
        return view('pages.features');
    }

    public function pricing(): View
    {
        return view('pages.pricing');
    }
}

