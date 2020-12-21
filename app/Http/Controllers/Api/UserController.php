<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{

    /**
     * Logged in user bookmarks
     * @return JsonResponse
     */
    public function myBookmarks(): JsonResponse
    {

        $bookmarks = User::with('bookmarks')->where('id', auth()->user()->id)->get();

        if(count($bookmarks) === 0) {

            return response()->json([

                "success" => false,
                "data" => "You don't have any image bookmarked."
            ], 404);

        }

        return response()->json([
            "success" => true,
            "data" => $bookmarks
        ]);

    }


    /**
     * Bookmark
     * @param Request $request
     * @return JsonResponse
     */
    public function bookmark(Request $request) {

        $validator = Validator::make($request->all(), [

            'image_id' => 'required|integer',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::find(auth()->user()->id);

        $user->bookmarks()->attach($request->input('image_id'));

        return response()->json([
            "success" => true,
            "data" => "Your image was succesfully bookmarked."
        ], 201);
    }
}
