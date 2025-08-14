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
        
        \Log::info('Requête reçue dans SearchController', [
            'q' => $query
        ]);

        $results = [];

        // Recherche dans les services
        $services = Service::where('name', 'LIKE', '%' . $query . '%')
            ->select('id', 'name')
            ->get();

        foreach ($services as $service) {
            $results[] = [
                'name' => $service->name,
                'url' => route('services.show', $service->id), // Ajustez si cette route n'existe pas
                'type' => 'Service'
            ];
        }

        // Recherche dans les catégories
        $categories = Category::where('name', 'LIKE', '%' . $query . '%')
            ->select('id', 'name')
            ->get();

        foreach ($categories as $category) {
            $results[] = [
                'name' => $category->name,
                'url' => route('categories.show', $category->id), // Ajustez si cette route n'existe pas
                'type' => 'Catégorie'
            ];
        }

        \Log::info('Résultats trouvés', [
            'count' => count($results),
            'results' => $results
        ]);

        return response()->json($results);
    }

    // Fonction utilitaire pour supprimer les accents (si vous en avez besoin plus tard)
    private function removeAccents($str)
    {
        return preg_replace('/\p{Mn}/u', '', \Normalizer::normalize($str, \Normalizer::FORM_D));
    }
}