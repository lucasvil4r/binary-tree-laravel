<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    // Método para registrar um novo usuário no sistema via API
    public function registerUser(Request $request)
    {
        // Validação de dados da requisição
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'referrer_id' => 'nullable|integer|exists:users,id', // Validação para o ID de referência
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $validatedData = $validator->validated();

        $user = new User();
        $user->name = $validatedData['name'];

        // Verifica se há um referenciador (usuário que indicou)
        if (!empty($validatedData['referrer_id'])) {
            $referrer = User::find($validatedData['referrer_id']);

            $user->referrer_id = $referrer->id;
            $user->save();

            // Aloca o novo usuário na esquerda ou direita do referenciador
            if (is_null($referrer->left_child_id)) {
                $referrer->left_child_id = $user->id;
            } elseif (is_null($referrer->right_child_id)) {
                $referrer->right_child_id = $user->id;
            } else {
                return response()->json(['error' => 'O usuário já tem dois filhos!'], 400);
            }

            $referrer->save();
        } else {
            $user->save();
        }

        return response()->json(['message' => 'Usuário cadastrado com sucesso!', 'user' => $user], 201);
    }

    // Método para adicionar pontos a um usuário via API
    public function addPoints(Request $request, $userId)
    {
        // Validação de pontos
        $validator = Validator::make($request->all(), [
            'points' => 'required|integer|min:0',
            'side' => 'required|string|in:left,right'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $validatedData = $validator->validated();

        $user = User::findOrFail($userId);

        // Adiciona pontos ao lado correto (esquerda ou direita)
        if ($validatedData['side'] == 'left') {
            $user->points_left += $validatedData['points'];
        } elseif ($validatedData['side'] == 'right') {
            $user->points_right += $validatedData['points'];
        }

        $user->save();

        return response()->json(['message' => 'Pontos adicionados com sucesso!', 'user' => $user]);
    }

    // Método para obter o resumo dos pontos (lado esquerdo e direito) de um usuário via API
    public function getPointsSummary($userId)
    {
        $user = User::findOrFail($userId);

        // Soma os pontos do lado esquerdo
        $leftPoints = $this->calculateSidePoints($user->left_child_id);

        // Soma os pontos do lado direito
        $rightPoints = $this->calculateSidePoints($user->right_child_id);

        return response()->json([
            'user' => $user->name,
            'left_points' => $leftPoints,
            'right_points' => $rightPoints
        ]);
    }

    // Função recursiva para calcular os pontos de uma subárvore (esquerda ou direita)
    private function calculateSidePoints($childId)
    {
        if (is_null($childId)) {
            return 0;
        }

        $child = User::find($childId);

        // Soma os pontos do usuário atual mais os pontos dos filhos
        return $child->points + $this->calculateSidePoints($child->left_child_id) + $this->calculateSidePoints($child->right_child_id);
    }
}
