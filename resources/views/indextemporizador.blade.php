<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - Temporizadores</title>
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
            padding: 20px;

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

    <!-- Cabeçalho fixo com o título e botão de Voltar -->
    <header class="header">
        <h1 class="text-3xl font-bold text-blue-400 text-center">Temporizadores</h1>
    </header>

    <!-- Botão de Voltar abaixo do cabeçalho -->
    <div class="btn-group">
        <div class="text-center">
            <a href="{{ route('passwords.index') }}" class="btn-primary">Voltar</a>
        </div>
    </div>

    <!-- Tabela com rolagem -->
    <div class="table-container">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif

        <table class="table table-auto text-left w-full">
            <thead class="bg-gray-700">
                <tr>
                    <th>ID</th>
                    <th>TempT</th>
                    <th>TempJ1</th>
                    <th>TempJ2</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($temporizadores as $temporizador)
                    <tr class="hover:bg-gray-600 transition duration-300 ease-in-out">
                        <td>{{ $temporizador->id }}</td>
                        <td>{{ $temporizador->tempT }}</td>
                        <td>{{ $temporizador->tempJ1 }}</td>
                        <td>{{ $temporizador->tempJ2 }}</td>
                        <td>
                            <a href="{{ route('temporizador.edit', $temporizador->id) }}" class="btn-warning btn-sm">Editar</a>
                            <form action="{{ route('temporizador.destroy', $temporizador->id) }}" method="POST" style="display:inline-block;">
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
