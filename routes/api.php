<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\VisitController;
use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Middleware\EnsureDeviceIsActive;
use App\Http\Controllers\Api\V1\IncidentController;
use App\Http\Controllers\Api\V1\AdminDeviceController;
use App\Http\Controllers\Api\V1\AuditReportController;
use App\Http\Controllers\Api\V1\SyncController;


Route::post('/v1/sync/scan', [SyncController::class, 'syncScan']);
Route::post('/v1/sync/incident', [SyncController::class, 'syncIncident']);
Route::post('/v1/mdm/heartbeat', [SyncController::class, 'heartbeatMDM']);
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    
    // Synchronisation depuis le mobile (avec sécurité MDM)
    Route::middleware(EnsureDeviceIsActive::class)->group(function () {
        Route::post('/visits/sync-offline', [SyncController::class, 'syncOfflineBatch']);
    });

    // Exportation CSV pour la console Web
    Route::get('/reports/audit/download-csv', [AuditReportController::class, 'downloadCsv']);
});
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    
    // Administration MDM (Console Web)
    Route::get('/admin/devices', [AdminDeviceController::class, 'index']);
    Route::patch('/admin/devices/{id}/status', [AdminDeviceController::class, 'updateStatus']);

    // Gestion des Incidents & Recherche
    Route::get('/vehicles/search', [IncidentController::class, 'searchByPlate']);

    // Exportation & Audits
    Route::get('/reports/audit', [AuditReportController::class, 'export']);
});
Route::prefix('v1')->group(function () {
    
    // Routes Authentifiées
    Route::middleware('auth:sanctum')->group(function () {
        
        // Routes Mobile (Protégées par le Middleware MDM)
        Route::middleware(EnsureDeviceIsActive::class)->group(function () {
            Route::post('/visits/entry', [VisitController::class, 'recordEntry']);
            Route::put('/visits/{id}/exit', [VisitController::class, 'recordExit']);
            Route::post('/devices/heartbeat', [DeviceController::class, 'heartbeat']);
        });

        // Console Web & Dashboard
        Route::get('/dashboard/metrics', [DashboardController::class, 'metrics']);
        Route::get('/dashboard/active-visits', [DashboardController::class, 'activeVisits']);
    });
});

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::post('/visits/entry', [VisitController::class, 'recordEntry']);
    Route::put('/visits/{id}/exit', [VisitController::class, 'recordExit']);
});

// Routes publiques pour l'authentification OTP
Route::post('/auth/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);

// Exemple de route protégée par Sanctum après connexion
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::get('/get_agents', function () {
    try {
        $agents = DB::table('agents')
            ->leftJoin('sites', 'agents.site_id', '=', 'sites.id')
            ->select(
                'agents.id',
                'agents.matricule',
                'agents.nom_prenom',
                'agents.sexe',
                'agents.numero_piece',
                'agents.numero_wave',
                'agents.salaire',
                'agents.statut',
                'sites.nom_site as site_nom'
            )
            ->orderBy('agents.id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $agents
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur BDD : ' . $e->getMessage()
        ], 500);
    }
});
