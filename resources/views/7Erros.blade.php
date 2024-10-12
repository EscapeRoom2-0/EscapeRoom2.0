<?php

$token1 = session('game_token1');
$token2 = session('game_token2');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Jogo dos 7 Erros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="{{ asset('js/jquery-2.1.3.min.js') }}"></script>
    <script src="{{ asset('js/game.js') }}"></script>
    <script>
        $(document).ready(function(){
            var positions = {
                '0': { x : '195', y : '125' },
                '1': { x : '476', y : '130' },
                '2': { x : '90' , y : '365' },
                '3': { x : '330', y : '255' },
                '4': { x : '380', y : '425' },
                '5': { x : '190', y : '460' },
                '6': { x : '190', y : '70' }
            };

            jogo = new Game(positions);
            // jogo.debug();

            jogo.startGame();
        });
    </script>
    <style>
        #temporizador {
            position: fixed;
            font-family: Courier;
            font-weight: bold;
            top: 10px;
            left: 10px;
            background-color: #212529;
            color: white;
            padding: 5px 10px;
            border: 2px solid #b41111;
            border-radius: 5px;
            font-size: 18px;
            z-index: 10000; 
        }

        body {
            background-color: rgb(70,70,70);
            color: white;
            font-family: Courier;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            text-align: center;
            padding-top: 60px; /* Adiciona espaço para o cabeçalho fixo */
        }
        header {
            display: flex;
            text-align: center;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #ba1111;
            color: white;
            padding: 15px;
            font-size: 24px;
            font-weight: bold;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            text-align: center;
            z-index: 10; 
        }
        .main-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 75%;
            height: 850px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
        }
        .canvas-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
        }
        .canvas {
            position: relative;
            cursor: url('{{ asset('img/cursor.png') }}'), auto;
            overflow: hidden;
        }

        .disabled {
            pointer-events: none; /* Desativa eventos de clique */
            opacity: 0.5; /* Opcional: Adiciona um efeito visual de desativação */
        }

        .marker {
            width: 40px;
            height: 40px;
            background: url('{{ asset('img/markerI.png') }}') no-repeat center center;
            background-size: cover;
            position: absolute;
        }
        .marker.accept {
            width: 40px;
            height: 40px;
            background: url('{{ asset('img/markerR.png') }}') no-repeat center center;
            background-size: cover;
            position: absolute;
        }
        .marker.reject {
            background: red;
        }
        .target {
            width: 50px;
            height: 50px;
            background: green;
            position: absolute;
            border-radius: 50%;
        }
        .cursor {
            position: absolute;
            left: 0;
            top: 0;
            width: 17px;
            height: 25px;
            opacity: 0;
            background: url('{{ asset('img/cursor.png') }}') center top no-repeat;
            transition: opacity 0.2s linear;
            z-index: 2;
        }
        .cursor.visible {
            opacity: 1;
        }
        .controls {
            margin: 20px 0;
        }
        .controls input {
            padding: 10px 20px;
            font-size: 16px;
            margin: 0 10px;
            cursor: pointer;
        }

        #startButton {
            background-color: #226e15; 
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-family: Courier;
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s, transform 0.2s;
            z-index: 10; 
        }

        #button {
            background-color: #ff0000;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s, transform 0.2s;
        }

        #button:hover {
            background-color: #a50000;
        }

        #victory {
            position: fixed; 
            top: 50%; 
            left: 50%; 
            transform: translate(-50%, -50%); 
            background-color: #4CAF50; 
            color: white; 
            font-size: 24px; 
            font-weight: bold; 
            padding: 40px; 
            border: 5px solid #fff; 
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.7); 
            z-index: 1000;
            transform-origin: center;
            display: none;
        }

        #victory .password {
            color: #FFEB3B; 
            font-size: 28px; 
            font-weight: bold; 
            background-color: #388E3C; 
            padding: 5px 10px; 
            border-radius: 5px; 
        }

        #defeat {
            position: fixed; 
            top: 50%; 
            left: 50%; 
            transform: translate(-50%, -50%); 
            background-color: #1d1b1b; 
            color: white; 
            font-size: 24px; 
            font-weight: bold; 
            padding: 40px; 
            border: 5px solid #b71c1c; 
            box-shadow: 0 0 20px #d61111; 
            z-index: 1000;
            display: none;
        }

        h1{
            font-family: Courier;
            font-size: 60px;
            font-weight: bold;
            color: #b41111;
            margin-bottom: 70px;
        }

        #temporizador{
            position: fixed;
            top: 10px;
            left: 10px;
            background-color: rgb(40,40,40);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;;
            font-size: 18px;
            z-index: 20; 
        }
    </style>
</head>
<body>
    <div id="temporizador"></div>
    <div class="main-content">
    <h1>Jogo dos 7 Erros</h1>
        <div class="canvas-container">
            <div id="canvas-main" class="canvas">
                <img src="{{ asset('img/imagem-esquerda.jpg') }}" draggable="false">
            </div>
            <div id="canvas-copy" class="canvas">
                <img src="{{ asset('img/imagem-direita.jpg') }}" draggable="false">
                <i class="cursor"></i>
            </div>
        </div>
        <input type="button" style="display: none;" id="startButton" value="Iniciar jogo" onclick="jogo.startGame();">
    </div>
    <div id="victory" class="result" style="display: none;">
        <p>Parabéns! Você venceu!</p>
        @if($token === $token1)
        <a href="{{ route('passwords.passwordacess', [$token]) }}">Acesse a Senha</a>
        @elseif($token === $token2)
        <a href="{{ route('passwords.passwordacess2', [$token]) }}">Acesse a Senha</a>
        @endif

    </div>
    <div id="defeat" class="result" style="display: none;">
        <p>Você Perdeu</p>
        <div class="controls">
            <input type="button" id="button" value="Jogar novamente" onclick="jogo.resetGame();">
        </div>
    </div>
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
            document.getElementById('tempo').innerText = "O tempo acabou!";
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
