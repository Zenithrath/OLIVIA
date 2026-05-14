<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractClause extends Model
{
    protected $fillable = [
        'contract_id',
        'clause_order',
        'page_number',
        'clause_title',
        'clause_text'
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function analyses()
    {
        return $this->hasMany(ClauseAnalysis::class);
    }
}