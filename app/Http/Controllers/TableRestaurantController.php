<?php

namespace App\Http\Controllers;

use App\Models\TableRestaurant;
use App\Models\Reservation;
use App\Models\Client;
use Illuminate\Http\Request;

/**
 * Contrôleur Table Restaurant
 * Gère les tables et les réservations
 */
class TableRestaurantController extends Controller
{
    /**
     * Lister toutes les tables avec leur statut
     */
    public function index()
    {
        $tables = TableRestaurant::all();
        
        $tablesByZone = $tables->groupBy('zone');

        return view('tables.index', [
            'tables' => $tables,
            'tablesByZone' => $tablesByZone,
        ]);
    }

    /**
     * Afficher le statut en temps réel des tables (API)
     */
    public function getStatut()
    {
        $tables = TableRestaurant::all();
        
        $tableau = [];
        foreach ($tables as $table) {
            $tableau[] = [
                'id' => $table->id,
                'numero' => $table->numero,
                'capacite' => $table->capacite,
                'zone' => $table->zone,
                'est_disponible' => $table->est_disponible,
                'position_x' => $table->position_x,
                'position_y' => $table->position_y,
            ];
        }

        return response()->json([
            'success' => true,
            'tables' => $tableau,
        ]);
    }

    /**
     * Attribuer une table à une commande
     */
    public function attribuer(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables_restaurant,id',
            'commande_id' => 'nullable|exists:commandes,id',
        ]);

        $table = TableRestaurant::findOrFail($request->table_id);

        if (!$table->est_disponible) {
            return response()->json([
                'success' => false,
                'message' => 'Cette table n\'est pas disponible',
            ], 422);
        }

        $table->update(['est_disponible' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Table attribuée avec succès',
            'table' => $table,
        ]);
    }

    /**
     * Libérer une table
     */
    public function liberer($tableId)
    {
        $table = TableRestaurant::findOrFail($tableId);
        $table->update(['est_disponible' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Table libérée',
        ]);
    }

    /**
     * Créer une réservation
     */
    public function reserver(Request $request)
    {
        $validated = $request->validate([
            'nom_client' => 'required|string|max:100',
            'nombre_personnes' => 'required|integer|min:1',
            'date_heure' => 'required|date_format:Y-m-d H:i',
            'duree_prevue' => 'integer|min:30',
            'client_id' => 'nullable|exists:clients,id',
        ]);

        // Chercher une table appropriée
        $table = TableRestaurant::where('capacite', '>=', $validated['nombre_personnes'])
            ->where('est_disponible', true)
            ->first();

        if (!$table) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune table disponible pour ce nombre de personnes',
            ], 422);
        }

        $reservation = Reservation::create([
            'nom_client' => $validated['nom_client'],
            'nombre_personnes' => $validated['nombre_personnes'],
            'date_heure' => $validated['date_heure'],
            'duree_prevue' => $validated['duree_prevue'] ?? 120,
            'table_id' => $table->id,
            'client_id' => $validated['client_id'] ?? null,
            'statut' => 'en_attente',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Réservation confirmée',
            'reservation' => $reservation,
        ]);
    }

    /**
     * Lister les réservations
     */
    public function listReservations()
    {
        $reservations = Reservation::orderBy('date_heure')
            ->paginate(20);

        return view('reservations.list', ['reservations' => $reservations]);
    }
}
