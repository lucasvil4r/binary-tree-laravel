<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulação de Árvore Binária</title>

    <!-- Bootstrap CSS (via CDN) -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .tree {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .node {
            margin: 20px;
            text-align: center;
        }
        .branch {
            display: flex;
            justify-content: space-between;
            width: 300px;
        }
        .user {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">Simulação de Árvore Binária</h1>

    <!-- Formulário para Inserir Usuário -->
    <form action="{{ route('register.user') }}" method="POST" class="mb-5">
        @csrf
        <div class="form-group">
            <label for="name">Nome do Usuário:</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="referrer_id">Referenciador (ID do Usuário):</label>
            <input type="number" id="referrer_id" name="referrer_id" class="form-control" placeholder="Opcional">
        </div>
        <button type="submit" class="btn btn-primary">Cadastrar Usuário</button>
    </form>

    <!-- Exibe a Árvore Binária de Usuários -->
    <h2 class="text-center">Árvore Binária de Usuários</h2>
    <div class="tree">
        @if($topUser)
            @include('partials.binarynode', ['user' => $topUser])
        @else
            <p class="text-center">Nenhum usuário cadastrado ainda.</p>
        @endif
    </div>
</div>

<!-- Bootstrap JS and dependencies (via CDN) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
