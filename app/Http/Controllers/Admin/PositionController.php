<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Committee;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $positions  = Position::with('committee')->withCount('userPositions')->orderBy('name')->get();
        $committees = Committee::orderBy('name')->get();
        return view('admin.positions.index', compact('positions', 'committees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'committee_id' => ['nullable', 'exists:committees,id'],
            'description'  => ['nullable', 'string', 'max:1000'],
        ]);

        Position::create($request->only('name', 'committee_id', 'description'));

        return back()->with('success', 'Position created.');
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'committee_id' => ['nullable', 'exists:committees,id'],
            'description'  => ['nullable', 'string', 'max:1000'],
        ]);

        $position->update($request->only('name', 'committee_id', 'description'));

        return back()->with('success', 'Position updated.');
    }

    public function destroy(Position $position)
    {
        if ($position->userPositions()->exists()) {
            return back()->with('error', 'Cannot delete a position that has assigned members.');
        }

        $position->delete();

        return back()->with('success', 'Position deleted.');
    }
}
