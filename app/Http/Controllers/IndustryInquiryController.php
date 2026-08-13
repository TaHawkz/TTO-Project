<?php

namespace App\Http\Controllers;

use App\Models\IndustryInquiry;
use Illuminate\Http\Request;

class IndustryInquiryController extends Controller
{
    public function create()
    {
        return view('pages.industry-inquiry');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name'  => ['required', 'string', 'max:255'],
            'contact_name'  => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:30'],
            'inquiry_type'  => ['required', 'in:sponsored_research,licensing,consultancy,joint_development,tech_scouting'],
            'message'       => ['required', 'string', 'max:5000'],
        ]);

        IndustryInquiry::create($validated);

        return back()->with('success', 'Your inquiry has been received. A TTO representative will contact you within 3 business days.');
    }
}
