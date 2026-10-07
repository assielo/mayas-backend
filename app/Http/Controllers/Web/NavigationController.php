<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NavigationController extends Controller
{
    public function dashboard() { return view('dashboard.index'); }
    public function vehicles()  { return view('vehicles.index'); }
    public function incidents() { return view('incidents.index'); }
    public function mdm()       { return view('mdm.index'); }
    public function reports()   { return view('reports.index'); }
}