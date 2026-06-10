<?php

namespace App\Http\Controllers;

use App\User;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderBy('name')->get();

        $audits = Audit::with('user')
            ->when($request->filled('user_id'), function ($query) use ($request) {
                if ($request->user_id === 'system') {
                    return $query->whereNull('user_id');
                }

                return $query->where('user_id', $request->user_id);
            })
            ->latest()
            ->paginate(10);

        return view('audit.index', compact('audits', 'users'));
    }
}
