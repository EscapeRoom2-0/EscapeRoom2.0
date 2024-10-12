<?php
$token1 = session('game_token1');
$token2 = session('game_token2');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogo de Portas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        /* Estilos para o corpo da página */
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
        
        h1 {
            justify-content: center;
            align-items: center;
            font-weight: bold;
            font-family: Courier;
            font-size: 60px;
            margin-bottom: 30px;
            color: #b41111;
            width: 1000px;
            display: block;
        }

        #subtitle{
            margin-bottom: 50px
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

        .portas {
            display: inline-block;
            margin: 50px 20px 0px 20px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            transition: opacity 0.3s;
        }

        .portas:hover {
            opacity: 0.8;
        }

        .portas:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .result {
            display: none;
            font-size: 20px;
            margin-top: 20px;
            color: red;
        }

        #timer-container {
            position: fixed;
            top: 10px;
            left: 10px;
            background-color: #000;
            color: #ff0000;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 1.2em;
            border: 2px solid #ff0000;
        }

        #controls {
            position: fixed;
            top: 40px;
            left: 10px;
            background-color: #000;
            color: #ff0000;
            padding: 5px;
            border-radius: 5px;
            font-size: 0.9em;
        }

        #controls button {
            background-color: #ff0000;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        #controls button:hover {
            background-color: #cc0000;
        }

        .acessarsenha {
            color: white;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div id="temporizador"></div>
    <div class="container">
        <h1>Jogo das Portas</h1>
        <p id="subtitle">Você deve abrir 3 portas seguidas sem encontrar uma criatura hostil. Boa sorte!</p>
        <!-- Imagens das portas -->
        <div id="portas">
            <img id="porta1" class="portas" src="{{ asset('img/porta-fechada.png') }}" onclick="selecionarPorta(1)" alt="Porta 1" height="300pt" width="150pt">
            <img id="porta2" class="portas" src="{{ asset('img/porta-fechada.png') }}" onclick="selecionarPorta(2)" alt="Porta 2" height="300pt" width="150pt">
            <img id="porta3" class="portas" src="{{ asset('img/porta-fechada.png') }}" onclick="selecionarPorta(3)" alt="Porta 3" height="300pt" width="150pt">
        </div>

        <div id="resultado" class="result"></div>      
    </div>

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

    <!-- Código do Jogo de Portas -->
    <script>
        var token = "{{$token}}";
        var token1 = "{{$token1}}";
        var token2 = "{{$token2}}";
        let rodada = 1;
        const totalRodadas = 3;
        let segundos = 60;

        function selecionarPorta(numeroPorta) {
            const portaHostil = Math.floor(Math.random() * 3) + 1; // Porta aleatória com criatura hostil
            const portaSelecionada = document.getElementById(`porta${numeroPorta}`); // Porta clicada

            if (!portaSelecionada.disabled) { // Verifica se as portas estão funcionando
                if (numeroPorta === portaHostil) {
                    // Porta hostil encontrada
                    portaSelecionada.src = "{{ asset('img/porta-aberta.png') }}"; // Atualiza a imagem para uma criatura hostil
                    document.getElementById('resultado').style.display = 'block';
                    document.getElementById('resultado').innerHTML = '<p style="color: red;">Você encontrou uma criatura hostil! O jogo será reiniciado em 3 segundos.</p>';
                    desabilitarPortas();
                    setTimeout(reiniciarJogo, 3000); // Pausa por 3 segundos e reinicia o jogo
                } else {
                    // Porta segura
                    portaSelecionada.src = "{{ asset('img/porta-aberta.png') }}"; // Atualiza a imagem para uma porta segura
                    document.getElementById('resultado').style.display = 'block';
                    document.getElementById('resultado').innerHTML = '<p style="color: green;">Porta segura! Continue para a próxima rodada.</p>';
                    desabilitarPortas();
                    setTimeout(() => {
                        // Avança para a próxima rodada
                        if (rodada < totalRodadas) {
                            rodada++;
                            habilitarPortas();
                            resetarPortas();
                            document.getElementById('resultado').innerHTML = '';
                        } else {
                            if(token === token1){
                            document.getElementById('resultado').innerHTML = '<p style="color: green;">Parabéns! Você venceu o jogo! <a class="acessarsenha" href="{{ route('passwords.passwordacess', [$token]) }}">Acessar Senha</a></p>';
                            pararTemporizador();    
                        }else{
                            document.getElementById('resultado').innerHTML = '<p style="color: green;">Parabéns! Você venceu o jogo! <a class="acessarsenha" href="{{ route('passwords.passwordacess2', [$token]) }}">Acessar Senha</a></p>';
                            pararTemporizador();   
                        }
                             // Para o temporizador se vencer o jogo
                        }
                    }, 2000); // Avança após 2 segundos
                }
            }
        }

        function desabilitarPortas() {
            document.getElementById('porta1').disabled = true;
            document.getElementById('porta2').disabled = true;
            document.getElementById('porta3').disabled = true;
        }

        function habilitarPortas() {
            document.getElementById('porta1').disabled = false;
            document.getElementById('porta2').disabled = false;
            document.getElementById('porta3').disabled = false;
        }

        function resetarPortas() {
            document.getElementById('porta1').src = "{{ asset('img/porta-fechada.png') }}";
            document.getElementById('porta2').src = "{{ asset('img/porta-fechada.png') }}";
            document.getElementById('porta3').src = "{{ asset('img/porta-fechada.png') }}";
        }

        function reiniciarJogo() {
            rodada = 1;
            segundos = 60;
            habilitarPortas();
            resetarPortas();
            document.getElementById('resultado').innerHTML = '';
        }
    </script>

</body>
</html>
