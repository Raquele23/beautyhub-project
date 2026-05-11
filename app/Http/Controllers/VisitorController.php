<?php

namespace App\Http\Controllers;

use App\Models\Professional;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    /**
     * Valida quais profissionais ainda existem na lista fornecida.
     * Retorna apenas os IDs de profissionais que existem (e cujo usuário está ativo).
     */
    public function validateVisited(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array', 'max:100'],
            'ids.*' => ['required', 'integer', 'min:1'],
        ]);

        $ids = $request->input('ids');

        // Busca apenas os profissionais que existem (não deletados)
        // Também filtra por usuários que ainda existem
        $existingIds = Professional::whereIn('id', $ids)
            ->whereHas('user', function ($query) {
                $query->where('deleted_at', null);
            })
            ->pluck('id')
            ->toArray();

        return response()->json([
            'valid_ids' => $existingIds,
        ]);
    }
}
