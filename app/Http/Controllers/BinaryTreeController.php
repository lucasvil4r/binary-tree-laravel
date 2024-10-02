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

        if ($topUser) {
            $leftPoints = $topUser->calculateLeftPoints() ?? 0;
            $rightPoints = $topUser->calculateRightPoints() ?? 0;
        } else {
            // Se nenhum usuário foi encontrado, defina os pontos como zero
            $leftPoints = 0;
            $rightPoints = 0;
        }

        // Busca os usuários que não têm filhos e estão prontos para receber novos usuários
        $ReferrerId = User::where(function ($query) {
            $query->orWhereNull('left_child_id')
                ->orWhereNull('right_child_id');
        })
        ->orderBy('id', 'asc') // Ordena em ordem ascendente pelo ID
        ->first(); // Obtém apenas o primeiro usuário

        return view('binarytree.index', compact('topUser', 'ReferrerId', 'leftPoints', 'rightPoints'));
    }

    // Método para registrar um novo usuário no sistema
    public function registerUser(Request $request)
    {
        // Validação dos dados do formulário
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'referrer_id' => 'nullable|integer|exists:users,id',
            'points_left' => 'required|integer|min:0',
            'points_right' => 'required|integer|min:0',
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
                $user->points = $validatedData['points_left'];
                $referrer->left_child_id = $user->id;
            } elseif (is_null($referrer->right_child_id)) {
                $user->points = $validatedData['points_right'];
                $referrer->right_child_id = $user->id;
            } else {
                return redirect('/')->with('error', 'O usuário já tem dois filhos!');
            }

            $referrer->save();
        }

        $user->save();

        return redirect('/')->with('success', 'Usuário cadastrado com sucesso!');
    }

    // Método para limpar a tabela users
    public function clearUsers()
    {
        try {
            // Trunca a tabela 'users', removendo todos os dados
            User::truncate();

            return redirect('/')->with('success', 'Tabela de usuários limpa com sucesso!');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Erro ao limpar a tabela de usuários: ' . $e->getMessage());
        }
    }
}
