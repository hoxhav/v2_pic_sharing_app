<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class TagController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {

        $tags = Tag::all();

        return response()->json([
            "success" => true,
            "data" => $tags
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function listImageTags(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [

            'image_id' => 'required|integer',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $tags = Tag::where('image_id', $request->input('image_id'))->get();

        return response()->json([
            "success" => true,
            "data" => $tags
        ]);

    }

    /**
     * Search by category
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [

            'name' => 'required|string|max:255',
            'image_id' => 'required|integer',

        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors(), 422);

        }

        $tag = new Tag();

        $tag->name = $request->input('name');

        $tag->image_id = $request->input('image_id');

        $tag->save();

        return response()->json([
            "success" => true,
            "data" => $tag
        ], 201);
    }
}
