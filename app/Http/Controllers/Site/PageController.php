<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\ContentPage;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Vite;

class PageController extends Controller
{
    public function show(string $slug): Response
    {
        $page = ContentPage::where('slug', $slug)->firstOrFail();

        if ($page->use_builder && $page->builder_html) {
            $cssUrl = Vite::asset('resources/css/app.css');

            $html = '<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . e($page->title) . '</title>
    <link rel="stylesheet" href="' . $cssUrl . '">
    <style>' . ($page->builder_css ?? '') . '</style>
</head>
<body>
' . $page->builder_html . '
</body>
</html>';

            return response($html)->header('Content-Type', 'text/html');
        }

        // Fallback: classic Blade view
        return response()->view('site.page', ['page' => $page]);
    }
}
