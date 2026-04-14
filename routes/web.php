<?php

use App\Models\categories;
use App\Models\materials;
use App\Models\walls;
use Illuminate\Http\Request;
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
        ->select('name', 'category_id', 'KgCO2e')
        ->orderBy('name')
        ->get()
        ->groupBy('category_id')
        ->map(fn ($materialGroup) => $materialGroup
            ->map(fn ($material) => [
                'name' => $material->name,
                'kgco2e' => (float) $material->KgCO2e,
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

Route::get('/calculations/wall-assemblies', function (Request $request) {
    $selectedMaterials = collect($request->query('materials', []))
        ->map(fn ($material) => trim((string) $material))
        ->filter()
        ->unique()
        ->values();

    if ($selectedMaterials->isEmpty()) {
        return response()->json(['data' => []]);
    }

    $query = walls::query()
        ->selectRaw('`Assembly_Description` as wall_assembly, `R_Value_U_Value` as r_value, `Embodied_Carbon` as embodied_carbon, `Wall_Thickness(m/in)` as thickness, `Fire_Resistance_Rating` as fire_rating');

    $selectedMaterials->each(function (string $materialName) use ($query) {
        $query->where('Assembly_Description', 'like', "%{$materialName}%");
    });

    $matches = $query
        ->orderBy('Assembly_Description')
        ->limit(200)
        ->get()
        ->map(fn ($wall) => [
            'wall_assembly' => $wall->wall_assembly,
            'r_value' => (float) $wall->r_value,
            'embodied_carbon' => (float) $wall->embodied_carbon,
            'thickness' => (float) $wall->thickness,
            'fire_rating' => (float) $wall->fire_rating,
        ])
        ->values();

    return response()->json(['data' => $matches]);
})->name('calculations.wall-assemblies');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
