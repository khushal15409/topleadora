<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactFormController extends Controller
{
    public function show(): View
    {
        return view('contact');
    }

    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        Contact::query()->create($request->contactPayload());

        if ($request->input('_return') === 'contact') {
            return redirect()
                ->route('contact')
                ->with('contact_success', true);
        }

        return redirect()
            ->route('home')
            ->withFragment('contact')
            ->with('contact_success', true);
    }
}
