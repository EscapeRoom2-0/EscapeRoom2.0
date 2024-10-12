<?php
    $token1 = session('game_token1');
    $token2 = session('game_token2');
    ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desarme a Bomba</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        /* Estilo do temporizador */
        #tempo {
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
            font-family: Courier, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            min-height: 100vh;
            text-align: center;
        }

        #passwordScreen {
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            font-family: Courier;
            font-size: 40px;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgb(20,20,20,0.95);
            border: #b41111 solid 5px;
            border-radius: 6px;
            color: white;
            padding: 40px;
            z-index: 1000;
        }

        #passwordScreen a {
            text-decoration: none;
            color: white;
            font-size: 30px;
            background-color: #b41111;
            padding: 10px 20px;
            border-radius: 5px;
        }

        #passwordScreen a:hover {
            text-decoration: underline;
        }

        .main-content {
            width: 70%;
            height: 85%;  
            border-radius: 10px;
            background-color: rgba(0,0,0,0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            flex-grow: 1; /* Permitir que a área principal cresça e ocupe o espaço */
        }

        .canvas-container {
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
            flex-grow: 1; /* Aumenta o tamanho da imagem */
            width: 100%;
            margin: 0px;
        }

        .canvas {
            position: relative; /* Para permitir o posicionamento absoluto dos itens */
            overflow: hidden;
            width: 90%; /* Ajustar o tamanho da imagem para ocupar mais espaço */
            /* max-width: 1000px;  Limitar o tamanho máximo para evitar estourar telas menores */
        }

        .canvas img {
            width: 90%; /* A imagem ocupará toda a área do canvas */
            height: auto; /* Manter a proporção da imagem */
        }

        .item {
            position: absolute; /* Itens serão posicionados dentro do canvas */
            width: 30px;
            height: 30px;
            cursor: pointer;
        }

        #item1 {
            top: 40%; 
            left: 65%; 
        }

        #item2 {
            top: 85%; 
            left: 90%; 
        }

        #item3 {
            top: 25%; 
            left: 50%; 
        }

        #item4 {
            top: 60%; 
            left: 85%; 
        }

        #victory{
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            font-family: Courier;
            font-size: 40px;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgb(20,20,20,0.95);
            border: #b41111 solid 5px;
            border-radius: 6px;
            color: white;
            padding: 40px;
            z-index: 1000;
        }

        #victory {
            background-color: #4CAF50;
            color: white;
        }


        .controls input {
            padding: 10px 20px;
            font-size: 16px;
            margin: 0 10px;
            cursor: pointer;
        }

        #startButton {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
            cursor: pointer;
            border-radius: 8px;
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
        }

        /* Estilo do rodapé */
        .header {
            font-family: Courier;
            font-size: 50px;
            color: #b41111;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
            z-index: 100;
        }

        .header img {
            height: 40px; /* Reduzir o tamanho das imagens no rodapé */
            margin: 0 10px;
        }
    </style>
</head>

<body>
    <!-- Temporizador -->
    <div id="tempo"></div>

    <!-- Conteúdo Principal -->
    <div class="main-content" id="desligar1">
        <div class="canvas-container">
            <!-- Cabeçalho-->
            <div class="header">
                <p>Encontre os Itens da cena do Crime:</p>
                <img src="{{ asset('img/Casinha.webp') }}" alt="Casinha">
                <img src="{{ asset('img/Pote.webp') }}" alt="Pote">
                <img src="{{ asset('img/Estomago.webp') }}" alt="Estomago">
                <img src="{{ asset('img/Orelha.webp') }}" alt="Orelha">
            </div>

            <div id="gameContainer" class="canvas">
                <img src="{{ asset('img/Bathroom.jpg') }}" alt="Cena Do Crime" class="img-fluid">
                <div id="item1" class="item">
                    <img src="{{ asset('img/Casinha.webp') }}" alt="Casinha" class="img-fluid">
                </div>
                <div id="item2" class="item">
                    <img src="{{ asset('img/Pote.webp') }}" alt="Pote" class="img-fluid">
                </div>
                <div id="item3" class="item">
                    <img src="{{ asset('img/Estomago.webp') }}" alt="Estomago" class="img-fluid">
                </div>
                <div id="item4" class="item">
                    <img src="{{ asset('img/Orelha.webp') }}" alt="Orelha" class="img-fluid">
                </div>
            </div>
        </div>


    </div>

    <!-- Tela de Senha -->
    <div id="passwordScreen" class="result" style="display: none;">
        <p>Parabéns! Você venceu!</p>
        @if($token === $token1)
        <a href="{{ route('passwords.passwordacess', [$token]) }}">Acesse a Senha</a>
        @elseif($token === $token2)
        <a href="{{ route('passwords.passwordacess2', [$token]) }}">Acesse a Senha</a>
        @endif

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
                var elemento = document.getElementById('tempo');
                
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
    <script src="{{ asset('js/Detetive.js') }}"></script>
</body>
</html>
