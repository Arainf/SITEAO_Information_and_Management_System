<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Term;
use App\Models\TermOfficer;
use App\Models\User;
use Illuminate\Http\Request;

class TermOfficerController extends Controller
{
    public function store(Request $request, Term $term)
    {
        $request->validate([
            'user_id'  => ['required', 'exists:users,id'],
            'position' => ['required', 'string', 'max:255'],
        ]);

        $already = $term->officers()->where('user_id', $request->user_id)->exists();
        if ($already) {
            return back()->with('error', 'This user already has a position in this term.');
        }

        $term->officers()->create([
            'user_id'  => $request->user_id,
            'position' => $request->position,
        ]);

        return back()->with('success', 'Officer added.');
    }

    public function destroy(Term $term, TermOfficer $officer)
    {
        $officer->delete();

        return back()->with('success', 'Officer removed.');
    }
}
