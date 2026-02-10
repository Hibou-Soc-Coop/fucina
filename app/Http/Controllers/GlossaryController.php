<?php

namespace App\Http\Controllers;

use App\Models\Glossary;
use Illuminate\Http\Request;

class GlossaryController extends Controller
{
    public function index()
    {
        return inertia('backend/Glossaries/Index', [
            'glossaries' => Glossary::all(),
        ]);
    }
}
