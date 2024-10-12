<?php

$token1 = session('game_token1');
$token2 = session('game_token2');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogo de Pedra-Papel-Tesoura</title>
    <!-- Link para o CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: rgb(70,70,70);
            font-family: Courier;
        }

        /* Estilos para a div container */
        .container {
            width: 85%;
            height: 85%;   

            display:flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #ffffff;
            background: rgba(0, 0, 0, 0.5); /* Fundo semitransparente para destacar o texto */
            border-radius: 10px; /* Bordas arredondadas */
        }

        .user {
            text-align: center;
            /* Alinhamento do texto centralizado */
            min-height: 100%;
            /* Garante que ocupe toda a altura do pai */
            display: flex;
            /* Utiliza flexbox para centralizar */
            justify-content: center;
            /* Centraliza horizontalmente */
            align-items: center;
            /* Centraliza verticalmente */
        }

        .user h1 {
            font-size: 50px;
            /* Tamanho do título */
            margin-bottom: 1.5rem;
            /* Margem inferior */
            color: #b41111;
            font-weight: bold;
        }

        .user h2 {
            font-size: 20px;
            margin-bottom: 10px;
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

        .btn-group {
            margin-top: 1.5rem;
            /* Espaçamento superior para o grupo de botões */
        }

        .btn-dark {
            background-color: #343a40;
            /* Cor de fundo dos botões */
            color: #fff;
            /* Cor do texto dos botões */
            border-color: #343a40;
            /* Cor da borda dos botões */
        }

        .btn-dark:hover {
            background-color: #212529;
            /* Cor de fundo dos botões ao passar o mouse */
            border-color: #212529;
            /* Cor da borda dos botões ao passar o mouse */
        }

        .btn-primary {
            background-color: #007bff;
            /* Cor de fundo do botão primário */
            border-color: #007bff;
            /* Cor da borda do botão primário */
        }

        .btn-primary:hover {
            background-color: #0069d9;
            /* Cor de fundo do botão primário ao passar o mouse */
            border-color: #0062cc;
            /* Cor da borda do botão primário ao passar o mouse */

        }

        .img-escolha{
            height: 50pt; 
            width: 60pt; 
            background-color: white;
            margin-bottom: 10px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div id="temporizador"></div>
    <div class="container">
    
        <div class="user d-flex justify-content-center align-items-center">
            <div class="Conteudo">
                <h1>Pedra Papel e Tesoura</h1>
                <h2>Escolha uma das opções abaixo:</h2>
                @if($token === $token1)
                <form action="{{ route('play1', [$token]) }}" method="post">
                    @elseif($token === $token2)
                    <form action="{{ route('play2', [$token]) }}" method="post">
                        @endif
                    @csrf
                    <div class="btn-group">
                        <button type="submit" name="choice" value="pedra" class="btn btn-white">
                            <img src="{{ asset('img/Pedra.png') }}" alt="pedra" class="img-escolha">
                        </button>
                        <button type="submit" name="choice" value="papel" class="btn btn-white">
                            <img src="{{ asset('img/Papel.png') }}" alt="papel" class="img-escolha">
                        </button>
                        <button type="submit" name="choice" value="tesoura" class="btn btn-white">
                            <img src="{{ asset('img/Tesoura.png') }}" alt="tesoura" class="img-escolha">
                        </button>
                    </div>
                </form>
            </div>
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
