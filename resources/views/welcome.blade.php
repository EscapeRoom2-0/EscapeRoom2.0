
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Link para o CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Link para o arquivo CSS personalizado -->
    <!--link rel="stylesheet" href="{{ asset('css/style.css') }}"--->
    <title>Desarme a bomba</title>
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

        /* Estilos para o título h1 */
        h1 {
            justify-content: center;
            align-items: center;
            font-size: 60px;
            margin-bottom: 150px;
            color: #ed1111;
            font-family: Courier;
            font-weight: bold;
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

    <div class="container">
        <!-- Div principal do usuário -->
        <div class="user">
            <h1>Computador Inacessado...</h1>
            <div class="cont">
                <!-- Link que dispara a função de atraso no redirecionamento -->
                <a href="{{ route('temporizador.atualizar') }}" onclick="delayRedirect(event)">Acessar</a>
                <p></p>
                <!-- Indicador de carregamento com três pontinhos -->
                <div class="loading mt-4" id="loading" style="display: none;">
                    <span class="dot">.</span>
                    <span class="dot">.</span>
                    <span class="dot">.</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Link para o arquivo JavaScript -->
    <script src="js/script.js"></script>
</body>
</html>

