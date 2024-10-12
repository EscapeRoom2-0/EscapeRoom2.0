<?php

$token1 = session('game_token1');
$token2 = session('game_token2');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lanterna no Escuro</title>
    <style>
        body {
            margin: 0;
            overflow: hidden;
            background: black;
            height: 100vh;
            font-family: Arial, sans-serif;
        }

        canvas {
            display: block;
            background: black;
        }
        
        header{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: none;
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

        #title {
            position: flex;
            background-color: rgba(0, 0, 0, 0.7); /* Fundo semitransparente para contraste */
            color: white;
            padding: 10px; /* Adiciona preenchimento */
            border-radius: 5px; /* Borda arredondada */
            font-size: 32px;
            font-weight: bold;
            background-color: rgba(0, 0, 0, 0.7); /* Fundo semitransparente */
        }

        #miniTimer {
            position: flex;
            top: 50px;
            color: white;
            font-size: 16px;
            font-weight: bold;
        }
        
        .endScreen {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgb(30,30,30);
            padding: 20px;
            border-radius: 10px;
            border: 4px solid #ba1111;
            text-align: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        .endScreen button {
            background-color: #ba1111;
            color: white;
            font-size: 20px;
            padding: 10px 20px;
            margin-top: 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .endScreen button:hover {
            background-color: #333333;
        }
    </style>
</head>
<body>
    <header>
        <div id="title">Encontre o Troféu</div>
        <div id="miniTimer">Respawn Objetivo: 3</div>
        <div id="temporizador"></div>
    </header>
    <div id="winScreen" class="endScreen">
        Você Ganhou!
        <p>Obrigado por jogar</p>
        <button onclick="Ganhar()">Ir Para Senha</button>
    </div>
    <div id="loseScreen" class="endScreen">
        Você Perdeu!
        <button onclick="restartGame()">Tentar Novamente</button>
    </div>
    <canvas id="gameCanvas"></canvas>
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
    <script>
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');
        const tituloElement = document.getElementById('title');
        const temporizadorElement = document.getElementById('temporizador');
        const miniTimerElement = document.getElementById('miniTimer');
        const winScreen = document.getElementById('winScreen');
        const loseScreen = document.getElementById('loseScreen');

        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        const lightRadius = 100;
        const obstacles = [];
        const numObstacles = 6;
        let miniTimerInterval;
        let miniTime = 1;
        let trofeu = { x: canvas.width / 2, y: canvas.height / 2, size: 30 };

        // Usando a função asset() do Laravel para carregar a imagem do troféu
        let trofeuImage = new Image();
        trofeuImage.src = 'https://img.freepik.com/vetores-gratis/trofeu_78370-345.jpg'; // Ajuste o caminho conforme a sua estrutura de arquivos

        let gameActive = true;

        var token = "{{ $token }}";
        var token1 = "{{ $token1 }}";
        var token2 = "{{ $token2 }}";

        function drawLight(x, y) {
            if (!gameActive) return;

            ctx.clearRect(0, 0, canvas.width, canvas.height);
            const gradient = ctx.createRadialGradient(x, y, 0, x, y, lightRadius);
            gradient.addColorStop(0, 'rgba(255, 255, 255, 0.8)');
            gradient.addColorStop(1, 'rgba(0, 0, 0, 0)');
            ctx.fillStyle = gradient;
            ctx.beginPath();
            ctx.arc(x, y, lightRadius, 0, Math.PI * 2);
            ctx.fill();

            // Draw obstacles
            for (let i = 0; i < obstacles.length; i++) {
                const obs = obstacles[i];
                if (isInLight(obs.x, obs.y, obs.size)) {
                    ctx.drawImage(obs.image, obs.x - obs.size / 2, obs.y - obs.size / 2, obs.size, obs.size);
                    setTimeout(() => {
                        obs.x = Math.random() * canvas.width;
                        obs.y = Math.random() * canvas.height;
                    }, 1000); // Teleport after 1 second
                }
            }

            // Draw trophy
            if (isInLight(trofeu.x, trofeu.y, trofeu.size)) {
                ctx.drawImage(trofeuImage, trofeu.x - trofeu.size / 2, trofeu.y - trofeu.size / 2, trofeu.size, trofeu.size);
            }
        }

        function isInLight(x, y, size) {
            const dx = x - mouseX;
            const dy = y - mouseY;
            return Math.sqrt(dx * dx + dy * dy) < lightRadius + size / 2;
        }

        function createObstacles() {
            for (let i = 0; i < numObstacles; i++) {
                const img = new Image();
                img.src = getRandomObstacleImage(); // Function to get random obstacle image URL
                const size = 100; // Default size
                obstacles.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height,
                    size: size,
                    image: img
                });
            }
        }

        function getRandomObstacleImage() {
            const images = [
                'https://t.ctcdn.com.br/YcF52xOzwnbw9EJM2IP7OfbLvXM/87x0:1199x626/640x360/smart/i333696.jpeg',
                'https://play-lh.googleusercontent.com/qBiLTYKuDA9aecK01rKoBYMp19lLOSq3xJvLkjTxlLCOJ_blR9ZPvBUblRaKFbDQ8P29',
                'https://preview.redd.it/i-just-noticed-that-golden-freddys-jumpscare-in-fnaf-1-is-v0-bheqfjmhotdb1.png',
                'https://i.pinimg.com/236x/6e/cc/03/6ecc0336ac101815a2cb107e7e797341.jpg',
                'https://tm.ibxk.com.br/ms/images/highlights/000/045/159/42639.jpg',
                'https://i.ytimg.com/vi/RNoHcWE8tbQ/maxresdefault.jpg'
            ];
            return images[Math.floor(Math.random() * images.length)];
        }

    

        function updateMiniTimer() {
            miniTime--;
            miniTimerElement.textContent = `Respawn Objetivo: ${miniTime}`;
            if (miniTime <= 0) {
                resetMiniTimer();
            }
        }

        function resetMiniTimer() {
            miniTime = 3;
            miniTimerElement.textContent = `Respawn Objetivo: ${miniTime}`;
            // Hide and reposition the trophy
            trofeu.x = Math.random() * canvas.width;
            trofeu.y = Math.random() * canvas.height;
            setTimeout(() => trofeuImage.src = 'https://img.freepik.com/vetores-gratis/trofeu_78370-345.jpg', 3000); // Reset the trophy image after 3 seconds
        }

        function startGame() {
            createObstacles();
            miniTimerInterval = setInterval(updateMiniTimer, 1000);
            gameActive = true;
        }

        function restartGame() {
            winScreen.style.display = 'none';
            loseScreen.style.display = 'none';
            miniTime = 3;
            startGame();
        }

        let mouseX = canvas.width / 2;
        let mouseY = canvas.height / 2;
        canvas.addEventListener('mousemove', (event) => {
            if (!gameActive) return;
            mouseX = event.clientX;
            mouseY = event.clientY;
            drawLight(mouseX, mouseY);
        });

        canvas.addEventListener('click', () => {
            if (!gameActive) return;
            if (isInLight(trofeu.x, trofeu.y, trofeu.size)) {
                winScreen.style.display = 'block';
                gameActive = false;
            }
        });

        function Ganhar(){
            if(token === token1){
                window.location.href ="{{ route('passwords.passwordacess', [$token]) }}";
            }else{
                window.location.href =" {{ route('passwords.passwordacess2', [$token]) }}";
            }
        };

        startGame();
    </script>
</body>
</html>
