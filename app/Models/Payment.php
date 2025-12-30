<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'commande_id',
        'montant',
        'methode',
        'statut',
        'reference_transaction',
        'date_paiement',
        'notes',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_paiement' => 'datetime',
    ];

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    public function isComplete(): bool
    {
        return $this->statut === 'complete';
    }

    public function isRefunded(): bool
    {
        return $this->statut === 'refunded';
    }
}
