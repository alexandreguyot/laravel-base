<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Str;

class PageBuilderController extends Controller
{
    /**
     * Full-screen editor for a ContentPage.
     */
    public function edit(ContentPage $contentPage)
    {
        return view('admin.page-builder.editor', [
            'page' => $contentPage,
        ]);
    }

    /**
     * Save GrapeJS content + rendered HTML/CSS.
     */
    public function save(Request $request, ContentPage $contentPage): JsonResponse
    {
        $validated = $request->validate([
            'builder_content' => ['required', 'array'],
            'builder_html'    => ['required', 'string'],
            'builder_css'     => ['nullable', 'string'],
            'slug'            => ['nullable', 'string', 'max:255'],
        ]);

        // Auto-generate slug from title if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($contentPage->title);
        }

        // Ensure slug uniqueness (exclude current record)
        $slug  = $validated['slug'];
        $count = ContentPage::where('slug', $slug)->where('id', '!=', $contentPage->id)->count();
        if ($count > 0) {
            $slug = $slug . '-' . $contentPage->id;
        }

        $contentPage->update([
            'builder_content' => $validated['builder_content'],
            'builder_html'    => $validated['builder_html'],
            'builder_css'     => $validated['builder_css'] ?? '',
            'use_builder'     => true,
            'slug'            => $slug,
        ]);

        return response()->json([
            'success'    => true,
            'message'    => 'Page sauvegardée.',
            'public_url' => $contentPage->slug ? route('page.show', $contentPage->slug) : null,
        ]);
    }

    /**
     * Live preview endpoint — returns raw HTML for the iframe canvas.
     * GrapeJS calls this to show a "real" preview outside the canvas.
     */
    public function preview(ContentPage $contentPage): Response
    {
        $cssUrl = Vite::asset('resources/css/app.css');

        $html = '<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . e($contentPage->title) . '</title>
    <link rel="stylesheet" href="' . $cssUrl . '">
    <style>' . ($contentPage->builder_css ?? '') . '</style>
</head>
<body>
' . ($contentPage->builder_html ?? '<p class="p-8 text-base-content/40">Aucun contenu.</p>') . '
</body>
</html>';

        return response($html)->header('Content-Type', 'text/html');
    }

    /**
     * Upload an image from the GrapeJS asset manager.
     */
    public function uploadMedia(Request $request, ContentPage $contentPage): JsonResponse
    {
        $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['file', 'image', 'max:5120'],
        ]);

        $urls = [];
        foreach ($request->file('files') as $file) {
            $media  = $contentPage->addMedia($file)->toMediaCollection('builder_media');
            $urls[] = $media->getUrl();
        }

        // GrapeJS asset manager expects { data: [{ src: '...' }] }
        return response()->json([
            'data' => array_map(fn ($url) => ['src' => $url], $urls),
        ]);
    }
}
