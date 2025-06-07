<?php

namespace App\Http\Controllers;

use App\Models\Carousel;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Carousel\StoreCarouselRequest;
use App\Http\Requests\Carousel\UpdateCarouselRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CarouselController extends Controller
{
    /**
     * Display a listing of the resource.
     */

        public function index(): View
    {
        $carousels = Service::where('top_position', '!=', 0)->orderBy('top_position')->get();
        $carousel_first = Service::where('top_position', '!=', 0)->orderBy('top_position')->first();
        $carousel_last = Service::where('top_position', '!=', 0)->orderByDesc('top_position')->first();

        return view('carousel.index', compact('carousels', 'carousel_first', 'carousel_last'));
    }
}
