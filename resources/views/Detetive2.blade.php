<?php
$token1 = session('game_token1');
$token2 = session('game_token2');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encontre o Item</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: rgb(70,70,70);
            color: white;
            font-family: Arial, sans-serif;
            width: 100%;
        }
        
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

        .container{
            width: 85%;
            height: 85%;  
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: rgba(0,0,0,0.5);
            margin-top: 40px;
            border-radius: 10px;
        }

        #gameContainer {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: top;
            height: 750px;
            margin-bottom: 30px;
        }

        #gameContainer img{
            /*display:none;*/
            width: auto;
            height: 100%;
        }

        .item {
            position: absolute;
            width: 60px;
            height: 60px;
            cursor: pointer;
        }

        #item1 { top: 40%; left: 65%; }
        #item2 { top: 85%; left: 90%; }

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

        .header {
            color: #b41111;
            font-weight: bold;
            text-align: center;
            width: 100%;
            position: flex;
            margin-bottom: 10px;
        }

        .header h1{
            font-family: Courier;
            font-size: 50px;
            color: #b41111;
            font-weight: bold;
        }

        .header img {
            height: 50px;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <!-- Temporizador -->
    <div id="tempo"></div>
    <div class="container">
        <!-- Cabeçalho -->
        <div class="header">
            <h1>Encontre os Itens na cena do Crime:</h1>
            <img src="{{ asset('img/Casinha.webp') }}" alt="Casinha">
            <img src="{{ asset('img/Pote.webp') }}" alt="Pote">
        </div>
        
        <!-- Conteúdo Principal -->
        <div id="gameContainer">
            <img src="{{ asset('img/Cena_do_crime_caso_6.webp') }}" alt="Cena do Crime">
            <div id="item1" class="item">
                <img src="{{ asset('img/Casinha.webp') }}" alt="Casinha">
            </div>
            <div id="item2" class="item">
                <img src="{{ asset('img/Pote.webp') }}" alt="Pote">
            </div>
        </div>
    </div>
    <!-- Tela de Senha -->
    <div id="passwordScreen">
        <p>Parabéns! Você venceu!</p>
        @if($token === $token1)
        <a href="{{ route('passwords.passwordacess', [$token]) }}">Acessar a Senha</a>
        @elseif($token === $token2)
        <a href="{{ route('passwords.passwordacess2', [$token]) }}">Acessar a Senha</a>
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
    <script src="{{ asset('js/Detetive2.js') }}"></script>
</body>
</html>
