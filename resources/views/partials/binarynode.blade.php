<div class="node">
    <div class="user">
        <strong>{{ $user->name }}</strong> <br> @if($user->points > 0) ({{ $user->points }} pts) @endif
        <p>ID: {{ $user->id }}</p>
    </div>

    <div class="branch">
        <!-- Exibir filho da esquerda -->
        @if($user->leftChild)
            @include('partials.binarynode', ['user' => $user->leftChild])
        @else
            <div class="user">Vazio (Esquerda)</div>
        @endif

        <!-- Exibir filho da direita -->
        @if($user->rightChild)
            @include('partials.binarynode', ['user' => $user->rightChild])
        @else
            <div class="user">Vazio (Direita)</div>
        @endif
    </div>
</div>
