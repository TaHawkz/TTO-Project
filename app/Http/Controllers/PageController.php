<?php

namespace App\Http\Controllers;

use App\Models\Startup;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function ipPolicy()
    {
        return view('pages.ip-policy');
    }

    public function sopGuidelines()
    {
        return view('pages.sop-guidelines');
    }

    public function formsTemplates()
    {
        return view('pages.forms-templates');
    }

    public function disclosure()
    {
        return view('pages.disclosure');
    }

    public function industryCollaboration()
    {
        return view('pages.industry-collaboration');
    }

    public function startups()
    {
        $startups = Startup::published()->get();
        return view('pages.startups', compact('startups'));
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function terms()
    {
        return view('pages.terms');
    }
}
