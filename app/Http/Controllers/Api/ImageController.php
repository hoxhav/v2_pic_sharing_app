<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;


class ImageController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {

        $images = Image::with('tags', 'category')->get();

        if(count($images) === 0) {

            return response()->json([

                "success" => false,
                "data" => "There is no images available in our app."
            ], 404);

        }

        return response()->json([
            "success" => true,
            "data" => $images
        ]);

    }

    /**
     * @return JsonResponse
     */
    public function myImages() {

        $images = Image::with('tags', 'category')->where('user_id', auth()->user()->id)->get();

        if(count($images) === 0) {

            return response()->json([

                "success" => false,
                "data" => "You don't have any image."
            ], 404);

        }

        return response()->json([
            "success" => true,
            "data" => $images
        ]);

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function download(Request $request) {

        return Storage::download($request->input('photo'));

    }

    /**
     * Third tasks, uploading picture
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request) {

        $validator = Validator::make($request->all(), [

            'name' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'category_id' => 'required|integer',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $path = $request->file('photo')->store('images');

        $image = new Image();

        $image->name = $request->input('name');

        $image->path_name = $path;

        $image->user_id = auth()->user()->id;

        $image->category_id = $request->input('category_id');

        $image->save();


        return response()->json([
            "success" => true,
            "data" => $image
        ], 201);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request) {

        $validator = Validator::make($request->all(), [

            'name_phrase' => 'required|string|max:255',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //search by name image
        $images = Image::with('category','tags')
            ->where('name', 'like', '%'.$request->input('name_phrase').'%')
            ->get();

        //no result, search by tag
        if(count($images) === 0) {

            $images = Image::with('category', 'tags')
                ->whereHas('tags',function ($query) use ($request) {

                    $query->where('name', 'like', '%'.$request->input('name_phrase').'%');

                })
                ->get();

        }

        return response()->json([
            "success" => true,
            "data" => $images
        ]);
    }

    /**
     * Search by category
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filterByCategory(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [

            'category_id' => 'required|integer',

        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors(), 422);

        }

        $images = Image::with('tags', 'category')->where('category_id', $request->input('category_id'))->get();

        if(count($images) === 0) {

            return  response()->json([

                "success" => false,
                "data" => "There is no images with given category."
            ], 404);

        }

        return response()->json([

            "success" => true,
            "data" => $images

        ]);
    }


}
