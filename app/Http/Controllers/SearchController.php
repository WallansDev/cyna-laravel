<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Service;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = trim($request->get('q', ''));

        if ($query === '') {
            return response()->json([]);
        }

        $categories = Category::where('name', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(fn($cat) => [
                'type' => 'Catégorie',
                'name' => $cat->name,
                'url'  => route('categories.show', $cat->id),
            ]);

        $services = Service::where('name', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(fn($srv) => [
                'type' => 'Service',
                'name' => $srv->name,
                'url'  => route('services.show', $srv->id),
            ]);

        return response()->json($categories->merge($services)->take(10));
    }
}
