<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends Controller
{

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {

        $categories = Category::all();

        if(count($categories) === 0) {

            return response()->json([

                "success" => false,
                "data" => "There is no categories available in our app."
            ], 404);

        }

        return response()->json([
            "success" => true,
            "data" => $categories
        ]);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request) {

        $validator = Validator::make($request->all(), [

            'name' => 'required|string|max:255',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category = new Category();

        $category->name = $request->input('name');

        $category->save();

        return response()->json([
            "success" => true,
            "data" => $category
        ], 201);
    }
}
