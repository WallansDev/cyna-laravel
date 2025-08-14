<?php

namespace App\Http\Controllers;

use App\Models\BillingAddress;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function testBillingAddress()
    {
        try {
            // Créer une instance du modèle
            $address = new BillingAddress();
            
            // Vérifier si les méthodes existent
            $methods = [
                'isRecentlyCreated' => method_exists($address, 'isRecentlyCreated'),
                'isRecentlyUpdated' => method_exists($address, 'isRecentlyUpdated'),
                'getCreatedAtFormattedAttribute' => method_exists($address, 'getCreatedAtFormattedAttribute'),
                'getUpdatedAtFormattedAttribute' => method_exists($address, 'getUpdatedAtFormattedAttribute'),
                'getFullAddressAttribute' => method_exists($address, 'getFullAddressAttribute'),
            ];
            
            // Tester avec une adresse existante
            $existingAddress = BillingAddress::first();
            
            if ($existingAddress) {
                $testResults = [
                    'created_at_formatted' => $existingAddress->created_at_formatted,
                    'updated_at_formatted' => $existingAddress->updated_at_formatted,
                    'full_address' => $existingAddress->full_address,
                    'isRecentlyCreated' => $existingAddress->isRecentlyCreated(),
                    'isRecentlyUpdated' => $existingAddress->isRecentlyUpdated(),
                ];
            } else {
                $testResults = 'Aucune adresse trouvée dans la base de données';
            }
            
            return response()->json([
                'methods_exist' => $methods,
                'test_results' => $testResults,
                'model_class' => get_class($address),
                'timestamps_enabled' => $address->timestamps,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }
} 