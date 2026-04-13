<?php

use App\Models\categories;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('/about', 'about')->name('about');
Route::get('/calculations', function () {
    $categoryNames = categories::query()
        ->orderBy('name')
        ->pluck('name')
        ->values()
        ->all();

    return view('calculations', [
        'categoryNames' => $categoryNames,
    ]);
})->name('calculations');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
