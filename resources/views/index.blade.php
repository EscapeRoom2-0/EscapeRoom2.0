<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - Lista de Senhas</title>
    <!-- Link para o Tailwind CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1c1f26; /* Fundo escuro */
            color: #e2e8f0; /* Texto claro */
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background-color: #2d3748; /* Fundo cinza escuro */
            padding: 10px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn-group {
            margin-top: 60px;
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .table-container {
            margin-top: 130px; /* Espaço para os botões fixos */
            padding: 20px;
            overflow-y: auto;
            height: calc(100vh - 130px); /* Calcula o tamanho disponível */
        }

        .table {
            background-color: #2d3748;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .table th {
            background-color: #4a5568; /* Cabeçalho cinza escuro */
            color: white;
            padding: 12px;
        }

        .table td {
            background-color: #2d3748;
            color: white;
            padding: 12px;
        }

        .table tr:hover {
            background-color: #4a5568; /* Destaque ao passar o mouse */
        }

        .btn-primary {
            background-color: #2b6cb0; /* Azul escuro */
            color: white;
            padding: 10px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2c5282; /* Azul mais escuro ao passar o mouse */
        }

        .btn-info {
            background-color: #3182ce; /* Azul padrão */
            color: white;
            padding: 8px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .btn-info:hover {
            background-color: #2b6cb0; /* Azul mais escuro */
        }

        .btn-warning {
            background-color: #ecc94b; /* Amarelo */
            color: white;
            padding: 8px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .btn-danger {
            background-color: #e53e3e; /* Vermelho */
            color: white;
            padding: 8px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .btn-sm {
            padding: 5px 10px;
        }
    </style>
</head>
<body>

    <!-- Cabeçalho fixo com o título e botões de ação -->
    <header class="header">
        <h1 class="text-3xl font-bold text-blue-400 text-center">Lista de Senhas</h1>
    </header>

    <!-- Botões de Filtro fixos abaixo do cabeçalho -->
    <div class="btn-group mt-5">
        <a href="{{ route('passwords.filterByGame', 1) }}" class="btn-info">Jogo 1</a>
        <a href="{{ route('passwords.filterByGame', 2) }}" class="btn-info">Jogo 2</a>
        <a href="{{ route('passwords.filterByGame', 3) }}" class="btn-info">Jogo 3</a>
        <a href="{{ route('passwords.filterByGame', 4) }}" class="btn-info">Jogo 4</a>
        <a href="{{ route('passwords.filterByGame', 5) }}" class="btn-info">Jogo 5</a>
        <a href="{{ route('passwords.filterByGame', 6) }}" class="btn-info">Jogo 6</a>
        <a href="{{ route('passwords.filterByGame', 7) }}" class="btn-info">Jogo 7</a>
        <a href="{{ route('passwords.filterByGame', 8) }}" class="btn-info">Jogo 8</a>
        <a href="{{ route('passwords.filterByGame', 9) }}" class="btn-info">Jogo 9</a>
        <a href="{{ route('passwords.filterByGame', 10) }}" class="btn-info">Jogo 10</a>
        <a href="{{ route('passwords.selectGame') }}" class="btn-info">Selecionar Jogo</a>
        <a href="{{ route('temporizador.index') }}" class="btn-info">Ver Temporizador</a>
        <a href="{{ route('passwords.create') }}" class="btn-primary">Criar Nova Senha</a>
    </div>

    <!-- Tabela com rolagem -->
    <div class="table-container">
        <table class="table table-auto text-left w-full">
            <thead class="bg-gray-700">
                <tr>
                    <th>ID</th>
                    <th>Senha</th>
                    <th>Jogo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            @foreach($passwords as $password)
                <tr class="hover:bg-gray-600 transition duration-300 ease-in-out">
                    <td>{{ $password->id }}</td>
                    <td>{{ $password->pass }}</td>
                    <td>{{ $password->game->id }}</td>
                    <td>
                        <a href="{{ route('passwords.edit', $password->id) }}" class="btn-warning btn-sm">Editar</a>
                        <form action="{{ route('passwords.destroy', $password->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger btn-sm">Deletar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>
