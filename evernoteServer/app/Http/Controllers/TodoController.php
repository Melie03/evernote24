<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        // Abrufen aller Todos
        $todos = Todo::all();
        return response()->json($todos);
    }

    public function store(Request $request)
    {
        // Validierung der Eingabe
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'nullable|date',
            'note_id' => 'nullable|exists:notes,id',
            'assigned_user_id' => 'nullable|exists:users,id'
        ]);

        // Erstellen eines neuen Todos
        $todo = Todo::create($validated);
        return response()->json($todo, 201);
    }

    public function show($id)
    {
        // Ein spezifisches Todo nach ID
        $todo = Todo::findOrFail($id);
        return response()->json($todo);
    }

    public function update(Request $request, $id)
    {
        // Validierung der Eingabe
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'nullable|date',
            'note_id' => 'nullable|exists:notes,id',
            'assigned_user_id' => 'nullable|exists:users,id'
        ]);

        // Update des Todos
        $todo = Todo::findOrFail($id);
        $todo->update($validated);
        return response()->json($todo);
    }

    public function destroy($id)
    {
        // Löschen eines Todos
        Todo::destroy($id);
        return response()->json(null, 204);
    }
}
