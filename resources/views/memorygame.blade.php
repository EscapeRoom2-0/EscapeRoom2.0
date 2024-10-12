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
    
    <title>Jogo da memória</title>
    <style>
        *{
            margin: 0;
            padding: 0;
        }
        body{
            background: rgb(70,70,70);
            text-align: center;
            display:flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            width: 100%;
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
        
        #main-container{
            background: rgba(0,0,0,0.5);
            height: 85%;
            width: 70%; 
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin-top: 50px;
            border-radius: 10px; 
            position: relative;
        }

        #container{
            height: 502px; /* Altura ajustada para caber 2 cartas por coluna */
            width: 840px; /* Largura ajustada para caber 5 cartas por linha */
            display:flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
            margin-bottom: 80px;
            border: 4px solid black;
            border-radius: 10px;
            background-color: rgb(75, 83, 83);
        }

        #text2{
            color: white;
            font-family: Courier;
            font-weight: bold;
            border-radius: 10px;
            font-size: 60px;
            margin: 40px 25% 5% 25%;
            padding-bottom: 10px;
            position: relative;
        }

        .card{
            width: 160px;
            height: 240px;
            position: absolute;
            border-radius: 10px;
            perspective: 600px;
        }

        .face{
        width: 100%;
        height: 100%;
        position: absolute;
        border-radius: 10px;
        transition: all 1s;
        backface-visibility: hidden;
        }

        .back{
            background-image: url("{{ asset('img/verso.jpg') }}");
            background-size: 160px 240px;
        }

        .back.flipped{
            transform: rotateY(180deg);
        }

        .front{
            transform: rotateY(-180deg);
        }

        .front.flipped{
            transform: rotateY(0deg);
        }

        .card:hover{
            box-shadow: 0 0 10px rgb(0, 0, 0);
        }

        #modalGameOver{
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

        #modalGameOver a {
            text-decoration: none;
            color: white;
            font-size: 30px;
            background-color: #b41111;
            padding: 10px 20px;
            border-radius: 5px;
        }

        #modalGameOver a:hover {
            text-decoration: underline;
        }

        img{
            margin-top: 3%;
        }

        .face.flipped.match{
            box-shadow: 0 0 10px rgb(255, 0, 0);
        }

        #imgMatchSign{
            position: relative;
            top: 10px;
            bottom: 1px;
            width: 400px;
            height: 400px;
            transition-property: top, opacity;
            transition-duration: 1.5s;
            opacity: 1;
            z-index: -1;
        }
    </style>
