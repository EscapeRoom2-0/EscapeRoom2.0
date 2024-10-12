<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecionar Jogo</title>
    <!-- Tailwind CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1c1f26; /* Fundo escuro tático */
            color: #e2e8f0; /* Texto claro */
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #2d3748; /* Fundo cinza escuro */
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #63b3ed; /* Azul claro */
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .form-label {
            color: #a0aec0; /* Cinza claro */
            margin-bottom: 10px;
        }

        .form-control {
            background-color: #1a202c; /* Campo escuro */
            border: 1px solid #4a5568;
            color: #e2e8f0;
            padding: 10px;
            border-radius: 4px;
        }

        .form-control:focus {
            outline: none;
            border-color: #63b3ed; /* Azul claro ao focar */
            box-shadow: 0 0 5px rgba(99, 179, 237, 0.5);
        }

        .btn-primary {
            background-color: #2b6cb0; /* Azul escuro */
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2c5282; /* Azul mais escuro ao passar o mouse */
        }
    </style>
</head>
<body>

    <div class="container mt-10">
        <h1>Selecionar Jogo</h1>

        <form action="{{ route('temporizador.iniciar') }}" method="GET">
            <div class="form-group mb-4">
                <label for="game_id" class="form-label">Selecione um Jogo:</label>
                <select name="game_id" id="game_id" class="form-control" required>
                    <option value="" disabled selected>Escolha um Jogo</option>
                    @foreach($games as $game)
                        <option value="{{ $game->id }}">Jogo {{ $game->id }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-4">
                <label for="Jogador1" class="form-label">Jogador 1:</label>
                <input type="text" name="Jogador1" id="Jogador1" class="form-control" required>
            </div>

            <div class="form-group mb-4">
                <label for="Jogador2" class="form-label">Jogador 2:</label>
                <input type="text" name="Jogador2" id="Jogador2" class="form-control" required>
            </div>

            <button type="submit" class="btn-primary">Iniciar Temporizador</button>
        </form>
    </div>

</body>
</html>
