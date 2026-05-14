<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractAnalysis extends Model
{
    protected $fillable = [
        'contract_id',
        'status',
        'summary',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
