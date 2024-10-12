

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Login</title>
    <!-- Link para o CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Link para o arquivo CSS personalizado -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
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

        /* Texto do formulário */
        .form-label {
            font-size: 60px;
            color: white;
            font-family: Courier;
            width: 600px;
        }

        /* Caixa do formulário */
        .form-control {
            border-color: red; 
            margin-bottom: 100px;
        }

        #password {
            background-color: transparent; /* Deixa o fundo transparente */
            color: white; /* Texto branco */
            border: none; /* Borda branca */
            padding: 10px;
            outline: none;
            text-align: center; /* Centraliza o texto horizontalmente */
            line-height: 1.5; /* Ajusta o espaçamento vertical */
        }

        #password::placeholder {
            color: rgba(255, 255, 255, 0.7); /* Placeholder branco com opacidade */
            text-align: center; /* Centraliza o placeholder também */
        }

        #password {
            font-family: 'Courier', sans-serif;
            letter-spacing: 3px; /* Espaçamento entre os caracteres */
        }

        /* Substituir o estilo dos caracteres no input type="password" */
        #password[type="password"] {
            font-family: 'password-override', sans-serif;
        }

        /* Botão */
        .btn {
            font-family: Courier;
            font-size: 30px;
            background-color: #ba1111; 
        }

        /* Botão com mouse por cima */
        .btn:hover {
            background-color: #cf1406; 
            border: solid;
            border-width: 2px;
        }

        /* NÂO FUNCIONA N SEI PQ */
        .btn:active {
            color: #eeff00; 
            border: solid;
            border-width: 2px;
        }

        .text-danger {
            color: orange; 
        }
        .text-danger:hover {
            color: green; 
        }
        .loading .dot {
            color: white; 
        }

        /* Esqueci a senha */
        .forgot{
            font-size: 18px;
            font-style: italic;
            color: #ba1111;
        }

        /* Esqueci a senha com mouse por cima */
        .forgot:hover{
            color: #cf1406;
            text-decoration: underline;
        }

        /* Esqueci a senha quando apertado */
        .forgot:active{
            color:#de2b10;
            text-decoration: underline;
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
                    <label for="password" class="form-label">Digite a Senha:</label>
                    <input type="password" id="password" name="password" class="form-control mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500" placeholder="Digite aqui">
                </div>
                <div>
                    <button type="submit" class="btn text-white font-bold py-2 px-4 rounded-md">Entrar</button>
                </div>
            </form>
              
            <a href="{{ route('criartoken') }}" class="forgot mt-3 d-block" onclick=" delayRedirect(event)">Esqueceu a senha?</a>
            <!-- Indicador de carregamento com três pontinhos -->
            <div class="loading mt-4" id="loading" style="display: none;">
                <span class="dot">.</span>
                <span class="dot">.</span>
                <span class="dot">.</span>
            </div>
        </div>
    </div>
    <!-- Script para o Temporizador -->
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
        
        window.location.href = "{{ route('temporizador.TempoAcabado') }}"; // Redireciona
    
        }
    }

    // Função para sincronizar o tempo com o servidor via AJAX
    function verificarTempoServidor() {
        $.ajax({
            url: "{{ route('temporizador.obterTempo1') }}", // Rota para obter o tempo do servidor
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
            url: "{{ route('temporizador.obterTempo1') }}", // Rota para obter o tempo do servidor
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

    // Inicia o temporizador ao carregar a página (Desativei pra fazer o front-end)
    iniciarTemporizador();
</script>

</body>
</html>
