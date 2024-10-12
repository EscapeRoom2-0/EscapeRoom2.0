<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Login</title>
    <!-- Link para o CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        /* Remover margens e padding padrão */
        * {
            margin: 0;
            padding: auto;
        }

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

        /* Estilos para o título h1 */
        h1 {
            justify-content: center;
            align-items: center;
            font-size: 60px;
            margin-bottom: 150px;
            color: #ed1111;
            font-family: Courier;
            width: 1000px;
            display: block;
        }

        /* Estilos para o parágrafo p */
        p {
            font-size: 18px;
        }

        /* Estilos para a div user */
        .user {
            display:flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-top: 5%;
            width: 300px;
            height: 200px;
        }

        /* Estilos para a div cont */
        .cont {
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        /* Estilos para os links na div cont */
        .cont a {
            text-decoration: none;
            font-size: 30px;
            color: #ffffff;
            /*background: linear-gradient(to left top, blue, red);*/
            padding: 10pt;
            border-radius: 5pt;
            margin-top: 25%;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #ba1111;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        /* Estilos para os links na div cont ao passar o mouse */
        .cont a:hover {
            border-style: solid;
            border-width: 2px;
            background-color: #cf1406;
        }

        /* Estilos para os links na div cont ao serem clicados */
        .cont a:active {
            border-style: solid;
            border-width: 2px;
            background-color: #de2b10;
        }

        /* Estilos para a div loading */
        .loading {
            display: none;
            margin-top: 20px;
            font-size: 24px;
        }

        /* Estilos para os elementos dot */
        .dot {
            color: rgba(0,0,0,0);
            display: inline-block;
            width: 8px;
            height: 8px;
            margin: 0 2px;
            background-color: rgba(0,0,0,0);
            border-radius: 50%;
            animation: loading 1.5s infinite;
        }

        /* Animação de piscar para os pontos */
        @keyframes loading {
            0% { opacity: 0; }
            50% { opacity: 1; }
            100% { opacity: 0; }
        }

        /* Delay de animação para os pontos */
        .dot:nth-child(1) {
            animation-delay: 0s;
            color: white;
        }

        .dot:nth-child(2) {
            animation-delay: 0.5s;
            color: white;
        }

        .dot:nth-child(3) {
            animation-delay: 1s;
            color: white;
        }

    </style>

</head>
<body>
<div id="temporizador"></div>
    <div class="container d-flex justify-content-center align-items-center vh-10">
        <div class="user text-center">
            <form action="{{ route('acessar.submit') }}" method="post">
            @csrf
                <div class="mb-4">
                    <label for="password" class="form-label"><h2 class="text-2xl font-bold text-center">Digite a Senha:</h2></label>
                    <input type="password" id="password" name="password" class="form-control mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                </div>
                <div>
                    <button type="submit" class="btn text-white font-bold py-2 px-4 rounded-md">Entrar</button>
                </div>
            </form>
              
            <a href="{{ route('criartoken2') }}" class="forgot mt-3 d-block" onclick=" delayRedirect(event)">Esqueceu a senha?</a>
            <!-- Indicador de carregamento com três pontinhos -->
            <div class="loading mt-4" id="loading" style="display: none;">
                <span class="dot">.</span>
                <span class="dot">.</span>
                <span class="dot">.</span>
            </div>
        </div>
    </div>
    <!-- Link para o arquivo JavaScript -->
    <script src="js/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
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
            
        
                window.location.href = "{{ route('temporizador.TempoAcabado2') }}";
            
            }
        }

        // Função para sincronizar o tempo com o servidor via AJAX
        function verificarTempoServidor() {
            $.ajax({
                url: "{{ route('temporizador.obterTempo2') }}", // Rota para obter o tempo do servidor
                method: 'GET',
                success: function(response) {
                    tempoRestanteServidor = response.tempoRestante; // Recebe o tempo atualizado do servidor
                    
                    // Se a diferença entre o tempo local e o do servidor for >= 10, ajusta o tempo local
                    var diferenca = Math.abs(tempoRestanteLocal - tempoRestanteServidor);
                    if (diferenca >= diferencaMaxima || diferenca <= diferencaMaxima) {
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
            // Recupera o tempo inicial do servidor
            $.ajax({
                url: "{{ route('temporizador.obterTempo2') }}", // Rota para obter o tempo do servidor
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
