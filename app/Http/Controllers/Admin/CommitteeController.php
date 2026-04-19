<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Committee;
use Illuminate\Http\Request;

class CommitteeController extends Controller
{
    public function index()
    {
        $committees = Committee::withCount('positions')->orderBy('name')->get();
        return view('admin.committees.index', compact('committees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:committees,name'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        Committee::create($request->only('name', 'description'));

        return back()->with('success', 'Committee created.');
    }

    public function update(Request $request, Committee $committee)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:committees,name,' . $committee->id],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $committee->update($request->only('name', 'description'));

        return back()->with('success', 'Committee updated.');
    }

    public function destroy(Committee $committee)
    {
        if ($committee->positions()->exists()) {
            return back()->with('error', 'Cannot delete a committee that has positions. Remove positions first.');
        }

        $committee->delete();

        return back()->with('success', 'Committee deleted.');
    }
}
