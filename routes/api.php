<?php

use Illuminate\Support\Facades\Route;

// Backoffice Controllers
use App\Http\Controllers\Backoffice\AuthAdminController;
use App\Http\Controllers\Backoffice\CategorieController;
use App\Http\Controllers\Backoffice\PlatController as BackPlatController;
use App\Http\Controllers\Backoffice\TableController as BackTableController;
use App\Http\Controllers\Backoffice\NotificationController;
use App\Http\Controllers\Backoffice\CommandeController as BackCommandeController;
use App\Http\Controllers\Backoffice\HistoriqueController;

// Frontoffice Controllers
use App\Http\Controllers\Frontoffice\TableController as FrontTableController;
use App\Http\Controllers\Frontoffice\PlatController as FrontPlatController;
use App\Http\Controllers\Frontoffice\CommandeController as FrontCommandeController;

// --------------------
// ADMIN (BACKOFFICE)
// --------------------

// Auth admin
Route::post('admin/login', [AuthAdminController::class, 'login']);

// Catégories CRUD
Route::get('/admin/categories', [CategorieController::class, 'index']);
Route::post('/admin/categories', [CategorieController::class, 'store']);
Route::put('/admin/categories/{id}', [CategorieController::class, 'update']);
Route::delete('/admin/categories/{id}', [CategorieController::class, 'destroy']);

// Plats CRUD
Route::get('admin/plats', [BackPlatController::class, 'index']);
Route::get('admin/plats/{id}', [BackPlatController::class, 'show']);
Route::post('admin/plats', [BackPlatController::class, 'store']);
Route::put('admin/plats/{id}', [BackPlatController::class, 'update']);
Route::delete('admin/plats/{id}', [BackPlatController::class, 'destroy']);
Route::put('admin/plats/{id}/toggle-actif', [BackPlatController::class, 'toggleActif']);

// Tables CRUD
Route::get('/admin/tables', [BackTableController::class, 'index']);
Route::post('/admin/tables', [BackTableController::class, 'store']);
Route::put('/admin/tables/{id}', [BackTableController::class, 'update']);
Route::delete('/admin/tables/{id}', [BackTableController::class, 'destroy']);

// Notifications et commandes employé
Route::get('/employe/notifications', [NotificationController::class, 'index']);
Route::post('/employe/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
Route::get('/employe/commande/{id}', [BackCommandeController::class, 'show']);
Route::post('/employe/commande/{id}/payer', [BackCommandeController::class, 'payer']);

// HISTORIQUE DES VENTES
Route::get('/admin/historique-ventes', [HistoriqueController::class, 'filter']);

// CLIENT (FRONTOFFICE)
Route::get('/client/tables', [FrontTableController::class, 'index']);
Route::get('/client/plats', [FrontPlatController::class, 'indexClient']);
Route::get('/client/plats/{id}', [FrontPlatController::class, 'show']);
Route::get('/client/categories', [FrontPlatController::class, 'categories']);
Route::get('/client/plats/categorie/{id}', [FrontPlatController::class, 'platsByCategorie']);
Route::post('/client/commandes', [FrontCommandeController::class, 'store']);
Route::get('/client/types-commande', [FrontCommandeController::class, 'getTypesCommande']);
