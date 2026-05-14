<?php

namespace App\Services;

use App\Models\ClauseAnalysis;
use App\Models\Contract;
use App\Models\ContractClause;
use App\Models\ScanHistory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ContractAnalysisResultPersister
{
    /**
     * @param  array<string, mixed>  $aiResponse
     * @return array{contract_id: int, scan_history_id: int}
     */
    public function persist(UploadedFile $file, array $aiResponse, ?int $userId = null, ?int $contractTypeId = null): array
    {
        return DB::transaction(function () use ($file, $aiResponse, $userId, $contractTypeId) {
            $disk = 'local';
            $dir = 'contracts/'.now()->format('Y/m');
            $storedPath = $file->store($dir, $disk);

            $results = $aiResponse['results'] ?? [];
            $total = (int) ($aiResponse['total_clause'] ?? count($results));

            $contract = Contract::create([
                'user_id' => $userId,
                'contract_type_id' => $contractTypeId,
                'title' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) ?: 'Kontrak',
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $storedPath,
                'overall_status' => 'completed',
                'risk_score' => null,
                'ai_model_used' => null,
                'total_clauses' => $total,
            ]);

            $scan = ScanHistory::create([
                'contract_id' => $contract->id,
                'user_id' => $userId,
                'status' => 'completed',
                'started_at' => now(),
                'finished_at' => now(),
                'meta' => [
                    'ai_status' => $aiResponse['status'] ?? null,
                ],
            ]);

            $order = 0;
            foreach ($results as $row) {
                $order++;
                if (! is_array($row)) {
                    continue;
                }

                $clauseText = $this->stringify($row['clause'] ?? '');
                $analysisText = $this->stringify($row['analysis'] ?? '');

                $clause = ContractClause::create([
                    'contract_id' => $contract->id,
                    'clause_order' => $order,
                    'page_number' => null,
                    'clause_title' => null,
                    'clause_text' => $clauseText,
                ]);

                ClauseAnalysis::create([
                    'contract_clause_id' => $clause->id,
                    'scan_history_id' => $scan->id,
                    'status' => 'completed',
                    'severity' => null,
                    'confidence_score' => null,
                    'analysis' => $analysisText,
                    'legal_basis' => null,
                    'suggestion' => null,
                ]);
            }

            return [
                'contract_id' => $contract->id,
                'scan_history_id' => $scan->id,
            ];
        });
    }

    private function stringify(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        return json_encode($value, JSON_UNESCAPED_UNICODE) ?: '';
    }
}
