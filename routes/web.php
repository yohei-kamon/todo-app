<?php

use Illuminate\Support\Facades\Route;

// 誰でも見られるページ
Route::redirect('/', '/todos')->name('home');
Route::livewire('/todos', 'pages::todos.index')->name('todos.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::redirect('dashboard', '/todos')->name('dashboard');

    Route::livewire('/todos/create', 'pages::todos.create')->name('todos.create');
    Route::livewire('/todos/{todo}/edit', 'pages::todos.edit')->name('todos.edit');
});

require __DIR__.'/settings.php';
