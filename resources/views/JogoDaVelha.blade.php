<?php
$token1 = session('game_token1');
$token2 = session('game_token2');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogo da Velha</title>
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
        *{
          padding: 0;
          margin: 0;
          box-sizing: border-box;
        }
        
        :root {
          --blue: #0099ff;
          --winning-blocks: #008000;
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
          font-size: 70px;
          text-transform: uppercase;
          color: #cf1406;
          margin-top: -100px;
          margin-bottom: 80px;
        }

        #gameboard {
          width: min(95%, 450px);
          max-width: 100%;
          display: grid;
          grid-template-columns: repeat(3, 1fr);
          margin-top: 50px;
        }
        
        #scoreboard {
          margin-top: 40px;
          display: flex;
          width: 50%;
          justify-content: space-between;
          font-size: 1.2rem;
          color: #fff;
          width: min(95%, 200px);
          max-width: 100%;
        }
        
        .box {
            font-family: Arial;
            height: 150px;
            width: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 120px;
            border-right: 2px solid;
            border-bottom: 2px solid;
        }
        
        .box:nth-child(3n) {
          border-right: none;
        }
        
        .box:nth-child(6) ~ .box {
          border-bottom: none;
        }
        
        button {
          padding: 10px 20px;
          border-radius: 10px;
          background-color: var(--blue);
          color: #fff;
          border-color: var(--blue);
          font-size: 1.2rem;
          transition: 200ms transform;
          font-weight: 600;
          margin-top: 20px;
        }
        
        button:hover {
          cursor: pointer;
          transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div id="temporizador"></div>
    <div class="container">
        <h1 id="playerText">Jogo da Velha</h1>
        <div id="gameboard">
            <div class="box" id="0"></div>
            <div class="box" id="1"></div>
            <div class="box" id="2"></div>
            <div class="box" id="3"></div>
            <div class="box" id="4"></div>
            <div class="box" id="5"></div>
            <div class="box" id="6"></div>
            <div class="box" id="7"></div>
            <div class="box" id="8"></div>
        </div>
    </div>

    <script>
        const playerText = document.getElementById("playerText");
        const boxes = Array.from(document.getElementsByClassName("box"));
        const O_TEXT = "O";
        const X_TEXT = "X";
        let currentPlayer = X_TEXT;
        let spaces = Array(9).fill(null);
        let isMachinePlaying = false;

        const winningCombos = [
            [0, 1, 2],
            [3, 4, 5],
            [6, 7, 8],
            [0, 3, 6],
            [1, 4, 7],
            [2, 5, 8],
            [0, 4, 8],
            [2, 4, 6],
        ];

        const startGame = () => {
            boxes.forEach((box) => box.addEventListener("click", boxClicked));
        };

        function boxClicked(e) {
            const id = e.target.id;

            if (!spaces[id] && !playerHasWon() && !isDraw() && !isMachinePlaying) {
                spaces[id] = currentPlayer;
                e.target.innerText = currentPlayer;

                if (playerHasWon()) {
                    playerText.innerText = `${currentPlayer} Ganhou!`;
                    const winningBlocks = playerHasWon();
                    winningBlocks.forEach((box) => boxes[box].style.backgroundColor = '#008000');
                    setTimeout(() => {
                        if(token === token1){
                        window.location.href = "{{ route('passwords.passwordacess', [$token]) }}";
                        }else{
                        window.location.href = "{{ route('passwords.passwordacess2', [$token]) }}";
                        }                 
                         // Redireciona após 1 segundo
                    }, 1000);
                } else if (isDraw()) {
                    playerText.innerText = "Empate!";
                    setTimeout(restartGame, 1000); // Reinicia após 1 segundo
                } else {
                    currentPlayer = currentPlayer === X_TEXT ? O_TEXT : X_TEXT;
                    if (currentPlayer === O_TEXT) {
                        isMachinePlaying = true; // A máquina vai jogar
                        setTimeout(computerPlay, 1000);  // Jogada da máquina com atraso
                    }
                }
            }
        }

        function computerPlay() {
            const availableSpaces = getRemainingSpaces();
            let computerChoice;

            // Verifica se a máquina pode vencer
            for (const combo of winningCombos) {
                const [a, b, c] = combo;
                if (spaces[a] === O_TEXT && spaces[b] === O_TEXT && spaces[c] === null) {
                    computerChoice = c;
                } else if (spaces[a] === O_TEXT && spaces[c] === O_TEXT && spaces[b] === null) {
                    computerChoice = b;
                } else if (spaces[b] === O_TEXT && spaces[c] === O_TEXT && spaces[a] === null) {
                    computerChoice = a;
                }
            }

            // Verifica se o jogador pode vencer na próxima jogada e bloqueia
            if (computerChoice === undefined) {
                for (const combo of winningCombos) {
                    const [a, b, c] = combo;
                    if (spaces[a] === X_TEXT && spaces[b] === X_TEXT && spaces[c] === null) {
                        computerChoice = c;
                    } else if (spaces[a] === X_TEXT && spaces[c] === X_TEXT && spaces[b] === null) {
                        computerChoice = b;
                    } else if (spaces[b] === X_TEXT && spaces[c] === X_TEXT && spaces[a] === null) {
                        computerChoice = a;
                    }
                }
            }

            // Se não encontrou um movimento para vencer ou bloquear, joga no primeiro espaço livre
            if (computerChoice === undefined) {
                computerChoice = availableSpaces[0]; // Joga na primeira posição livre
            }

            spaces[computerChoice] = O_TEXT;
            boxes[computerChoice].innerText = O_TEXT;

            if (playerHasWon()) {
                playerText.innerText = "Máquina Venceu!";
                const winningBlocks = playerHasWon();
                winningBlocks.forEach((box) => boxes[box].style.backgroundColor = '#008000');
                setTimeout(restartGame, 1000); // Reinicia após 1 segundo
            } else if (isDraw()) {
                playerText.innerText = "Empate!";
                setTimeout(restartGame, 1000); // Reinicia após 1 segundo
            }

            currentPlayer = X_TEXT; // Volta para o jogador X após a jogada da máquina
            isMachinePlaying = false; // A máquina terminou de jogar
        }

        function playerHasWon() {
            for (const combo of winningCombos) {
                const [a, b, c] = combo;
                if (spaces[a] && spaces[a] === spaces[b] && spaces[a] === spaces[c]) {
                    return combo;
                }
            }
            return false;
        }

        function isDraw() {
            return spaces.every((space) => space !== null);
        }

        function getRemainingSpaces() {
            return spaces.map((space, index) => space === null ? index : null).filter(val => val !== null);
        }

        function restartGame() {
            spaces.fill(null);
            boxes.forEach(box => {
                box.innerText = "";
                box.style.backgroundColor = "";
            });
            playerText.innerText = "Jogo da Velha";
            currentPlayer = X_TEXT; // Reinicia o jogador para X
            isMachinePlaying = false; // Reinicia a máquina
            startGame(); // Reinicia o jogo
        }

        startGame();
    </script>
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
