@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-white">Registre des Incidents & Securité</h2>
        <button class="bg-rose-600 hover:bg-rose-500 text-white px-4 py-2 rounded-lg text-xs font-semibold">
            + Signaler un Incident
        </button>
    </div>
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 text-slate-400 text-sm">
        Module de suivi et historique des infractions, signalements d'intrusion et véhicules suspects.
    </div>
</div>
@endsection
<?php
public function index()
{
    $incidents = Incident::latest()->get();
    return view('incidents.index', compact('incidents'));
}