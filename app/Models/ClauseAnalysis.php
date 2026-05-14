<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClauseAnalysis extends Model
{
    protected $fillable = [
        'contract_clause_id',
        'scan_history_id',
        'status',
        'severity',
        'confidence_score',
        'analysis',
        'legal_basis',
        'suggestion'
    ];

    public function clause()
    {
        return $this->belongsTo(ContractClause::class);
    }
}