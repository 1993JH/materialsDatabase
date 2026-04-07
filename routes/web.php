<?php

use App\Models\walls;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::get('/', function () {
    $walls = collect();

    if (Schema::connection('sqlite')->hasTable((new walls)->getTable())) {
        $walls = DB::connection('sqlite')
            ->table((new walls)->getTable())
            ->get();
    }

    return view('pages.home', [
        'walls' => $walls,
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
