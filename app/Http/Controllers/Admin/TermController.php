<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function index()
    {
        $terms = Term::withCount(['officers', 'events'])
            ->orderByDesc('start_date')
            ->get();

        return view('admin.terms.index', compact('terms'));
    }

    public function create()
    {
        return view('admin.terms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after:start_date'],
            'is_active'  => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if ($validated['is_active']) {
            Term::where('is_active', true)->update(['is_active' => false]);
        }

        $term = Term::create($validated);

        return redirect()->route('admin.terms.show', $term)->with('success', 'Term created.');
    }

    public function show(Term $term)
    {
        $term->load(['officers.user', 'events']);

        return view('admin.terms.show', compact('term'));
    }

    public function edit(Term $term)
    {
        return view('admin.terms.edit', compact('term'));
    }

    public function update(Request $request, Term $term)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after:start_date'],
            'is_active'  => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if ($validated['is_active']) {
            Term::where('is_active', true)->where('id', '!=', $term->id)->update(['is_active' => false]);
        }

        $term->update($validated);

        return redirect()->route('admin.terms.show', $term)->with('success', 'Term updated.');
    }

    public function destroy(Term $term)
    {
        if ($term->events()->exists()) {
            return back()->with('error', 'Cannot delete a term that has events linked to it.');
        }

        $term->delete();

        return redirect()->route('admin.terms.index')->with('success', 'Term deleted.');
    }

    public function activate(Term $term)
    {
        Term::where('is_active', true)->update(['is_active' => false]);
        $term->update(['is_active' => true]);

        return back()->with('success', '"' . $term->name . '" is now the active term.');
    }

    public function public()
    {
        $terms = Term::with(['officers.user'])
            ->orderByDesc('start_date')
            ->get();

        return view('administration.index', compact('terms'));
    }
}
