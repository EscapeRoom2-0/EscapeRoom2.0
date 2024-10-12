<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Link para o Tailwind CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Desarme a Bomba - Criar Relatório</title>

    <style>
        body {
            background-color: #1c1f26; /* Fundo escuro tático */
            color: #e2e8f0; /* Texto claro */
        }

        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #2d3748; /* Fundo cinza escuro */
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            color: #63b3ed; /* Azul claro */
        }

        .form-label {
            color: #a0aec0;
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
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2c5282; /* Azul mais escuro ao passar o mouse */
        }

        .alert-danger {
            background-color: #e53e3e; /* Fundo de alerta vermelho */
            color: white;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="form-container mt-10">
    <h1 class="form-title text-3xl font-bold mb-6 text-center">Criar Novo Relatório</h1>

    <!-- Validação de erros -->
    @if ($errors->any())
        <div class="alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulário -->
    <form action="{{ route('relatorios.store') }}" method="POST">
        @csrf
        <!-- Campo Nome -->
        <div class="mb-4">
            <label for="nome" class="form-label block text-sm font-medium mb-1">Nome</label>
            <input type="text" name="nome" id="nome" class="form-control w-full" value="{{ $Nome_Ganhador ?? '' }}" required>
        </div>

        <!-- Campo Tempo -->
        <div class="mb-4">
            <label for="tempo" class="form-label block text-sm font-medium mb-1">Tempo</label>
            <input type="text" name="tempo" id="tempo" class="form-control w-full" required>
        </div>

        <!-- Campo Contato -->
        <div class="mb-4">
            <label for="contato" class="form-label block text-sm font-medium mb-1">Contato</label>
            <input type="text" name="contato" id="contato" class="form-control w-full" required>
        </div>

        <!-- Botão Enviar -->
        <button type="submit" class="btn-primary w-full">Criar Relatório</button>
    </form>
</div>
<script>
        // Exemplo de variável em segundos (substitua pelo valor de $Tempo_Ganhador vindo do Laravel)
        var tempoGanhador = {{ $Tempo_Ganhador }}; // Ex: 200 segundos
        var tempoTotal = 300; // O tempo total é 300 segundos

        // Função que faz a subtração, transforma o valor em positivo e converte para MM:SS
        function calcularTempoJogado(tempoGanhador, tempoTotal) {
            // Subtrai o tempo total do tempo jogado
            var tempoRestante = (tempoTotal - tempoGanhador);

            // Converte o valor restante para minutos e segundos
            var minutos = Math.floor(tempoRestante / 60);
            var segundos = tempoRestante % 60;

            // Formata os segundos para sempre ter 2 dígitos
            if (segundos < 10) {
                segundos = '0' + segundos;
            }

            // Retorna o tempo no formato MM:SS
            return minutos + ":" + segundos;
        }

        var tempoFormatado = calcularTempoJogado(tempoGanhador, tempoTotal);

        // Exibe o resultado no elemento HTML
        document.getElementById('tempo').value = tempoFormatado;

    </script>
</body>
</html>
