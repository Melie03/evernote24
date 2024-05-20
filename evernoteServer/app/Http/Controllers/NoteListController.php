<?php

namespace App\Http\Controllers;

use App\Models\NoteList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoteListController extends Controller
{
    public function index()
    {
        $noteLists = NoteList::all();
        return response()->json($noteLists);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to store a new NoteList in the database.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $list = NoteList::create($request->all());
            $request->validate([
                'name' => 'required|string|max:50'
            ]);
            DB::commit();
            return response()->json($list, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("saving list failed: " . $e->getMessage(), 500);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to update a NoteList in the database.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $noteList = NoteList::findOrFail($id);
            $request->validate([
                'name' => 'required|string|max:50'
            ]);

            $noteList->update($request->all());
            $noteList->save();
            DB::commit();
            $noteList = NoteList::findOrFail($id);
            return response()->json($noteList, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("updating list failed: " . $e->getMessage(), 500);
        }

    }

    /**
     * @param Request $request
     * @param $listId
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to share a NoteList with a user.
     */
    public function shareList(Request $request, $listId, $userId)
    {
        DB::beginTransaction();
        try {
            $noteList = NoteList::findOrFail($listId);
            $noteList->shared()->attach($userId);
            DB::commit();
            return response()->json($noteList, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("sharing list failed: " . $e->getMessage(), 500);
        }
    }

    /**
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to get all the NoteLists shared with a user.
     */
    public function getAllPendingLists($userId)
    {
        $noteLists = User::findOrFail($userId)->shared()->wherePivot('status', false)->get();
        return response()->json($noteLists);
    }

    /**
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to get all the NoteLists shared with a user.
     */
    public function acceptSharedList($listId, $userId)
    {
        DB::beginTransaction();
        try {
            $noteList = NoteList::findOrFail($listId);
            $noteList->shared()->updateExistingPivot($userId, ['status' => true]);
            DB::commit();
            return response()->json($noteList, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("status list failed: " . $e->getMessage(), 500);
        }
    }

    /**
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to get all the NoteLists shared with a user.
     */
    public function declineSharedList($listId, $userId)
    {
        DB::beginTransaction();
        try {
            $noteList = NoteList::findOrFail($listId);
            $noteList->shared()->detach($userId);
            DB::commit();
            return response()->json($noteList, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("declining list failed: " . $e->getMessage(), 500);
        }
    }

    /**
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to get all the NoteLists shared with a user.
     */
    public function getUsersWhoShareLists($listId)
    {
        $noteList = NoteList::findOrFail($listId);
        $users = $noteList->sharedUsers()->wherePivot('status', true)->get();
        $users->push(User::findOrFail($noteList->user_id));
        return response()->json($users);
    }

    /**
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to get all the NoteLists shared with a user.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $noteList = NoteList::where('id', $id)->first();
            if ($noteList == null) {
                DB::commit();
                return response()->json("NoteList not found", 404);
            }
            $noteList->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("deleting list failed: " . $e->getMessage(), 500);
        }
    }

    /**
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to get all the NoteLists shared with a user.
     */
    public function getById($id)
    {
        $noteList = NoteList::findOrFail($id);
        return response()->json($noteList);

    }

    /**
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to get all the NoteLists shared with a user.
     */
    public function getNotesByListId($id)
    {
        $noteList = NoteList::findOrFail($id);
        $notes = $noteList->notes;
        return response()->json($notes);
    }

    /**
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     *
     * This method is used to get all the NoteLists shared with a user.
     */
    public function getByUserId($userId)
    {
        $noteLists = NoteList::where('user_id', $userId)->get();
        $noteLists = $noteLists->merge(User::findOrFail($userId)->shared()->wherePivot('status', true)->get());
        return response()->json($noteLists);
    }
}
