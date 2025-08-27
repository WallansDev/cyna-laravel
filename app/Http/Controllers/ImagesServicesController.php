<?php

namespace App\Http\Controllers;

use App\Models\ImagesServices;
use Illuminate\Http\Request;
use App\Models\ServiceImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Nette\Utils\ImageException;

class ImagesServicesController extends Controller
{
    public function destroy(ImagesServices $image): RedirectResponse
{
    // Supprime le fichier physique
    Storage::disk('public')->delete('services/gallery/'.$image->image_path);

    // Supprime la ligne BDD
    $image->delete();

    return back()->with('success', 'Image supprimée avec succès');
}

}
