<?php

use App\Models\walls;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

<<<<<<< HEAD
Route::view('/', 'welcome')->name('home');
Route::view('/about', 'about')->name('about');
=======
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
>>>>>>> 3508934d94cfbb83c2f05cb060a84443c9294173

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
