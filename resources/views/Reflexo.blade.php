<?php

$token1 = session('game_token1');
$token2 = session('game_token2');

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogo de Reflexo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!--link rel="stylesheet" href="{{ asset('css/reflexo.css') }}"-->
    <style>
        body {
            background-color: rgb(70,70,70);
            color: white;
            font-family: 'Courier New', Courier, monospace;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Centraliza verticalmente */
            margin: 0; /* Remove margens */
        }

        h1 {
            font-family: Courier;
            font-size: 60px;
            ext-shadow: 2px 2px 5px rgba(255, 102, 0, 0.7);
        }

        .container {
            height: 70%;
            flex-direction: column;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(0,0,0,0.5);
            margin-top: 50px;
        }

        button {
            padding: 20px;
            font-size: 20px;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
            margin: 10px; /* Espaçamento entre os botões*/
        }

        button:hover {
            opacity: 0.8;
            transform: scale(1.05);
        }

        #botao {
            box-shadow: none;
        }

        .top-text{
            margin-bottom: 60px;
        }
        
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

        #tempo {
            font-size: 20px;
        }

        #Obtersenha a{
            background-color: #b41111;
            border: none;
        }

        #resultado, #tempo, #botao, #Obtersenha, #btn-recomecar, #tempoinsuficiente{
            display: none;
        }

        #mensagem{
            color: red;
        }

        #btn-iniciar, #botao, #btn-recomecar{
            background-color: #de2b10;
        }
    </style>
</head>
<body>
    <div id="temporizador"></div>
    <div class="container">
        <div class="top-text">
        <h1>Jogo de Reflexo</h1>
        <p id="tempo">Espere a cor mudar!</p>
        </div>
        <button id="btn-iniciar" onclick="iniciarJogo()">Iniciar Jogo</button>
        <button id="botao" onclick="clicar()">Clique aqui!</button>
        <div id="Obtersenha">
            @if($token === $token1)
            <a href="{{ route('passwords.passwordacess', [$token]) }}" class="btn btn-primary btn-lg shadow-custom">Ir para Senha</a>
            @else
            <a href="{{ route('passwords.passwordacess2', [$token]) }}" class="btn btn-primary btn-lg shadow-custom">Ir Para Senha</a>
            @endif
        </div>
        <div id="tempoinsuficiente">Tente Clicar antes de 0.40s</div>
        <p id="resultado"></p> <!-- Esconde o resultado inicialmente -->
        <p id="mensagem"></p> <!-- Mensagem de erro -->
        <button id="btn-recomecar" onclick="recomecar()">Recomeçar</button>
    </div>
    <script src="{{ asset('js/reflexo.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Código do Temporizador -->
    <script>
        var token = "{{ $token }}";
        var token1 = "{{ $token1 }}";
        var token2 = "{{ $token2 }}";
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
