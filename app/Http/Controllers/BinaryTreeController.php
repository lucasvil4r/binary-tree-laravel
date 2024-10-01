<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class BinaryTreeController extends Controller
{
    public function index(Request $request)
    {
        // Busca o usuário que está no topo da árvore
        $topUser = User::whereNull('referrer_id')->first();

        return view('binarytree.index', compact('topUser'));
    }

    // Método para registrar um novo usuário no sistema
    public function registerUser(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'referrer_id' => 'nullable|exists:users,id',
        ]);

        $user = new User();
        $user->name = $validatedData['name'];

        // Verifica se há um referenciador (usuário que indicou)
        if (!empty($validatedData['referrer_id'])) {
            $referrer = User::find($validatedData['referrer_id']);

            // Aloca o novo usuário na esquerda ou direita do referenciador
            if (is_null($referrer->left_child_id)) {
                $referrer->left_child_id = $user->id;
            } elseif (is_null($referrer->right_child_id)) {
                $referrer->right_child_id = $user->id;
            } else {
                return response()->json(['error' => 'O usuário já tem dois filhos!'], 400);
            }

            $referrer->save();
            $user->referrer_id = $referrer->id;
        }

        $user->save();
        // Redireciona para a página da árvore binária com uma mensagem de sucesso
        //return redirect()->route('binarytree.index')->with('success', 'Usuário cadastrado com sucesso!');
        return response()->json($user, 201);
    }

    // Método para adicionar pontos a um usuário
    public function addPoints(Request $request, $userId)
    {
        $validatedData = $request->validate([
            'points' => 'required|integer|min:0',
        ]);

        $user = User::findOrFail($userId);
        $user->points += $validatedData['points'];
        $user->save();

        return response()->json(['message' => 'Pontos adicionados com sucesso!', 'user' => $user]);
    }

    // Método para obter o resumo dos pontos (lado esquerdo e direito) de um usuário
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
