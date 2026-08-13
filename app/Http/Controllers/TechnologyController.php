<?php

namespace App\Http\Controllers;

use App\Models\Technology;
use Illuminate\Http\Request;

class TechnologyController extends Controller
{
    public function index(Request $request)
    {
        $technologies = Technology::published()->get();
        return view('pages.technology-portfolio', compact('technologies'));
    }
}
