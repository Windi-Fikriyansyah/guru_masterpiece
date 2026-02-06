<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/rpp', [\App\Http\Controllers\Admin\RppController::class, 'index'])->name('admin.rpp');
    Route::post('/rpp/generate', [\App\Http\Controllers\Admin\RppController::class, 'generate'])->name('admin.rpp.generate');

    Route::get('/lkpd', [\App\Http\Controllers\Admin\LkpdController::class, 'index'])->name('admin.lkpd');
    Route::post('/lkpd/generate', [\App\Http\Controllers\Admin\LkpdController::class, 'generate'])->name('admin.lkpd.generate');

    Route::get('/text-summary', [\App\Http\Controllers\Admin\TextSummaryController::class, 'index'])->name('admin.text_summary');
    Route::post('/text-summary/generate', [\App\Http\Controllers\Admin\TextSummaryController::class, 'generate'])->name('admin.text_summary.generate');

    Route::get('/soal', [\App\Http\Controllers\Admin\SoalController::class, 'index'])->name('admin.soal');
    Route::post('/soal/generate', [\App\Http\Controllers\Admin\SoalController::class, 'generate'])->name('admin.soal.generate');

    Route::get('/presentation', [\App\Http\Controllers\Admin\PresentationController::class, 'index'])->name('admin.presentation');
    Route::post('/presentation/generate', [\App\Http\Controllers\Admin\PresentationController::class, 'generate'])->name('admin.presentation.generate');

    Route::get('/rubric', [\App\Http\Controllers\Admin\RubricController::class, 'index'])->name('admin.rubric');
    Route::post('/rubric/generate', [\App\Http\Controllers\Admin\RubricController::class, 'generate'])->name('admin.rubric.generate');

    Route::get('/ice-breaking', [\App\Http\Controllers\Admin\IceBreakingController::class, 'index'])->name('admin.ice_breaking');
    Route::post('/ice-breaking/generate', [\App\Http\Controllers\Admin\IceBreakingController::class, 'generate'])->name('admin.ice_breaking.generate');

    Route::get('/curhat', [\App\Http\Controllers\Admin\CurhatController::class, 'index'])->name('admin.curhat');
    Route::post('/curhat/generate', [\App\Http\Controllers\Admin\CurhatController::class, 'generate'])->name('admin.curhat.generate');
});

require __DIR__.'/auth.php';
