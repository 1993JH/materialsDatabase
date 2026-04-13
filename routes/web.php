<?php

use App\Models\categories;
use App\Models\materials;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('/about', 'about')->name('about');
Route::get('/calculations', function () {
    $categories = categories::query()
        ->select('id', 'name')
        ->orderBy('name')
        ->get();

    $categoryNames = $categories
        ->pluck('name')
        ->values()
        ->all();

    $materialsByCategory = materials::query()
        ->select('name', 'category_id')
        ->orderBy('name')
        ->get()
        ->groupBy('category_id')
        ->map(fn ($materialGroup) => $materialGroup->pluck('name')->values()->all());

    $categoryMaterialMap = $categories
        ->mapWithKeys(fn ($category) => [
            $category->name => $materialsByCategory->get($category->id, []),
        ])
        ->all();

    return view('calculations', [
        'categoryNames' => $categoryNames,
        'categoryMaterialMap' => $categoryMaterialMap,
    ]);
})->name('calculations');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
