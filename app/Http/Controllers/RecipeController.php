<?php

namespace App\Http\Controllers;

use App\Models\Product\Variant\ProductVariant;
use App\Models\Ingredient;
use App\Models\IngredientsItem;
use App\Models\IngredientSale;
use App\Models\IngredientSaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecipeController extends Controller
{
    /**
     * Display the recipe creation form with service items and ingredients.
     */
    public function index()
    {
        $serviceItems = ProductVariant::where('service_item', 1)->with('rates')->get();
        $ingredients = ProductVariant::where('raw_material', 1)->with('rates')->get();
        $recipes = Ingredient::with('productVariant')->get();
        return view('adminPanel.product.recipe.create_recipe', compact('serviceItems', 'ingredients', 'recipes'));
    }

    public function details($id)
    {
        $recipe = Ingredient::with(['productVariant', 'items.productVariant'])->findOrFail($id);
        return view('adminPanel.product.recipe.recipe_details', compact('recipe'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_item_id' => 'required|exists:product_variants,id',
            'ingredient_id' => 'required|array',
            'quantity' => 'required|array',
            'cost_price' => 'required|array',
            'update_cost' => 'nullable|boolean',
        ]);

        $existingRecipe = Ingredient::where('product_variant_id', $request->service_item_id)->first();
        if ($existingRecipe) {
            return redirect()->back()->with('error', 'A recipe already exists for this service item.');
        }

        $serviceItem = ProductVariant::findOrFail($request->service_item_id);
        $serviceCost = $serviceItem->rates->cost_price ?? 0;

        $totalCost = 0;
        foreach ($request->ingredient_id as $index => $ingredientId) {
            $qty = $request->quantity[$index] ?? 0;
            $cost = $request->cost_price[$index] ?? 0;
            $totalCost += $qty * $cost;
        }

        if ($totalCost == $serviceCost) {
            $this->saveRecipe($request, $totalCost);
            return redirect()->back()->with('success', 'Recipe saved successfully.');
        } else {
            if ($request->has('update_cost') && $request->update_cost) {
                $serviceItem->rates()->update(['cost_price' => $totalCost]);
                $this->saveRecipe($request, $totalCost);
                return redirect()->back()->with('success', 'Recipe saved and cost price updated successfully.');
            } else {
                $this->saveRecipe($request, $totalCost);
                return redirect()->back()->with('warning', 'Total cost does not match service item cost. Recipe saved without updating cost price.');
            }
        }
    }

    private function saveRecipe(Request $request, $totalCost)
    {
        $recipe = Ingredient::create([
            'product_variant_id' => $request->service_item_id,
            'ingredient_total_cost' => $totalCost,
        ]);

        foreach ($request->ingredient_id as $index => $ingredientId) {
            IngredientsItem::create([
                'ingredients_id' => $recipe->id,
                'product_variant_id' => $ingredientId,
                'qty' => $request->quantity[$index] ?? 0,
            ]);
        }
    }

    public function getEditData($id)
    {
        $recipe = Ingredient::with(['productVariant.rates', 'items.productVariant.rates'])->find($id);
        if (!$recipe) {
            return response()->json(['error' => 'Recipe not found'], 404);
        }

        $serviceItem = $recipe->productVariant;
        $ingredients = $recipe->items->map(function ($item) {
            return [
                'id' => $item->productVariant->id,
                'name' => $item->productVariant->product_variant_name,
                'cost' => $item->productVariant->rates->cost_price ?? 0,
                'quantity' => $item->qty,
            ];
        });

        return response()->json(['serviceItem' => $serviceItem, 'ingredients' => $ingredients]);
    }

    public function update(Request $request, $id)
    {
        

        $request->validate([
            'service_item_id' => 'required|exists:product_variants,id',
            'ingredient_id' => 'required|array',
            'quantity' => 'required|array',
            'cost_price' => 'required|array',
            'update_cost' => 'sometimes|boolean',
        ]);

        $recipe = Ingredient::find($id);
        if (!$recipe) {
            return redirect()->back()->with('error', 'Recipe not found');
        }

        if ($recipe->product_variant_id != $request->service_item_id) {
            return redirect()->back()->with('error', 'Cannot change the service item for an existing recipe.');
        }

        // Delete existing items
        $recipe->items()->delete();

        // Calculate total cost and save new items
        $totalCost = 0;
        foreach ($request->ingredient_id as $index => $ingredientId) {
            $qty = $request->quantity[$index] ?? 0;
            $cost = $request->cost_price[$index] ?? 0;
            $totalCost += $qty * $cost;
            IngredientsItem::create([
                'ingredients_id' => $recipe->id,
                'product_variant_id' => $ingredientId,
                'qty' => $qty,
            ]);
        }

        $recipe->ingredient_total_cost = $totalCost;
        $recipe->save();

        $serviceItem = ProductVariant::find($request->service_item_id);
        $serviceCost = $serviceItem->rates->cost_price ?? 0;
        if ($totalCost != $serviceCost) {
            if ($request->has('update_cost') && $request->update_cost) {
                $serviceItem->rates()->update(['cost_price' => $totalCost]);
                return redirect()->route('recipes.index')->with('success', 'Recipe updated and cost price updated successfully.');
            } else {
                return redirect()->route('recipes.index')->with('warning', 'Total cost does not match service item cost. Recipe updated without updating cost price.');
            }
        } else {
            return redirect()->route('recipes.index')->with('success', 'Recipe updated successfully.');
        }
    }
}