<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Requests\Service\StoreServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
    */

    public function index()
    {
        $services = Service::orderBy('position')->paginate(10);

        return view('service.index', compact('services'));
    }


    public function viewAdmin()
    {
        $services = Service::orderBy('position')->paginate(3);
        $service_first = Service::orderBy('position')->first();
        $service_last = Service::orderByDesc('position')->first();
        $top_position_first = Service::orderBy('top_position')->first();
        $top_position_last = Service::orderByDesc('top_position')->first();

        return view('service.viewAdmin', compact('services', 'service_first', 'service_last', 'top_position_first', 'top_position_last'));
    }

    public function topProducts()
    {
        $services = Service::where('top_position', '!=', 0)->orderBy('top_position')->get();
        $top_position_first = Service::where('top_position', '!=', 0)->orderBy('top_position')->first();
        $top_position_last = Service::where('top_position', '!=', 0)->orderByDesc('top_position')->first();

        return view('service.top-products', compact('services', 'top_position_first', 'top_position_last'));
    }

    /**
     * Move up the current Service item
     */
    public function moveUp($id)
    {
        $current = Service::findOrFail($id);
        $above = Service::where('position', '<', $current->position)->orderByDesc('position')->first();

        if ($above) {
            [$current->position, $above->position] = [$above->position, $current->position];
            $current->save();
            $above->save();
        }

        return redirect()->back();
    }

    /**
     * Move down the current Service item
     */
    public function moveDown($id)
    {
        $current = Service::findOrFail($id);
        $below = Service::where('position', '>', $current->position)->orderBy('position')->first();

        if ($below) {
            [$current->position, $below->position] = [$below->position, $current->position];
            $current->save();
            $below->save();
        }

        return redirect()->back();
    }

    /**
     * Move up the current Service Top product item
     */
    public function moveUpTopProduct($id)
    {
        $current = Service::findOrFail($id);

        $above = Service::where('top_position', '<', $current->top_position)->orderBy('top_position', 'desc')->first();

        if ($above) {
            [$current->top_position, $above->top_position] = [$above->top_position, $current->top_position];
            $current->save();
            $above->save();
        }

        return redirect()->back();
    }

    /**
     * Move down the current Service Top product item
     */
    public function moveDownTopProduct($id)
    {
        $current = Service::findOrFail($id);

        $below = Service::where('top_position', '>', $current->top_position)->orderBy('top_position', 'asc')->first();

        if ($below) {
            [$current->top_position, $below->top_position] = [$below->top_position, $current->top_position];
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
        $service = new Service();
        $allCategories = Category::all();
        $topServices = Service::where('top_position', '>', 0)->orderBy('top_position')->get();

        return view('service.create', compact('service', 'allCategories', 'topServices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request): RedirectResponse
    {
        $lastPosition = Service::max('position') ?? 0;
        $path = $request->file('image_path')->store('services', 'public');

        // 1. On crée d'abord le service sans top_position
        $service = Service::create([
            'name' => $request->name,
            'image_path' => basename($path),
            'description' => $request->description,
            'position' => $lastPosition + 1,
            'top_position' => 0,
            'availbility' => $request->boolean('availbility'),
        ]);

        $service->categories()->attach($request->input('categories', []));

        // 2. Si c’est un top produit, mettre à jour les positions selon ordre
        if ($request->boolean('is_top_product')) {
            $order = json_decode($request->input('top_order_json'), true);

            // ❗ Ici on gère le cas où l'élément est `"new"` (remplacé par $service->id)
            foreach ($order as $item) {
                $id = $item['id'] === 'new' ? $service->id : $item['id'];
                Service::where('id', $id)->update(['top_position' => $item['position']]);
            }
        }

        // 3. Toujours réorganiser pour être sûr
        // $this->reorderTopPositions();

        return Redirect::route('services.index')->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $service = Service::findOrFail($id);

        return view('service.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $service = Service::with('categories')->findOrFail($id);
        $allCategories = Category::all();
        $topServices = Service::where('top_position', '>', 0)->orderBy('top_position')->get();

        return view('service.edit', compact('service', 'allCategories', 'topServices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        $data = $request->validated();
        $data['availbility'] = $request->boolean('availbility');

        if ($request->hasFile('image_path')) {
            if ($service->image_path) {
                $oldImagePath = public_path('storage/services/' . $service->image_path);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $path = $request->file('image_path')->store('services', 'public');
            $data['image_path'] = basename($path);
        }

        $service->update($data);
        $service->categories()->sync($data['categories'] ?? []);

        if ($request->boolean('is_top_product')) {
            $order = json_decode($request->input('top_order_json'), true);

            if (is_array($order)) {
                foreach ($order as $item) {
                    Service::where('id', $item['id'])->update([
                        'top_position' => $item['position'],
                    ]);
                }
            } else {
                // Si pas d'ordre JSON valide, ajouter en fin
                $maxPosition = Service::max('top_position') ?? 0;
                $service->update(['top_position' => $maxPosition + 1]);
            }
        } else {
            $service->update(['top_position' => 0]);
        }

        // Réordonner après modification
        $this->reorderTopPositions();

        return Redirect::route('services.index')->with('success', 'Service updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        $service = Service::find($id);
        $ImagePath = public_path('storage/services/' . $service->image_path);

        unlink($ImagePath);

        $service->delete();

        $this->reorderPositions();
        $this->reorderTopPositions();

        return Redirect::route('services.index')->with('success', 'Category deleted successfully');
    }

    private function reorderPositions(): void
    {
        $services = Service::orderBy('position')->get();

        foreach ($services as $index => $service) {
            if ($service->position !== $index + 1) {
                $service->position = $index + 1;
                $service->save();
            }
        }
    }

    private function reorderTopPositions(): void
    {
        $produits_top = Service::where('top_position', '>', 0)->orderBy('top_position')->get();

        foreach ($produits_top as $index => $service) {
            $service->update(['top_position' => $index + 1]);
        }
    }
}
