<?php

namespace App\Http\Controllers;

use App\Models\Term;
use Illuminate\Http\Request;
use App\Models\Language;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use \Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Http\Requests\StoreTermRequest;
use App\Http\Requests\EditTermRequest;

use App\Helpers\QrCodeHelper;

class TermController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $termRecords = Term::with('media')->get();
        return inertia('backend/Terms/Index', [
            'terms' => $termRecords,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return inertia('backend/Terms/Create', [
            'languages' => Language::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTermRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();
        $langCodes = Language::pluck('code')->toArray();

        try {
            $term = Term::create([
                'term' => $data['term'],
                'definition' => $data['definition'] ?? [],
            ]);

            DB::commit();
            return redirect()->route('terms.index')->with('success', 'Termine creato con successo.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Errore durante la creazione del termine: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Term $term)
    {
        $termRecord = [
            'id' => $term->id,
            'term' => $term->getTranslations('term'),
            'definition' => $term->getTranslations('definition'),
        ];
        return inertia('backend/Terms/Show', [
            'term' => $termRecord,
            'languages' => Language::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Term $term)
    {
        $termRecord = [
            'id' => $term->id,
            'term' => $term->getTranslations('term'),
            'definition' => $term->getTranslations('definition'),
        ];
        return inertia('backend/Terms/Edit', [
            'term' => $termRecord,
            'languages' => Language::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditTermRequest $request, Term $term)
    {
        $data = $request->validated();
        DB::beginTransaction();

        try {
            $term->update([
                'term' => $data['term'],
                'definition' => $data['definition'],
            ]);

            DB::commit();
            return redirect()->route('terms.index')->with('success', 'Termine aggiornato con successo.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Errore durante l\'aggiornamento del termine: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Term $term)
    {
        try {
            $term->delete();
            return redirect()->route('terms.index')->with('success', 'Termine eliminato con successo.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Errore durante l\'eliminazione del termine: ' . $e->getMessage());
        }
    }
}
