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
        ->selectRaw('name, category_id, KgCO2e, "Conductivity(W/mK)" as conductivity')
        ->orderBy('name')
        ->get()
        ->groupBy('category_id')
        ->map(fn ($materialGroup) => $materialGroup
            ->map(fn ($material) => [
                'name' => $material->name,
                'kgco2e' => (float) $material->KgCO2e,
                'conductivity' => (float) $material->conductivity,
            ])
            ->values()
            ->all());

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