</head>
<body>
    <div id="temporizador"></div>
    <div id="main-container">
        <h2 id="text2">Jogo Da Memória!</h2>

        <div id="container">
            <div class="card" id="card0">
                <div class="face back"></div>
                <div class="face front"></div>
            </div>

            <div class="card" id="card1">
                <div class="face back"></div>
                <div class="face front"></div>
            </div>

            <div class="card" id="card2">
                <div class="face back"></div>
                <div class="face front"></div>
            </div>

            <div class="card" id="card3">
                <div class="face back"></div>
                <div class="face front"></div>
            </div>

            <div class="card" id="card4">
                <div class="face back"></div>
                <div class="face front"></div>
            </div>

            <div class="card" id="card5">
                <div class="face back"></div>
                <div class="face front"></div>
            </div>
            <div class="card" id="card6">
                <div class="face back"></div>
                <div class="face front"></div>
            </div>
            <div class="card" id="card7">
                <div class="face back"></div>
                <div class="face front"></div>
            </div>
            <div class="card" id="card8">
                <div class="face back"></div>
                <div class="face front"></div>
            </div>
            <div class="card" id="card9">
                <div class="face back"></div>
                <div class="face front"></div>
            </div>

            <img src="{{ asset('img/match.png') }}" alt="match" id="imgMatchSign">

        </div>
    </div>
    <div id="modalGameOver" style="display:none;">
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
        (function() { // Função anônima para manter as variáveis dentro de um escopo local

        var matches = 0; // Contador de pares encontrados
        var flippedCards = []; // Array que armazena as cartas viradas para comparação
        var modalGameOver = document.querySelector("#modalGameOver"); // Modal de fim de jogo
        var imgMatchSign = document.querySelector("#imgMatchSign"); // Imagem que indica um par encontrado

        // Array de imagens com os rostos dos inventores - 5 pares
        var images = [
            { src: "{{ asset('img/0.jpg') }}", id: 0 },
            { src: "{{ asset('img/0.jpg') }}", id: 0 },
            { src: "{{ asset('img/1.jpg') }}", id: 1 },
            { src: "{{ asset('img/1.jpg') }}", id: 1 },
            { src: "{{ asset('img/2.jpg') }}", id: 2 },
            { src: "{{ asset('img/2.jpg') }}", id: 2 },
            { src: "{{ asset('img/3.webp') }}", id: 3 },
            { src: "{{ asset('img/3.webp') }}", id: 3 },
            { src: "{{ asset('img/4.jpg') }}", id: 4 },
            { src: "{{ asset('img/4.jpg') }}", id: 4 }
        ];

        startGame(); // Inicia o jogo

        // Função que configura o jogo inicial
        function startGame() {
            matches = 0; // Reinicia o contador de pares
            flippedCards = []; // Limpa o array de cartas viradas
            images = randomSort(images); // Embaralha as imagens

            var frontFaces = document.getElementsByClassName("front"); // Faces frontais das cartas
            var backFaces = document.getElementsByClassName("back"); // Faces traseiras das cartas

            // Loop que configura a posição, tamanho e eventos das cartas
            for (var i = 0; i < 10; i++) {
                frontFaces[i].classList.remove("flipped", "match"); // Remove classes de virado ou pareado
                backFaces[i].classList.remove("flipped", "match"); // Remove classes de virado ou pareado

                var card = document.querySelector("#card" + i); // Seleciona a carta correspondente

                // Define a posição da carta no tabuleiro (2 linhas de 5 cartas)
                card.style.left = i % 5 * 165 + 5 + "px";  // Posição horizontal (5 cartas por linha)
                card.style.top = i < 5 ? 5 + "px" : 250 + "px"; // 2 linhas de cartas

                card.addEventListener("click", flipcard, false); // Adiciona evento de clique para virar a carta

                // Define a imagem da face frontal da carta
                frontFaces[i].style.background = "url('" + images[i].src + "')";
                frontFaces[i].setAttribute("id", images[i].id); // Atribui um ID para a carta
            }

            // Mostrar as cartas por 3 segundos para o jogador memorizar
            setTimeout(function() {
                showAllCards();  // Vira todas as cartas
                setTimeout(hideAllCards, 3000);  // Esconde após 3 segundos
            }, 500);
        }

        // Função para mostrar todas as cartas
        function showAllCards() {
            var frontFaces = document.getElementsByClassName("front");
            var backFaces = document.getElementsByClassName("back");

            for (var i = 0; i < 10; i++) {
                frontFaces[i].classList.add("flipped"); // Mostra a face frontal
                backFaces[i].classList.add("flipped"); // Esconde a face traseira
            }
        }

        // Função para esconder todas as cartas
        function hideAllCards() {
            var frontFaces = document.getElementsByClassName("front");
            var backFaces = document.getElementsByClassName("back");

            for (var i = 0; i < 10; i++) {
                frontFaces[i].classList.remove("flipped"); // Esconde a face frontal
                backFaces[i].classList.remove("flipped"); // Mostra a face traseira
            }
        }

        // Função para embaralhar as imagens de forma aleatória
        function randomSort(oldArray) {
            var newArray = []; // Novo array que será retornado embaralhado

            // Loop para embaralhar o array original
            while (newArray.length !== oldArray.length) {
                var i = Math.floor(Math.random() * oldArray.length); // Gera um índice aleatório

                if (newArray.indexOf(oldArray[i]) < 0) { // Verifica se a imagem já foi inserida
                    newArray.push(oldArray[i]); // Adiciona a imagem ao novo array
                }
            }

            return newArray; // Retorna o array embaralhado
        }

        // Função que lida com o evento de virar as cartas
        function flipcard() {
            if (flippedCards.length < 2) { // Se menos de 2 cartas estão viradas
                var faces = this.getElementsByClassName("face"); // Obtém as faces da carta clicada

                if (faces[0].classList.length > 2) { // Se a carta já está virada ou pareada, retorna
                    return;
                }

                faces[0].classList.toggle("flipped"); // Vira a face frontal
                faces[1].classList.toggle("flipped"); // Vira a face traseira

                flippedCards.push(this); // Adiciona a carta ao array de cartas viradas

                // Se duas cartas estão viradas, verifica se formam um par
                if (flippedCards.length === 2) {
                    if (flippedCards[0].childNodes[3].id === flippedCards[1].childNodes[3].id) { // Compara os IDs das cartas
                        flippedCards[0].childNodes[1].classList.toggle("match"); // Marca a primeira carta como pareada
                        flippedCards[0].childNodes[3].classList.toggle("match"); // Marca a segunda carta como pareada
                        flippedCards[1].childNodes[1].classList.toggle("match");
                        flippedCards[1].childNodes[3].classList.toggle("match");

                        matchCardSign(); // Mostra o sinal de par encontrado
                        matches++; // Incrementa o contador de pares
                        flippedCards = []; // Limpa o array de cartas viradas

                        // Se todos os pares foram encontrados, termina o jogo
                        if (matches === 5) {  // Agora precisa de 5 pares para vencer
                            gameOver();
                        }
                    }
                }
            } else { // Se mais de duas cartas estão viradas, vira as duas primeiras de volta
                flippedCards[0].childNodes[1].classList.toggle("flipped");
                flippedCards[0].childNodes[3].classList.toggle("flipped");
                flippedCards[1].childNodes[1].classList.toggle("flipped");
                flippedCards[1].childNodes[3].classList.toggle("flipped");

                flippedCards = []; // Limpa o array de cartas viradas
            }
        }

        // Função que mostra o modal de fim de jogo
        function gameOver() {
            document.getElementById('modalGameOver').style.display = 'block';
        }

        // Função que mostra um sinal visual de que um par foi encontrado
        function matchCardSign() {
            imgMatchSign.style.zIndex = 1; // Traz o sinal para a frente
            imgMatchSign.style.top = 150 + "px"; // Ajusta a posição
            imgMatchSign.style.opacity = 0; // Esconde o sinal inicialmente

            // Após 1,5 segundos, esconde o sinal novamente
            setTimeout(function () {
                imgMatchSign.style.zIndex = -1; // Esconde o sinal
                imgMatchSign.style.top = 250 + "px"; // Ajusta a posição original
                imgMatchSign.style.opacity = 1; // Mostra novamente para futuros pares
            }, 1500);
        }

        })();
    </script>
</body>
</html>
