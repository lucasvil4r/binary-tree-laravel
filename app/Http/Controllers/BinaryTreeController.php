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

        // Busca os usuários que não têm filhos e estão prontos para receber novos usuários
        $ReferrerId = User::where(function ($query) {
            $query->orWhereNull('left_child_id')
                ->orWhereNull('right_child_id');
        })
        ->orderBy('id', 'asc') // Ordena em ordem ascendente pelo ID
        ->first(); // Obtém apenas o primeiro usuário

        return view('binarytree.index', compact('topUser', 'ReferrerId'));
    }

    // Método para registrar um novo usuário no sistema
    public function registerUser(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'referrer_id' => 'nullable|integer|exists:users,id', // Validação para o ID de referência
        ]);

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
                return redirect()->route('binarytree.index')->with('error', 'O usuário já tem dois filhos!');
            }

            $referrer->save();
        } else {
            $user->save();
        }

        return redirect()->route('binarytree.index')->with('success', 'Usuário cadastrado com sucesso!');
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

        return redirect()->route('binarytree.index')->with('success', 'O usuário já tem dois filhos!');
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
