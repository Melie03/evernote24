<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::all();
        return response()->json($notes);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to store a new Note in the database.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'title' => 'required|string|max:50',
                'description' => 'required|string|max:400'
            ]);

            $note = Note::create($request->all());

            if (isset($request['tags']) && is_array($request['tags'])) {
                foreach ($request['tags'] as $t) {
                    $tag = Tag::firstOrCreate(['name' => $t['name']]);
                    $note->tags()->attach($tag->id);
                }
            }

            DB::commit();
            return response()->json($note, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("saving note failed: " . $e->getMessage(), 500);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to get a Note by its id.
     */
    public function show($id)
    {
        $note = Note::findOrFail($id);
        $note->tags = $note->tags;
        return response()->json($note);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to get all tags of a Note by its id.
     */
    public function getTagsByNoteId($id)
    {
        $note = Note::findOrFail($id);
        $tags = $note->tags;
        return response()->json($tags);
    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to get all todos of a Note by its id.
     */
    public function getTodoByNoteId($id)
    {
        $note = Note::findOrFail($id);
        $todos = $note->todos;
        return response()->json($todos);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to update a Note in the database.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $note = Note::findOrFail($id);
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'note_list_id' => 'required|integer'
            ]);

            $note->update($request->all());

            // Tags aktualisieren
            if (isset($request['tags']) && is_array($request['tags'])) {
                $existingTagIds = $note->tags->pluck('id')->toArray();
                $newTagIds = [];

                foreach ($request['tags'] as $t) {
                    $tag = Tag::firstOrCreate(['name' => $t['name']]);
                    $newTagIds[] = $tag->id;
                }

                // Entferne nicht mehr vorhandene Tags
                $tagsToDetach = array_diff($existingTagIds, $newTagIds);
                if (!empty($tagsToDetach)) {
                    $note->tags()->detach($tagsToDetach);
                }

                // Füge neue Tags hinzu
                $tagsToAttach = array_diff($newTagIds, $existingTagIds);
                if (!empty($tagsToAttach)) {
                    $note->tags()->attach($tagsToAttach);
                }
            }

            DB::commit();
            return response()->json($note, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("updating list failed: " . $e->getMessage(), 500);
        }
    }

/**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to delete a Note from the database.
     */
    public function destroy($id)
    {
        $note = Note::findOrFail($id);
        $note->delete();
        return response()->json(null, 204);
    }
}
