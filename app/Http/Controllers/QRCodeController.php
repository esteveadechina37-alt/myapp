<?php

namespace App\Http\Controllers;

use App\Models\TableRestaurant;
use Illuminate\Http\Request;

class QRCodeController extends Controller
{
    /**
     * Afficher le scanner QR
     */
    public function showScanner()
    {
        return view('qrcode.scanner');
    }

    /**
     * Vérifier le code QR scanné
     */
    public function checkQRCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        // Vérifier si la table existe et est disponible
        $table = TableRestaurant::where('qr_code', $request->code)
                     ->where('statut', 'libre')
                     ->first();

        if (!$table) {
            return response()->json([
                'success' => false,
                'message' => 'Table non trouvée ou non disponible.'
            ], 404);
        }

        // Mettre à jour le statut de la table
        $table->update(['statut' => 'occupée']);

        return response()->json([
            'success' => true,
            'table_id' => $table->id,
            'table_number' => $table->numero
        ]);
    }
}
