<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\image\FilterByCategoryImageRequest;
use App\Http\Requests\image\ImageFormRequest;
use App\Http\Requests\image\SearchImageRequest;
use App\Http\Requests\image\UploadImageRequest;
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
     * @param UploadImageRequest $request
     * @return JsonResponse
     */
    public function upload(UploadImageRequest $request) {

        $validated = $request->validated();

        $path = $validated['photo']->store('images');

        $image = new Image();

        $image->name = $validated['name'];

        $image->path_name = $path;

        $image->user_id = auth()->user()->id;

        $image->category_id = $validated['category_id'];

        $image->save();


        return response()->json([
            "success" => true,
            "data" => $image
        ], 201);

    }

    /**
     * @param SearchImageRequest $request
     * @return JsonResponse
     */
    public function search(SearchImageRequest $request) {

        $validated = $request->validated();

        //search by name image
        $images = Image::with('category','tags')
            ->where('name', 'like', '%'.$validated['name_phrase'].'%')
            ->get();

        //no result, search by tag
        if(count($images) === 0) {

            $images = Image::with('category', 'tags')
                ->whereHas('tags',function ($query) use ($validated) {

                    $query->where('name', 'like', '%'.$validated['name_phrase'].'%');

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
     * @param FilterByCategoryImageRequest $request
     * @return JsonResponse
     */
    public function filterByCategory(FilterByCategoryImageRequest $request): JsonResponse
    {

        $validated = $request->validated();


        $images = Image::with('tags', 'category')->where('category_id',$validated['category_id'])->get();

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
