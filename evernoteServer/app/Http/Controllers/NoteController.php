<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::all();
        return response()->json($notes);
    }

    public function store(Request $request)
    {
        $note = Note::create($request->all());
        return response()->json($note, 201);
    }

    public function show($id)
    {
        $note = Note::findOrFail($id);
        return response()->json($note);
    }

    public function update(Request $request, $id)
    {
        $note = Note::findOrFail($id);
        $note->update($request->all());
        return response()->json($note);
    }

    public function destroy($id)
    {
        Note::destroy($id);
        return response()->json(null, 204);
    }
}
