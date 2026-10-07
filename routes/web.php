<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\IncidentController;
use Illuminate\Support\Facades\Route;

// 1. Redirection racine -> login
Route::get('/', fn () => redirect('/login'));

// 2. Authentification
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 3. Espace protégé (middleware auth)
Route::middleware('auth')->group(function () {

    // Supervision / Dashboard
    Route::get('/dashboard', fn () => view('index_dashboard'))->name('dashboard');

    // Véhicules & Parking
    Route::get('/vehicles/manage', fn () => view('vehicles.index'))->name('vehicles.index');

    // Incidents
    Route::get('/incidents', [IncidentController::class, 'index'])->name('incidents.index');
    Route::post('/incidents/store', [IncidentController::class, 'store'])->name('incidents.store');

    // MDM / Appareils
  // MDM / Appareils
Route::get('/mdm', function () {
    $devices = class_exists(\App\Models\Device::class) 
        ? \App\Models\Device::with('agent')->latest()->get() 
        : collect();

    return view('admin.devices', compact('devices'));
})->name('mdm.index');

    // Audits / Rapports (pointe vers AuditController)
    Route::get('/reports', [AuditController::class, 'index'])->name('reports.index');
    Route::post('/reports/export', [AuditController::class, 'exportCsv'])->name('reports.export');

    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/access', [ProfileController::class, 'updateAccess'])->name('profile.updateAccess');

    // API JSON
    Route::get('/api/sites', [SiteController::class, 'index']);
    Route::get('/api/agents', [AgentController::class, 'index']);
    Route::post('/api/agents', [AgentController::class, 'store']);
    Route::get('/api/agents/{agent}', [AgentController::class, 'show']);
    Route::put('/api/agents/{agent}', [AgentController::class, 'update']);
    Route::delete('/api/agents/{agent}', [AgentController::class, 'destroy']);
    Route::get('/api/agents-export/wave', [AgentController::class, 'exportWave']);
    Route::get('/api/agents-export/wave-csv', [AgentController::class, 'downloadWaveCsv']);
});

// Compatibilité
Route::redirect('/index_dashboard', '/dashboard');
Route::get('/get_sites.php', fn () => redirect('/api/sites'));
Route::get('/get_agents.php', fn () => redirect('/api/agents'));