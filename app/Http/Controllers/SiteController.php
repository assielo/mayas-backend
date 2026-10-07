<?php

namespace App\Http\Controllers;

use App\Models\Site;

class SiteController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Site::orderBy('nom_site')->get(['id', 'nom_site', 'localisation', 'responsable_site', 'contact']),
        ]);
    }
}
