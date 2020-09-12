<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Profile home.
     *
     * @return View
     */
    public function index()
    {
        return view('profile.index');
    }

    /**
     * Profile edit form
     *
     * @return View
     */
    public function edit()
    {
        return view('profile.edit');
    }

    /**
     * Save profile updates.
     *
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        //
    }
}
