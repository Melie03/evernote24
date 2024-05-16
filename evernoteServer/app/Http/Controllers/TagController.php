<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::all();
        return response()->json($tags);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required|string|max:50'
            ]);

            if (Tag::where('name', $request->name)->exists()) {
                return response()->json("Tag already exists", 400);
            }

            $tag = Tag::create($request->all());
            DB::commit();
            return response()->json($tag, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("saving tag failed: " . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        $tag = Tag::findOrFail($id);
        return response()->json($tag);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required|string|max:50'
            ]);

            if (Tag::where('name', $request->name)->exists()) {
                return response()->json("Tag already exists", 400);
            }

            $tag = Tag::findOrFail($id);
            $tag->update($request->all());
            DB::commit();
            return response()->json($tag, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("updating tag failed: " . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $tag = Tag::findOrFail($id);

            // Entferne den Tag aus allen Notizen
            $tag->notes()->detach();
            $tag->delete();

            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("deleting tag failed: " . $e->getMessage(), 500);
        }
    }
}
