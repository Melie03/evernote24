<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\NoteList;
use App\Models\Tag;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::all();
        return response()->json($todos);
    }

    public function store(Request $request)
    {
        $request = $this->parseRequest($request);
        DB::beginTransaction();
        try {
            $todo = Todo::create($request->all());
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'due_date' => 'nullable|date',
                'note_id' => 'nullable|exists:notes,id'
            ]);
            if (isset($request['tags']) && is_array($request['tags'])) {
                foreach ($request['tags'] as $t) {
                    $tag = Tag::firstOrCreate(['name' => $t['name']]);
                    $todo->tags()->attach($tag->id);
                }
            }
            DB::commit();
            return response()->json($todo, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("saving ToDo failed: " . $e->getMessage(), 500);
        }
    }
    public function getTagsByTodoId($id)
    {
        $todo = Todo::findOrFail($id);
        $tags = $todo->tags;
        return response()->json($tags);
    }
    public function getTodoWithoutNote()
    {
        $todos = Todo::where('note_id', null)->get();
        return response()->json($todos);

    }

    public function show($id)
    {
        $todo = Todo::findOrFail($id);
        return response()->json($todo);
    }

    public function update(Request $request, $id)
    {
        $request = $this->parseRequest($request);
        DB::beginTransaction();
        try {
            $todo = Todo::findOrFail($id);
            $request->validate([
                'title' => 'required|string|max:30',
                'description' => 'required|string|min:10|max:255',
                'due_date' => 'nullable|date',
                'note_id' => 'nullable|exists:notes,id',
                'completed' => 'required|boolean'
            ]);

            $todo->update($request->all());
            // Tags aktualisieren
            if (isset($request['tags']) && is_array($request['tags'])) {
                $existingTagIds = $todo->tags->pluck('id')->toArray();
                $newTagIds = [];

                foreach ($request['tags'] as $t) {
                    $tag = Tag::firstOrCreate(['name' => $t['name']]);
                    $newTagIds[] = $tag->id;
                }

                // Entferne nicht mehr vorhandene Tags
                $tagsToDetach = array_diff($existingTagIds, $newTagIds);
                if (!empty($tagsToDetach)) {
                    $todo->tags()->detach($tagsToDetach);
                }

                // Füge neue Tags hinzu
                $tagsToAttach = array_diff($newTagIds, $existingTagIds);
                if (!empty($tagsToAttach)) {
                    $todo->tags()->attach($tagsToAttach);
                }
            }
            $todo->save();
            DB::commit();
            return response()->json($todo, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("updating todo failed: " . $e->getMessage(), 500);
        }

    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $todo = Todo::where('id', $id)->first();
            if ($todo == null) {
                DB::commit();
                return response()->json("ToDo not found", 404);
            }
            $todo->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("deleting ToDo failed: " . $e->getMessage(), 500);
        }
    }
    private function parseRequest (Request $request) : Request {
        $date = new \DateTime($request->due_date);
        $request['due_date'] = $date->format('Y-m-d H:i:s');
        return $request;
    }
}
