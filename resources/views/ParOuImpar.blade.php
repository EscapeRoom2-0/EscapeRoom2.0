<?php

$token1 = session('game_token1');

$token2 = session('game_token2')


?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Par ou Ímpar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        * {
            margin: 0;
            padding: auto;
        }
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
        #btn {
            background-color: #ba1111;
            color: white;
            font-size: 120%;
            border-radius: 5px;
            border: none;
            margin-top: 5%;
        }
        h1 {
            color: #cf1406;
            font-weight: bold;
            font-style: italic;
            font-size: 60px;
        }
    </style>
</head>
<body>
<div id="temporizador"></div>
<div class="container">
    <main>
        <h1>Par ou Ímpar</h1>
        @if($token === $token1)
        <form id="gameForm" action="{{ route('processGame1', [$token]) }}" method="POST" onsubmit="return validateForm()">
            @elseif($token === $token2)
            <form id="gameForm" action="{{ route('processGame2', [$token]) }}" method="POST" onsubmit="return validateForm()">
            @endif
            @csrf
            <p>Escolha um número de 1 a 5</p>
            <input type="number" id="numero_jogador" name="numero_jogador" style="font-size: 13px;" required>
            <select name="escolha_jogador" style="margin-top: 1.5rem; font-size: 17px" required>
                <option value="par">Par</option>
                <option value="impar">Ímpar</option>
            </select>
            <button type="submit" id="btn">Jogar</button>
        </form>
    </main>
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
<script>
    function validateForm() {
        const numeroJogador = document.getElementById('numero_jogador').value;
        if (numeroJogador < 1 || numeroJogador > 5 || isNaN(numeroJogador)) {
            alert('Escolha um número de 1 a 5');
            return false; // Impede o envio do formulário
        }
        return true; // Permite o envio do formulário
    }
</script>
</body>
</html>
