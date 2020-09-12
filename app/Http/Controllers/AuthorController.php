<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthorController extends Controller
{
    /**
     * List all authors except logged in author.
     *
     * @return View
     */
    public function index()
    {
        $authors = User::where('id', '!=', auth()->id())->get();

        return view('authors.index', compact('authors'));
    }

    /**
     * Show author details for guests
     *
     * @param User $user
     * @return View
     */
    public function show(User $user)
    {
        $author = User::find(request()->segment(2));
        return view('authors.show', compact('author'));
    }
}
