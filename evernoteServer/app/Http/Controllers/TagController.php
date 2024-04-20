<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        // Abrufen aller Tags
        $tags = Tag::all();
        return response()->json($tags);
    }

    public function store(Request $request)
    {
        // Validierung der Eingabe
        $validated = $request->validate(['name' => 'required|string|max:255']);

        // Erstellen eines neuen Tags
        $tag = Tag::create($validated);
        return response()->json($tag, 201);
    }

    public function show($id)
    {
        // Ein spezifischer Tag nach ID
        $tag = Tag::findOrFail($id);
        return response()->json($tag);
    }

    public function update(Request $request, $id)
    {
        // Validierung der Eingabe
        $validated = $request->validate(['name' => 'required|string|max:255']);

        // Update des Tags
        $tag = Tag::findOrFail($id);
        $tag->update($validated);
        return response()->json($tag);
    }

    public function destroy($id)
    {
        // Löschen eines Tags
        Tag::destroy($id);
        return response()->json(null, 204);
    }
}
