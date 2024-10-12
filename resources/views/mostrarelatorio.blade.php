<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - Lista de Relatórios</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1c1f26;
        }

        /* Cores inspiradas no tema policial */
        .header {
            background-color: #2c3e50;
        }

        .table-container {
            margin-top: 20px;
        }

        /* Sombras e bordas para criar um efeito tático */
        .table {
            border-collapse: separate;
            border-spacing: 0 0.5rem;
        }

        .table th {
            background-color: #34495e;
        }

        .table td, .table th {
            padding: 0.75rem;
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        .alert-success {
            background-color: #27ae60;
        }
    </style>
</head>
<body class="text-gray-100">

    <header class="header text-white p-4 shadow-md flex justify-between items-center">
        <h1 class="text-2xl font-semibold">Lista de Relatórios</h1>

    </header>

    <div class="table-container max-w-7xl mx-auto p-4">
        @if (session('success'))
            <div class="alert-success text-white p-4 rounded mb-4 shadow-md">
                {{ session('success') }}
            </div>
        @endif

        <table class="table w-full text-left bg-gray-800 shadow-lg rounded-lg">
            <thead>
                <tr class="text-gray-300 text-sm">
                    <th class="bg-gray-900 p-3">ID</th>
                    <th class="bg-gray-900 p-3">Nome</th>
                    <th class="bg-gray-900 p-3">Tempo</th>
                    <th class="bg-gray-900 p-3">Contato</th>
                    <th class="bg-gray-900 p-3">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($relatorios as $relatorio)
                    <tr class="bg-gray-700 hover:bg-gray-600 text-gray-100 transition duration-300 ease-in-out">
                        <td class="p-3">{{ $relatorio->id }}</td>
                        <td class="p-3">{{ $relatorio->nome }}</td>
                        <td class="p-3">{{ $relatorio->tempo }}</td>
                        <td class="p-3">{{ $relatorio->contato }}</td>
                        <td class="p-3 flex space-x-2">
                            <form action="{{ route('relatorios.destroy', $relatorio->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow">
                                    Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>
