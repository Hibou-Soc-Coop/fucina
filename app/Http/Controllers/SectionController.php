<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        return inertia('backend/Sections/Index', [
            'sections' => Section::all(),
        ]);
    }

    public function create()
    {
        return inertia('backend/Sections/Create', [
            'languages' => Language::all(),
        ]);
    }

    public function store(Request $request)
    {


        return redirect()->route('sections.index');
    }
}
