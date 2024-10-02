<div class="node">
    <div class="user">
        <strong>{{ $user->name }}</strong><br>
        @if($user->points > 0)
            ({{ $user->points }} pts)
        @endif
        <p>ID: {{ $user->id }}</p>
    </div>

    <div class="branch">
        <!-- Exibir filho da esquerda -->
        @if($user->leftChild)
            @include('partials.binarynode', ['user' => $user->leftChild])
        @endif

        <!-- Exibir filho da direita -->
        @if($user->rightChild)
            @include('partials.binarynode', ['user' => $user->rightChild])
        @endif
    </div>
</div>
