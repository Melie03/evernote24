<?php

namespace App\Http\Controllers;

use App\Models\NoteList;
use Illuminate\Http\Request;

class NoteListController extends Controller
{
    public function index()
    {
        $noteLists = NoteList::all();
        return response()->json($noteLists);
    }

    public function store(Request $request)
    {
        $noteList = NoteList::create($request->all());
        return response()->json($noteList, 201);
    }

    public function show($id)
    {
        $noteList = NoteList::findOrFail($id);
        return response()->json($noteList);
    }

    public function update(Request $request, $id)
    {
        $noteList = NoteList::findOrFail($id);
        $noteList->update($request->all());
        return response()->json($noteList);
    }

    public function destroy($id)
    {
        NoteList::destroy($id);
        return response()->json(null, 204);
    }
}
