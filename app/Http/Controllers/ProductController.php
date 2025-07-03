<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display the product inventory page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all products ordered by submitted_at
        $products = $this->getProductsFromJson();
        
        // Calculate total sum
        $totalSum = 0;
        foreach ($products as $product) {
            $totalSum += $product['total_value'];
        }
        
        return view('products.index', compact('products', 'totalSum'));
    }

    /**
     * Store a newly created product in the JSON storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Prepare product data
        $productData = [
            'id' => uniqid(),
            'name' => $request->name,
            'quantity' => (int)$request->quantity,
            'price' => (float)$request->price,
            'submitted_at' => Carbon::now()->toDateTimeString(),
            'total_value' => (float)$request->quantity * (float)$request->price
        ];

        // Get existing products
        $products = $this->getProductsFromJson();
        
        // Add new product
        $products[] = $productData;
        
        // Save products back to JSON file
        $this->saveProductsToJson($products);

        // Calculate total sum
        $totalSum = 0;
        foreach ($products as $product) {
            $totalSum += $product['total_value'];
        }

        return response()->json([
            'success' => true, 
            'product' => $productData,
            'products' => $products,
            'totalSum' => $totalSum,
            'message' => 'Product added successfully!'
        ]);
    }

    /**
     * Update the specified product in the JSON storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Get existing products
        $products = $this->getProductsFromJson();
        
        // Find and update the product
        $updated = false;
        foreach ($products as $key => $product) {
            if ($product['id'] === $id) {
                $products[$key]['name'] = $request->name;
                $products[$key]['quantity'] = (int)$request->quantity;
                $products[$key]['price'] = (float)$request->price;
                $products[$key]['total_value'] = (float)$request->quantity * (float)$request->price;
                $updated = true;
                break;
            }
        }

        if (!$updated) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Save products back to JSON file
        $this->saveProductsToJson($products);

        // Calculate total sum
        $totalSum = 0;
        foreach ($products as $product) {
            $totalSum += $product['total_value'];
        }

        return response()->json([
            'success' => true,
            'products' => $products,
            'totalSum' => $totalSum,
            'message' => 'Product updated successfully!'
        ]);
    }

    /**
     * Get products from JSON file
     *
     * @return array
     */
    private function getProductsFromJson()
    {
        $path = storage_path('app/products.json');
        
        if (!file_exists($path)) {
            return [];
        }
        
        $jsonContent = file_get_contents($path);
        $products = json_decode($jsonContent, true) ?: [];
        
        // Sort products by submitted_at (newest first)
        usort($products, function($a, $b) {
            return strtotime($b['submitted_at']) - strtotime($a['submitted_at']);
        });
        
        return $products;
    }

    /**
     * Save products to JSON file
     *
     * @param  array  $products
     * @return bool
     */
    private function saveProductsToJson($products)
    {
        $path = storage_path('app/products.json');
        
        // Create directory if it doesn't exist
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        $jsonContent = json_encode($products, JSON_PRETTY_PRINT);
        
        return file_put_contents($path, $jsonContent);
    }
}
