<?php

namespace App\Http\Controllers;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    //
    
public function index()
{
    $audits = Audit::with('user')->latest()->get();

    return view('audit.index', compact('audits'));
}
}
