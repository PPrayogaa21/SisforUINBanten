<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleSelectionController extends Controller
{
    public function show()
    {
        return view('auth.select-role');
    }

    public function select(Request $request)
    {
        $request->validate([
            'role' => 'required|in:peserta,narasumber',
        ]);

        $role = $request->input('role');
        session(['active_role' => $role]);

        return redirect()->route("{$role}.dashboard")
            ->with('success', 'Role aktif: ' . ucfirst($role));
    }
}
