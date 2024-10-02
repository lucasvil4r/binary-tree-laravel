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
        .points {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Estilos gerais para a árvore binária */
        .node {
            text-align: center;
            margin: 10px;
        }

        /* Estilo para o nó do usuário */
        .user {
            padding: 10px;
            background-color: #e3f2fd;
            border: 2px solid #90caf9;
            border-radius: 5px;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            min-width: 120px;
            text-align: center;
            position: relative;
            width: 100px;
            margin: auto;
        }

        /* Estilo para ramificação dos filhos */
        .branch {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            margin-top: 20px;
            position: relative;
        }

        /* Linhas que conectam os nós, estilo flowchart */
        .node:before, .node:after {
            content: '';
            position: absolute;
            top: 0;
            border-top: 2px solid #90caf9;
            width: 50%;
            height: 20px;
        }

        .node:before {
            left: 50%;
            border-right: 2px solid #90caf9;
        }

        .node:after {
            right: 50%;
            border-left: 2px solid #90caf9;
        }

        /* Conexão vertical entre os nós */
        .branch:before {
            content: '';
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 20px;
            background-color: #90caf9;
            z-index: -1;
        }

        /* Para os nós vazios */
        .user.empty {
            background-color: #ffebee;
            color: #b71c1c;
            font-style: italic;
            border: 2px dashed #f44336;
        }

        /* Organização responsiva para dispositivos menores */
        @media (max-width: 768px) {
            .branch {
                flex-direction: column;
                align-items: center;
            }

            .node:before, .node:after {
                width: 0;
            }

            .node {
                margin: 20px 0;
            }
        }
    </style>
</head>
<body>

<div class="container mt-5">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h1 class="text-center">Simulação de Árvore Binária</h1>

    <form action="{{ route('binarytree.register') }}" method="POST" class="mb-5 p-4 border rounded shadow-sm" style="background-color: #f9f9f9;">
        @csrf

        <h4 class="mb-4">Cadastrar Novo Usuário</h4>

        <!-- Campo para o nome do usuário -->
        <div class="form-group mb-3">
            <label for="name" class="form-label">Nome do Usuário:</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Digite o nome do usuário" required>
        </div>

        <!-- Campo para o referenciador (usuário pai) -->
        <div class="form-group mb-3">
            <label for="referrer_id" class="form-label">Referenciador (ID do Usuário PAI):</label>
            <input type="number" id="referrer_id" name="referrer_id" class="form-control"
                @if ($ReferrerId) value="{{$ReferrerId->id}}" @endif readonly>
        </div>

        <!-- Seção de regras de pontuação -->
        <div class="points form-group mb-4">
            <label class="form-label">Regras de Pontuação</label>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <label for="points_left" class="form-label">Pontos à Esquerda:</label>
                    <input type="number" id="points_left" name="points_left" class="form-control" value="200" readonly>
                </div>
                <span class="mx-3 text-muted">+</span>
                <div>
                    <label for="points_right" class="form-label">Pontos à Direita:</label>
                    <input type="number" id="points_right" name="points_right" class="form-control" value="100" readonly>
                </div>
            </div>
            <small class="form-text text-muted mt-2">
                A pontuação será atribuída automaticamente para cada lado.
            </small>
        </div>

        <!-- Botão de cadastro -->
        <button type="submit" class="btn btn-primary btn-block">Cadastrar Usuário</button>
        <button id="clear-tree-btn" class="btn btn-danger btn-block">Limpar Árvore Binária</button>
    </form>

    <!-- Exibe a Árvore Binária de Usuários -->
    <h2 class="text-center">Árvore Binária de Usuários</h2>
    <div class="tree">
        @if($topUser)
            <p>Pontos do Lado Esquerdo: <strong>{{ $leftPoints }}</strong></p>
            <p>Pontos do Lado Direito: <strong>{{ $rightPoints }}</strong></p>

            @include('partials.binarynode', ['user' => $topUser, 'leftPoints' => $leftPoints, 'rightPoints' => $rightPoints])
        @else
            <p class="text-center">Nenhum usuário cadastrado ainda.</p>
        @endif
    </div>
</div>

<!-- Bootstrap JS and dependencies (via CDN) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    document.getElementById('clear-tree-btn').addEventListener('click', function(event) {
        event.preventDefault(); // Previne o comportamento padrão do botão
        if (confirm('Você tem certeza que deseja limpar a árvore binária?')) {
            fetch('{{ route("binarytree.clear") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Inclui o token CSRF para segurança
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                window.location.reload(); // Recarrega a página após sucesso
            })
            .catch(error => {
                window.location.reload(); // Recarrega a página após erro
            });
        }
    });
</script>

</body>
</html>
