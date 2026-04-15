<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiKeyController extends Controller
{
    public function index()
    {
        $tokens = Auth::user()->tokens;
        return view('admin.api.keys', compact('tokens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $token = Auth::user()->createToken($request->name);

        return back()->with('new_token', $token->plainTextToken);
    }

    public function destroy($tokenId)
    {
        Auth::user()->tokens()->where('id', $tokenId)->delete();
        return back()->with('success', 'API Key revoked successfully.');
    }
}
