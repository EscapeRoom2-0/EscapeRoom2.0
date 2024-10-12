
<?php

$token1 = session('game_token1');
$token2 = session('game_token2');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Link para o CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Link para o arquivo CSS personalizado -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Desarme a bomba</title>
    <style>
        body {
            background-color: #f8d7da;
            color: #721c24;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: white;
            height: 50%
            border: 2px solid #f5c6cb;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #dc3545;
        }

        h2 {
            font-size: 2rem;
            color: #6c757d;
        }

        p {
            font-size: 1.2rem;
        }

        .loading {
            margin-top: 30px;
            font-size: 1.5rem;
        }

        .dot {
            color: #dc3545;
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0% { opacity: 1; }
            50% { opacity: 0; }
            100% { opacity: 1; }
        }

        /* Para melhorar a experiência em dispositivos menores */
        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }

            h2 {
                font-size: 1.5rem;
            }

            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
<div id="temporizador" class="position-fixed top-0 start-0 bg-dark text-white p-2 m-2 rounded shadow"></div>
    <div class="container">
        <!-- Div principal do usuário -->
        <div class="user">
            <h1>Erro</h1>
            <div class="cont">
                <h2>Tente Outro Computador</h2>
                <p>Este computador já foi utilizado ou está indisponível no momento.</p>

                <!-- Indicador de carregamento com três pontinhos -->
                <div class="loading" id="loading">
                    <span class="dot">.</span>
                    <span class="dot">.</span>
                    <span class="dot">.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Link para o arquivo JavaScript -->
    <script src="js/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    var token = "{{ $token }}"; // Pega o token atual
    var token1 = "{{ $token1 }}"; // Token do jogador 1
    var token2 = "{{ $token2 }}"; // Token do jogador 2
    var intervalo;
    var tempoRestanteLocal;
    var tempoRestanteServidor;
    var diferencaMaxima = 3; // Diferença máxima permitida entre o tempo local e o do servidor (3 segundos)

    // Função para atualizar o temporizador localmente
    function atualizarTemporizadorLocal() {
        if (tempoRestanteLocal > 0) {
            var elemento = document.getElementById('temporizador');
            
            // Converte o tempo restante local em minutos e segundos
            var minutos = Math.floor(tempoRestanteLocal / 60);
            var segundos = tempoRestanteLocal % 60;

            // Adiciona zero à esquerda se necessário
            if (segundos < 10) {
                segundos = "0" + segundos;
            }

            // Atualiza o elemento com o tempo no formato MM:SS
            elemento.innerText = minutos + ":" + segundos + " restantes";

            // Reduz o tempo localmente
            tempoRestanteLocal--;

        } else {
            // Quando o tempo acabar
            document.getElementById('temporizador').innerText = "O tempo acabou!";
            clearInterval(intervalo); // Para o temporizador
            if(token == token1){
            window.location.href = "{{ route('temporizador.TempoAcabado') }}"; // Redireciona
            }else{
                window.location.href = "{{ route('temporizador.TempoAcabado2') }}";
            }
        }
    }

    // Função para sincronizar o tempo com o servidor via AJAX
    function verificarTempoServidor() {
        var url = "";

        // Define a rota correta com base no token do jogador
        if (token == token1) {
            url = "{{ route('temporizador.obterTempo1') }}"; // Rota para o jogador 1
        } else if (token == token2) {
            url = "{{ route('temporizador.obterTempo2') }}"; // Rota para o jogador 2
        }

        // Faz a requisição AJAX com a rota correta
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                tempoRestanteServidor = response.tempoRestante; // Recebe o tempo atualizado do servidor
                
                // Se a diferença entre o tempo local e o do servidor for >= 3, ajusta o tempo local
                var diferenca = Math.abs(tempoRestanteLocal - tempoRestanteServidor);
                if (diferenca >= diferencaMaxima) {
                    tempoRestanteLocal = tempoRestanteServidor; // Ajusta o tempo local
                    console.log("Tempo ajustado para sincronizar com o servidor.");
                }
            },
            error: function(xhr) {
                console.error("Erro ao obter o tempo do servidor:", xhr.statusText);
            }
        });
    }

    // Inicia o temporizador local e a verificação com o servidor
    function iniciarTemporizador() {
        var url = "";

        // Define a rota correta com base no token do jogador
        if (token == token1) {
            url = "{{ route('temporizador.obterTempo1') }}"; // Rota para o jogador 1
        } else if (token == token2) {
            url = "{{ route('temporizador.obterTempo2') }}"; // Rota para o jogador 2
        }

        // Recupera o tempo inicial do servidor
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                tempoRestanteLocal = response.tempoRestante; // Define o tempo inicial do servidor no temporizador local

                // Inicia a contagem local
                intervalo = setInterval(atualizarTemporizadorLocal, 1000); // Atualiza localmente a cada 1 segundo

                // Verifica o tempo do servidor a cada 10 segundos para manter a sincronização
                setInterval(verificarTempoServidor, 10000); // Verifica a cada 10 segundos
            },
            error: function(xhr) {
                console.error("Erro ao obter o tempo inicial do servidor:", xhr.statusText);
            }
        });
    }

    // Inicia o temporizador ao carregar a página
    iniciarTemporizador();
</script>
</body>
</html>
