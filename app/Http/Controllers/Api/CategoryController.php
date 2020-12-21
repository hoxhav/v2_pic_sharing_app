<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\category\CreateCategoryRequest;
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
     * @param CreateCategoryRequest $request
     * @return JsonResponse
     */
    public function create(CreateCategoryRequest $request) {

        $validated = $request->validated();

        $category = new Category();

        $category->name = $validated['name'];

        $category->save();

        return response()->json([
            "success" => true,
            "data" => $category
        ], 201);
    }
}
