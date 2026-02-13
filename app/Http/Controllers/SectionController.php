<?php

namespace App\Http\Controllers;

use App\Helpers\QrCodeHelper;
use App\Models\Language;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSectionRequest;
use App\Http\Requests\EditSectionRequest;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use \Spatie\MediaLibrary\MediaCollections\Models\Media;


class SectionController extends Controller
{
    public function index()
    {
        $sectionRecords = Section::with('media')->get();
        $sections = [];
        foreach ($sectionRecords as $section) {
            $sections[] = [
                'id' => $section->id,
                'title' => $section->getTranslations('title'),
                'subtitle' => $section->getTranslations('subtitle'),
                'description' => $section->getTranslations('description'),
                'image' => $section->getMedia('image')->map(fn(Media $media) => [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                ]),
                'audio' => $section->getMedia('audio')->map(fn(Media $media) => [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                ]),
                'video' => $section->getMedia('video')->map(fn(Media $media) => [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                ]),
                'qrcode' => $section->getMedia('qrcode')->map(fn(Media $media) => [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                ]),
            ];
        }
        return inertia('backend/Sections/Index', [
            'sections' => $sections,
        ]);
    }

    public function create()
    {
        return inertia('backend/Sections/Create', [
            'languages' => Language::all(),
        ]);
    }

    public function store(StoreSectionRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();
        $langCodes = Language::pluck('code')->toArray();

        try {
            $section = Section::create(
                [
                    'title' => $data['title'],
                    'subtitle' => $data['subtitle'] ?? [],
                    'description' => $data['description'] ?? [],
                ]
            );

            // Generate QR Codes for each language
            foreach ($langCodes as $langCode) {
                $url = url("section/{$section->id}/{$langCode}");
                $svg = QrCodeHelper::generateSvg($url, 256);
                $section->addMediaFromString($svg)
                    ->usingFileName("qr-{$section->id}-{$langCode}.svg")
                    ->withCustomProperties(['lang' => $langCode])
                    ->toMediaCollection('qrcode');
            }

            if ($request->hasFile('image.file')) {
                foreach ($request->file('image.file') as $lang => $file) {
                    $section->addMedia($file)
                        ->toMediaCollection('image');
                }
            }
            if ($request->hasFile('video.file')) {
                foreach ($request->file('video.file') as $lang => $file) {
                    $section->addMedia($file)
                        ->toMediaCollection('video');
                }
            }
            if ($request->hasFile(key: 'audio.file')) {
                foreach ($request->file('audio.file') as $langCode => $file) {
                    $section->addMedia($file)->withCustomProperties(['language' => $langCode])
                        ->toMediaCollection('audio');
                }
            }
            if ($request->hasFile(key: 'qrcode.file')) {
                foreach ($request->file('qrcode.file') as $langCode => $file) {
                    $section->addMedia($file)
                        ->toMediaCollection('qrcode');
                }
            }
            DB::commit();
            return redirect()->route('sections.index')->with('success', 'Sezione creata con successo.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Errore durante la creazione della sezione: ' . $e->getMessage());
        }
    }
    public function show(Section $section)
    {
        $section->load('media');
        $sectionRecord = [
            'id' => $section->id,
            'title' => $section->getTranslations('title'),
            'subtitle' => $section->getTranslations('subtitle'),
            'description' => $section->getTranslations('description'),
            'image' => $section->getMedia('image')->map(fn(Media $media) => [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'custom_properties' => $media->custom_properties,
            ]),
            'audio' => $section->getMedia('audio')->map(fn(Media $media) => [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'custom_properties' => $media->custom_properties,
            ]),
            'video' => $section->getMedia('video')->map(fn(Media $media) => [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'custom_properties' => $media->custom_properties,
            ]),
            'qrcode' => $section->getMedia('qrcode')->map(fn(Media $media) => [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'custom_properties' => $media->custom_properties,
            ]),
        ];
        return inertia('backend/Sections/Show', [
            'section' => $sectionRecord,
        ]);
    }


    public function edit(Section $section)
    {
        $section->load('media');
        $sectionRecord = [
            'id' => $section->id,
            'title' => $section->getTranslations('title'),
            'subtitle' => $section->getTranslations('subtitle'),
            'description' => $section->getTranslations('description'),
            'image' => $section->getMedia('image')->map(fn(Media $media) => [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'custom_properties' => $media->custom_properties,
            ]),
            'audio' => $section->getMedia('audio')->map(fn(Media $media) => [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'custom_properties' => $media->custom_properties,
            ]),
            'video' => $section->getMedia('video')->map(fn(Media $media) => [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'custom_properties' => $media->custom_properties,
            ]),
            'qrcode' => $section->getMedia('qrcode')->map(fn(Media $media) => [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'custom_properties' => $media->custom_properties,
            ])
        ];

        return inertia('backend/Sections/Edit', [
            'section' => $sectionRecord,
        ]);
    }

    public function update(EditSectionRequest $request, Section $section)
    {
        $data = $request->validated();
        DB::beginTransaction();

        try {
            $section->update([
                'title' => $data['title'],
                'subtitle' => $data['subtitle'] ?? [],
                'description' => $data['description'] ?? [],
            ]);

            if ($request->hasFile('image.file')) {
                $section->clearMediaCollection('image');
                foreach ($request->file('image.file') as $lang => $file) {
                    $section->addMedia($file)
                        ->toMediaCollection('image');
                }
            }
            if ($request->hasFile('video.file')) {
                $section->clearMediaCollection('video');
                foreach ($request->file('video.file') as $lang => $file) {
                    $section->addMedia($file)
                        ->toMediaCollection('video');
                }
            }
            if ($request->hasFile(key: 'audio.file')) {
                foreach ($request->file('audio.file') as $langCode => $file) {
                    // Remove existing audio for this language
                    $existing = $section->getMedia('audio')->filter(fn(Media $media) => ($media->custom_properties['language'] ?? '') === $langCode);
                    foreach ($existing as $media) {
                        $media->delete();
                    }

                    $section->addMedia($file)->withCustomProperties(['language' => $langCode])
                        ->toMediaCollection('audio');
                }
            }

            DB::commit();
            return redirect()->route('sections.index')->with('success', 'Sezione aggiornata con successo.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Errore durante l\'aggiornamento della sezione: ' . $e->getMessage());
        }
    }
    public function destroy(Section $section)
    {
        $section->delete();
        return redirect()->route('sections.index')->with('success', 'Sezione eliminata con successo.');
    }
}
