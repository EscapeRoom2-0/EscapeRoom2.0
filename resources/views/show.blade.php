
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Link para o Tailwind CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Painel de Controle - Senhas do Jogo</title>

    <style>
        body {
            background-color: #1c1f26; /* Fundo escuro tático */
            color: #e2e8f0; /* Texto claro */
        }

        .container {
            margin-top: 30px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #63b3ed; /* Azul claro */
        }

        .card {
            background-color: #2d3748; /* Fundo cinza escuro */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .table {
            margin-top: 20px;
        }

        .table thead {
            background-color: #4a5568; /* Fundo cinza escuro */
            color: #e2e8f0; /* Texto claro */
        }

        #temporizador, #temporizador1, #temporizador2 {
            background-color: #2d3748; /* Fundo cinza escuro */
            color: #63b3ed; /* Texto azul claro */
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-size: 18px;
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        .btn-success {
            background-color: #38a169; /* Verde escuro */
            color: white;
        }

        .btn-danger {
            background-color: #e53e3e; /* Vermelho escuro */
            color: white;
        }

        .btn-secondary {
            background-color: #718096; /* Cinza médio */
            color: white;
        }

        footer {
            margin-top: 50px;
            text-align: center;
            color: #a0aec0; /* Cinza claro */
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-200">

    <div class="container mx-auto mt-10">
        <!-- Cabeçalho -->
        <h1 class="text-3xl font-bold">Senhas do Jogo {{ $game_id }}</h1>

        <!-- Exibição das Senhas -->
        <div class="card p-6 mb-4">
            @if(count($passwords) > 0)
                <table class="table-auto w-full">
                    <thead class="bg-gray-700 text-gray-300">
                        <tr>
                            <th class="p-3">ID</th>
                            <th class="p-3">Senha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($passwords as $password)
                            <tr class="bg-gray-800 hover:bg-gray-700 transition duration-200 ease-in-out">
                                <td class="p-3">{{ $password->id }}</td>
                                <td class="p-3">{{ $password->pass }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-center">Nenhuma senha encontrada para este jogo.</p>
            @endif
        </div>

        <!-- Exibição dos Temporizadores -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
            <div id="temporizador" class="p-3">Tempo Total: {{ $tempoRestante }} segundos</div>
            <div id="temporizador1" class="p-3">{{$Jogador1}}: {{ $tempoRestante1 }} segundos</div>
            <div id="temporizador2" class="p-3">{{$Jogador2}}: {{ $tempoRestante2 }} segundos</div>
        </div>

        <!-- Botões para ajustar os tempos -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
            <div class="space-y-2">
                <button onclick="ajustarTempo('total', 10)" class="btn btn-success w-full">+10s Tempo Total</button>
                <button onclick="ajustarTempo('total', -10)" class="btn btn-danger w-full">-10s Tempo Total</button>
            </div>
            <div class="space-y-2">
                <button onclick="ajustarTempo('jogador1', 10)" class="btn btn-success w-full">+10s Jogador 1</button>
                <button onclick="ajustarTempo('jogador1', -10)" class="btn btn-danger w-full">-10s Jogador 1</button>
            </div>
            <div class="space-y-2">
                <button onclick="ajustarTempo('jogador2', 10)" class="btn btn-success w-full">+10s Jogador 2</button>
                <button onclick="ajustarTempo('jogador2', -10)" class="btn btn-danger w-full">-10s Jogador 2</button>
            </div>
        </div>


        <!-- Botão para parar todos os temporizadores -->
        <div class="text-center mt-8">
            <button onclick="pararTodosOsTempos()" class="btn btn-danger w-1/2">Parar Todos os Temporizadores</button>

            <!-- Seleção de Jogador -->
            <form action="{{ route('passwords.gotoform') }}" method="GET" id="Jogador" class="mt-4" target="_blank">
                @csrf
                <label for="Jogador" class="block text-lg font-medium mb-2">Escolher Ganhador:</label>
                <select name="Jogador" id="Jogador" class="form-control w-full bg-gray-800 text-white p-2 rounded">
                    <option value="{{$Jogador1}}">{{$Jogador1}}</option>
                    <option value="{{$Jogador2}}">{{$Jogador2}}</option>
                </select>
                <input type="submit" class="btn btn-success mt-4 w-full"  value="Enviar">
            </form>
        </div>

        <!-- Navegação -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center mt-8">
            <a href="{{ route('passwords.selectGame') }}" class="btn btn-secondary">Voltar</a>
            <a href="{{ route('passwords.resetGame') }}" class="btn btn-secondary">Resetar Computadores</a>
            <a href="{{ route('passwords.resetPasswords') }}" class="btn btn-secondary">Resetar Senhas</a>
            <a href="{{ route('passwords.index') }}" class="btn btn-secondary">Tela Inicial</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-8 text-center">
        <p>&copy; 2024 EscapeRoom</p>
    </footer>

   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        var tempoRestante = {{ $tempoRestante }}; // Define o tempo restante em segundos
        var tempoRestante1 = {{ $tempoRestante1 }};
        var tempoRestante2 = {{ $tempoRestante2 }};
        var intervalo;
        var intervalo1;
        var intervalo2; // Variável para armazenar o intervalo do temporizador


    

        
        // Função para atualizar o temporizador
        function atualizarTemporizador() {
    if (tempoRestante > 0) {
        var elemento = document.getElementById('temporizador');

        // Converte o tempo restante local em minutos e segundos
        var minutos = Math.floor(tempoRestante2 / 60);
                var segundos = tempoRestante % 60;

                // Adiciona zero à esquerda se necessário
                if (segundos < 10) {
                    segundos = "0" + segundos;
                }

                // Atualiza o elemento com o tempo no formato MM:SS

        elemento.innerText ="Tempo Total: " + minutos + ":" + segundos + " minutos restantes";;

        // função ajax para enviar tempo para o Controller
        $.ajax({
            url: "{{ route('temporizador.atualizarTempo') }}", // URL da rota para a função PHP no servidor
            type: "POST", // Método de envio (POST recomendado para segurança)
            data: {
                tempo_restante: tempoRestante, // O tempo restante que será enviado
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log("Tempo enviado com sucesso: " + response.tempo_restante);
            },
            error: function(xhr) {
                console.error("Erro ao enviar tempo: " + xhr.statusText);
            }
        });

        tempoRestante--; // Reduz o tempo localmente
        
        // Atualiza o temporizador a cada 1 segundo
        intervalo = setTimeout(atualizarTemporizador, 1000);

    } else {
        // Redireciona ou exibe mensagem quando o tempo acabar
        document.getElementById('temporizador').innerText = "O tempo acabou!";
        document.getElementById('temporizador1').innerText = "O tempo acabou!";
        document.getElementById('temporizador2').innerText = "O tempo acabou!";
        clearTimeout(intervalo); // Para o temporizador
    }
}

function atualizarTemporizador1() {
    if (tempoRestante1 > 0) {
        var elemento1 = document.getElementById('temporizador1');
        // Converte o tempo restante local em minutos e segundos
        var minutos = Math.floor(tempoRestante1 / 60);
                var segundos = tempoRestante1 % 60;

                // Adiciona zero à esquerda se necessário
                if (segundos < 10) {
                    segundos = "0" + segundos;
                }

                // Atualiza o elemento com o tempo no formato MM:SS
        elemento1.innerText ="{{$Jogador1}}: " + minutos + ":" + segundos + " minutos restantes";

        // função ajax para enviar tempo para o Controller
        $.ajax({
            url: "{{ route('temporizador.atualizarTempo1') }}", // URL da rota para a função PHP no servidor
            type: "POST", // Método de envio (POST recomendado para segurança)
            data: {
                tempo_restante1: tempoRestante1, // O tempo restante que será enviado
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log("Tempo enviado com sucesso: " + response.tempo_restante1);
            },
            error: function(xhr) {
                console.error("Erro ao enviar tempo: " + xhr.statusText);
            }
        });

        tempoRestante1--; // Reduz o tempo localmente
        
        // Atualiza o temporizador a cada 1 segundo
        intervalo1 = setTimeout(atualizarTemporizador1, 1000); // Corrigido para `atualizarTemporizador1`

    } else {
         // Quando o tempo acabar, atribui o valor de `tempoRestante` ao `tempoRestante2`
         tempoRestante1 = tempoRestante - 10;  // Aqui ele recebe o valor do tempo geral

    

        // Reinicia o temporizador para o jogador 2
        intervalo1 = setTimeout(atualizarTemporizador1, 1000);
    }
}

function atualizarTemporizador2() {
    if (tempoRestante2 > 0) {
        var elemento2 = document.getElementById('temporizador2');
                
                // Converte o tempo restante local em minutos e segundos
                var minutos = Math.floor(tempoRestante2 / 60);
                var segundos = tempoRestante2 % 60;

                // Adiciona zero à esquerda se necessário
                if (segundos < 10) {
                    segundos = "0" + segundos;
                }

                // Atualiza o elemento com o tempo no formato MM:SS
                elemento2.innerText = "{{$Jogador2}}: " + minutos + ":" + segundos + " minutos restantes";

        // função ajax para enviar tempo para o Controller
        $.ajax({
            url: "{{ route('temporizador.atualizarTempo2') }}", // URL da rota para a função PHP no servidor
            type: "POST", // Método de envio (POST recomendado para segurança)
            data: {
                tempo_restante2: tempoRestante2, // O tempo restante que será enviado
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log("Tempo enviado com sucesso: " + response.tempo_restante2);
            },
            error: function(xhr) {
                console.error("Erro ao enviar tempo: " + xhr.statusText);
            }
        });

        tempoRestante2--; // Reduz o tempo localmente
        
        // Atualiza o temporizador a cada 1 segundo
        intervalo2 = setTimeout(atualizarTemporizador2, 1000); // Corrigido para `atualizarTemporizador2`

    } else {
        // Quando o tempo acabar, atribui o valor de `tempoRestante` ao `tempoRestante2`
        tempoRestante2 = tempoRestante - 10;  // Aqui ele recebe o valor do tempo geral

    

        // Reinicia o temporizador para o jogador 2
        intervalo2 = setTimeout(atualizarTemporizador2, 1000);
    }
}
function ajustarTempo(tipo, valor) {
    if (tipo === 'total') {
        tempoRestante += valor; // Ajusta o tempo total
    } else if (tipo === 'jogador1') {
        tempoRestante1 += valor; // Ajusta o tempo do Jogador 1
    } else if (tipo === 'jogador2') {
        tempoRestante2 += valor; // Ajusta o tempo do Jogador 2
    }
            }
            function pararTodosOsTempos() {
            clearTimeout(intervalo); // Para o temporizador geral
            clearTimeout(intervalo1); // Para o temporizador do Jogador 1
            clearTimeout(intervalo2); // Para o temporizador do Jogador 2
            alert("Todos os temporizadores foram parados!");
        }


// Inicia os temporizadores
atualizarTemporizador();
atualizarTemporizador1();
atualizarTemporizador2();

        </script>
</body>
</html>
