<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index()
     {
        $categories = Category::with('services')->orderBy('position')->get();
        $category_first = $categories->first();
        $category_last = $categories->last();
        return view('category.index', compact('categories', 'category_first', 'category_last'));
     }

    public function viewAdmin()
    {
        $categories = Category::orderBy('position')->paginate(3);
        $category_first = Category::orderBy('position')->first();
        $category_last = Category::orderByDesc('position')->first();

        return view('category.viewAdmin', compact('categories', 'category_first', 'category_last'));
    }

    public function orderIndex()
    {
        $categories = Category::orderBy('position')->get();
        $category_first = Category::orderBy('position')->first();
        $category_last = Category::orderByDesc('position')->first();

        return view('category.order', compact('categories', 'category_first', 'category_last'));
    }

    /**
     * Move up the current Category item
     */
    public function moveUp($id)
    {
        $current = Category::findOrFail($id);
        $above = Category::where('position', '<', $current->position)->orderByDesc('position')->first();

        if ($above) {
            [$current->position, $above->position] = [$above->position, $current->position];
            $current->save();
            $above->save();
        }

        return redirect()->back();
    }

    /**
     * Move down the current Category item
     */
    public function moveDown($id)
    {
        $current = Category::findOrFail($id);
        $below = Category::where('position', '>', $current->position)->orderBy('position')->first();

        if ($below) {
            [$current->position, $below->position] = [$below->position, $current->position];
            $current->save();
            $below->save();
        }

        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $category = new Category();
        $allServices = Service::all();

        return view('category.create', compact('category', 'allServices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $lastPosition = Category::max('position') ?? 0;

        $path = $request->file('image_path')->store('categories', 'public');

        $category = Category::create([
            'name' => $request->name,
            'image_path' => basename($path),
            'description' => $request->description,
            'position' => $lastPosition + 1,
        ]);

        $category->services()->attach($request->input('services', []));

        return Redirect::route('categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $category = Category::findOrFail($id);

        return view('category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $category = Category::with('services')->findOrFail($id);
        $allServices = Service::all();

        return view('category.edit', compact('category', 'allServices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image_path')) {
            if ($category->image_path) {
                $oldImagePath = public_path('storage/categories/' . $category->image_path);

                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $path = $request->file('image_path')->store('categories', 'public');

            $data['image_path'] = basename($path);
        } else {
            $data['image_path'] = $category->image_path;
        }

        $category->update($data);

        $category->services()->sync($data['services'] ?? []);

        return Redirect::route('categories.index')->with('success', 'Category updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        $category = Category::find($id);
        $ImagePath = public_path('storage/categories/' . $category->image_path);

        unlink($ImagePath);

        $category->delete();

        $this->reorderPositions();

        return Redirect::route('categories.index')->with('success', 'Category deleted successfully');
    }

    private function reorderPositions()
    {
        $categories = Category::orderBy('position')->get();

        foreach ($categories as $index => $category) {
            if ($category->position !== $index + 1) {
                $category->position = $index + 1;
                $category->save();
            }
        }
    }
}
