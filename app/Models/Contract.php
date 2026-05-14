<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'user_id',
        'contract_type_id',
        'title',
        'file_name',
        'file_path',
        'overall_status',
        'risk_score',
        'ai_model_used',
        'total_clauses'
    ];

    public function clauses()
    {
        return $this->hasMany(ContractClause::class);
    }

    public function analyses()
    {
        return $this->hasMany(ContractAnalysis::class);
    }

    public function scans()
    {
        return $this->hasMany(ScanHistory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}