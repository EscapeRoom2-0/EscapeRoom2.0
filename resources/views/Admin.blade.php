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
            width: 1000px;
            display: block;
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
        .btn {
            background-color: #ba1111; /* Dark grey button background */
            border: none;
        }
        .btn:hover {
            background-color: #cf1406; /* Slightly lighter grey on hover */
        }
        .text-danger {
            color: #555555; /* Dark grey for the forgot password link */
        }
        .text-danger:hover {
            color: #333333; /* Darker grey on hover */
        }
        .loading .dot {
            color: #333333; /* Dark grey for loading dots */
        }
        .forgot{
            color: red;
            text-decoration: none;
        }
        .forgot:hover{
            text-decoration: underline;
        }
        .forgot:active{
            color:rgba(255, 255, 255, 0);
            text-decoration: none;
        }
    </style>

</head>
<body>
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
              
            <!-- Indicador de carregamento com três pontinhos -->
            <div class="loading mt-4" id="loading" style="display: none;">
                <span class="dot">.</span>
                <span class="dot">.</span>
                <span class="dot">.</span>
            </div>
        </div>
    </div>

</body>
</html>
