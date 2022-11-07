<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    //


    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        //
        return  view('profile.index');
    }
}
