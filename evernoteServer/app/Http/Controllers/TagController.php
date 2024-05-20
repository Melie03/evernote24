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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to store a new Tag in the database.
     */
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

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to get a Tag by its id.
     */
    public function show($id)
    {
        $tag = Tag::findOrFail($id);
        return response()->json($tag);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to update a Tag in the database.
     */
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

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to delete a Tag from the database.
     */
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
