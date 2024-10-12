<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    
    public function play1(Request $request)
    {
        $token = session('game_token1');
        
        // Recebe a escolha do usuário do formulário
        $userChoice = $request->input('choice');
    
        // Lógica para gerar escolha aleatória do computador (pedra, papel ou tesoura)
        $choices = ['pedra', 'papel', 'tesoura'];
        $computerChoice = $choices[array_rand($choices)];
    
        // Determina o resultado do jogo
        if ($userChoice == $computerChoice) {
            $result = 'Empate';
            $userWins = false;
        } elseif (
            ($userChoice == 'pedra' && $computerChoice == 'tesoura') ||
            ($userChoice == 'papel' && $computerChoice == 'pedra') ||
            ($userChoice == 'tesoura' && $computerChoice == 'papel')
        ) {
            $result = 'Você ganhou!';
            $userWins = true;
        } else {
            $result = 'Você perdeu!';
            $userWins = false;
        }
    
        // Redireciona para a rota 'show' com os resultados do jogo
        return view('resultado', compact('token'), [
            'userChoice' => $userChoice,
            'computerChoice' => $computerChoice,
            'result' => $result,
            'userWins' => $userWins,
        ]);
    }
    
    public function play2(Request $request)
    {
        $token = session('game_token2');
        
        // Recebe a escolha do usuário do formulário
        $userChoice = $request->input('choice');
    
        // Lógica para gerar escolha aleatória do computador (pedra, papel ou tesoura)
        $choices = ['pedra', 'papel', 'tesoura'];
        $computerChoice = $choices[array_rand($choices)];
    
        // Determina o resultado do jogo
        if ($userChoice == $computerChoice) {
            $result = 'Empate';
            $userWins = false;
        } elseif (
            ($userChoice == 'pedra' && $computerChoice == 'tesoura') ||
            ($userChoice == 'papel' && $computerChoice == 'pedra') ||
            ($userChoice == 'tesoura' && $computerChoice == 'papel')
        ) {
            $result = 'Você ganhou!';
            $userWins = true;
        } else {
            $result = 'Você perdeu!';
            $userWins = false;
        }
    
        // Redireciona para a rota 'show' com os resultados do jogo
        return view('resultado', compact('token'), [
            'userChoice' => $userChoice,
            'computerChoice' => $computerChoice,
            'result' => $result,
            'userWins' => $userWins,
        ]);
    }
    public function showForm()
    {
        return view('ParOuImpar');
    }

    public function puzzle1()
    {
        $token = session('game_token1');
        return view('puzzle', compact('token'));
    }
    public function puzzle2()
    {
        $token = session('game_token2');
    return view('puzzle', compact('token'));
    }


    // Função para processar o jogo
    public function processGame1(Request $request)
    {
        $token = session('game_token1');

        // Verifica se os dados foram enviados via POST
        $numero_jogador = $request->input('numero_jogador', 0);
        $escolha_jogador = $request->input('escolha_jogador', '');

        // Gera um número aleatório de 1 a 5
        $numero_computador = rand(1, 5);

        // Calcula a soma e o resultado
        $soma = $numero_jogador + $numero_computador;
        $resultado = $soma % 2 == 0 ? 'Par' : 'Ímpar';

        // Verifica se o jogador ganhou ou perdeu
        $ganhou = ($escolha_jogador === 'par' && $resultado === 'Par') || ($escolha_jogador === 'impar' && $resultado === 'Ímpar');

        // Passa os dados para a view de resultados
        return view('ResultadoImparOuPar', compact('ganhou', 'numero_computador', 'soma', 'resultado', 'token'));
    }
    public function processGame2(Request $request)
    {
        $token = session('game_token2');
        // Verifica se os dados foram enviados via POST
        $numero_jogador = $request->input('numero_jogador', 0);
        $escolha_jogador = $request->input('escolha_jogador', '');

        // Gera um número aleatório de 1 a 5
        $numero_computador = rand(1, 5);

        // Calcula a soma e o resultado
        $soma = $numero_jogador + $numero_computador;
        $resultado = $soma % 2 == 0 ? 'Par' : 'Ímpar';

        // Verifica se o jogador ganhou ou perdeu
        $ganhou = ($escolha_jogador === 'par' && $resultado === 'Par') || ($escolha_jogador === 'impar' && $resultado === 'Ímpar');

        // Passa os dados para a view de resultados
        return view('ResultadoImparOuPar', compact('ganhou', 'numero_computador', 'soma', 'resultado', 'token'));
    }
    public function parouimpar1()
    {
        $token = session('game_token1');
        return view('ParOuImpar', compact('token'));
    }
    public function parouimpar2()
    {
        $token = session('game_token2');
    return view('ParOuImpar', compact('token'));
    }
}