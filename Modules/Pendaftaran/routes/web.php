<?php

use Illuminate\Support\Facades\Route;
use Modules\Pendaftaran\Http\Controllers\PendaftaranController;

Route::middleware('auth')->prefix('pendaftaran')->name('pendaftaran.')->group(function () {
    Route::get('/', [PendaftaranController::class, 'index'])->name('index');
    Route::post('/books', [PendaftaranController::class, 'storeBook'])->name('books.store');
    Route::put('/books/{book}', [PendaftaranController::class, 'updateBook'])->name('books.update');
    Route::delete('/books/{book}', [PendaftaranController::class, 'destroyBook'])->name('books.destroy');
    Route::post('/loans', [PendaftaranController::class, 'storeLoan'])->name('loans.store');
    Route::put('/loans/{loan}', [PendaftaranController::class, 'updateLoan'])->name('loans.update');
    Route::delete('/loans/{loan}', [PendaftaranController::class, 'destroyLoan'])->name('loans.destroy');
    Route::get('/loans/{loan}/document', [PendaftaranController::class, 'document'])->name('loans.document');
});
