<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Services\AiEngineClient;
use App\Services\ContractAnalysisResultPersister;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContractAnalysisController extends Controller
{
    public function __construct(
        private readonly AiEngineClient $aiEngine,
        private readonly ContractAnalysisResultPersister $resultPersister
    ) {}

    public function engineHealth(): JsonResponse
    {
        $ping = $this->aiEngine->ping();

        return response()->json([
            'laravel' => 'ok',
            'ai_engine' => $ping['ok'] ? 'reachable' : 'unreachable',
            'ai_engine_detail' => $ping['ok'] ? ($ping['payload'] ?? []) : ['error' => $ping['error'] ?? 'unknown'],
        ], $ping['ok'] ? 200 : 503);
    }

    public function show(Contract $contract): JsonResponse
    {
        $contract->load([
            'clauses.analyses',
            'scans' => fn ($q) => $q->latest()->limit(5),
        ]);

        return response()->json($contract);
    }

    public function analyze(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file' => [
                'required',
                'file',
                'max:51200',
                'mimes:pdf,txt,docx',
            ],
            'user_id' => ['sometimes', 'nullable', 'integer', 'exists:users,id'],
            'contract_type_id' => ['sometimes', 'nullable', 'integer', 'exists:contract_types,id'],
        ]);

        /** @var \Illuminate\Http\UploadedFile $file */
        $file = $validated['file'];
        $userId = $validated['user_id'] ?? null;
        $contractTypeId = $validated['contract_type_id'] ?? null;

        $persist = $request->boolean(
            'persist',
            (bool) config('services.ai_engine.persist_analysis', true)
        );

        try {
            $payload = $this->aiEngine->analyze($file);
        } catch (RequestException $e) {
            $status = $e->response?->status() ?? 502;

            return response()->json([
                'message' => 'Gagal menghubungi AI engine.',
                'detail' => $e->response?->json() ?? $e->response?->body(),
            ], $status >= 400 && $status < 600 ? $status : 502);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Gagal memproses permintaan analisis.',
                'detail' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }

        $persisted = null;
        if ($persist && ($payload['status'] ?? null) === 'success') {
            try {
                $persisted = $this->resultPersister->persist(
                    $file,
                    $payload,
                    $userId,
                    $contractTypeId
                );
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => 'Analisis AI berhasil tetapi gagal menyimpan ke database.',
                    'detail' => config('app.debug') ? $e->getMessage() : null,
                    'analysis' => $payload,
                ], 500);
            }
        }

        return response()->json(array_merge($payload, [
            'persisted' => $persisted,
        ]));
    }
}
