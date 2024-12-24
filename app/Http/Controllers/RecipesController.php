<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Recipe;

class RecipesController extends Controller
{

    public function index()
    {
        $recipes = Recipe::all();
        $response = [
            'recipes' => $recipes->map(function ($recipe) {
                return [
                    'id' => $recipe->id,
                    'title' => $recipe->title,
                    'making_time' => $recipe->making_time,
                    'serves' => $recipe->serves,
                    'ingredients' => $recipe->ingredients,
                    'cost' => $recipe->cost
                ];
            })->toArray()
        ];
    
        return response()->json($response, 200); 
    }

    public function detail($id)
    {
        $recipe = Recipe::find($id);

        if (!$recipe) {
            return response()->json([
                'message' => 'Recipe not found',
            ], 404);
        }

        $response = [
            'message' => 'Recipe details by id',
            'recipe' => [
                [
                    'id' => $recipe->id,
                    'title' => $recipe->title,
                    'making_time' => $recipe->making_time,
                    'serves' => $recipe->serves,
                    'ingredients' => $recipe->ingredients,
                    'cost' => $recipe->cost
                ]
            ]
        ];

        return response()->json($response, 200);
    }

    public function update(Request $request, $id)
    {
        $recipe = Recipe::find($id);

        if (!$recipe) {
            return response()->json([
                'message' => 'Recipe not found',
            ], 404);
        }

        $recipe->update($request->only([
            'title', 'making_time', 'serves', 'ingredients', 'cost'
        ]));

        $response = [
            'message' => 'Recipe successfully updated!',
            'recipe' => [
                [
                    'title' => $recipe->title,
                    'making_time' => $recipe->making_time,
                    'serves' => $recipe->serves,
                    'ingredients' => $recipe->ingredients,
                    'cost' => $recipe->cost
                ]                
            ]
        ];

        return response()->json($response, 200);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'making_time' => 'required|string|max:100',
            'serves' => 'required|string|max:100',
            'ingredients' => 'required|string|max:300',
            'cost' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Recipe creation failed!',
                'required' => 'title, making_time, serves, ingredients, cost'
            ], 422); // 422 Unprocessable Entity
        }
    
        try {
            $recipe = new Recipe($validator->validated());
            $recipe->save();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Recipe creation failed!'
            ], 500); // 500 Internal Server Error
        }

        $response = [
            'message' => 'Recipe successfully created!',
            'recipe' => [
                [
                    'id' => $recipe->id,
                    'title' => $recipe->title,
                    'making_time' => $recipe->making_time,
                    'serves' => $recipe->serves,
                    'ingredients' => $recipe->ingredients,
                    'cost' => $recipe->cost,
                    'created_at' => $recipe->created_at,
                    'updated_at' => $recipe->updated_at
                ]
            ]
        ];

        return response()->json($response, 200);
    }

    public function delete($id)
    {
        $recipe = Recipe::find($id);

        if (!$recipe) {
            return response()->json([
                'message' => 'No Recipe found'
            ], 404);
        }

        try {
            $recipe->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to remove the recipe'
            ], 500);
        }

        return response()->json([
            'message' => 'Recipe successfully removed!'
        ], 200);
    }
}
