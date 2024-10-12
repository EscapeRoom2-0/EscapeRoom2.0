<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AcessoController;
namespace App\Http\Controllers;

use App\Models\Password;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PasswordController extends Controller
{
    //Função para ver Jogos em Lista
    public function index()
    {
        // Busca todas as senhas, junto com os jogos associados
        $passwords = Password::with('game')->get();

        // Retorna a view com as senhas e os jogos
        return view('index', compact('passwords'));
    }

       // Exibe o formulário de seleção de jogo
       public function selectGame()
       {
           $games = Game::all(); // Pega todos os jogos
           return view('select', compact('games'));
       }
   
       // Exibe as senhas associadas ao jogo selecionado
       public function showPasswords(Request $request)
       {
        

        $available_numbers = session('available_numbers');

            $numbers = range(1,4);
            $randomKey = array_rand($numbers); // Pega uma chave aleatória do array
            $randomNumber = $numbers[$randomKey]; // Usa a chave para obter o número aleatório
    
            session(['randomNumber' => $randomNumber]);
            $Jogador1 = $request->input('Jogador1');
            $Jogador2 = $request->input('Jogador2');
           $game_id = $request->input('game_id'); // Obtém o ID do jogo selecionado
           session(['game_id' => $game_id]); // armazena ID do Jogo na Sessão
           $passwords = Password::where('game_id', $game_id)->get(); // Busca as senhas associadas ao jogo
            $tempoRestante = session('tempo_restante');
            $tempoRestante1 = session('tempo_restante1');
            $tempoRestante2 = session('tempo_restante2');

           return view('show', compact('passwords', 'game_id', 'tempoRestante','tempoRestante1','tempoRestante2', 'randomNumber', 'Jogador1', 'Jogador2'));
       }
       public function gotoform(Request $request)
{
    // Recupera o nome do jogador da sessão
    $Jogador1 = session('Jogador1_nome');
    $Nome_Ganhador = $request->input('Jogador');

    // Determina o tempo do vencedor com base na escolha do jogador
    if ($Nome_Ganhador === $Jogador1) {
        $Tempo_Ganhador = session('tempo_restante1');
    } else {
        $Tempo_Ganhador = session('tempo_restante2');
    }

    // Verifica se os valores de tempo foram definidos
    if (!$Tempo_Ganhador) {
        return redirect()->back()->withErrors(['Erro' => 'Tempo do jogador não encontrado.']);
    }

    // Retorna a view com os valores do ganhador e do tempo
    return view('FormularioG', compact('Nome_Ganhador', 'Tempo_Ganhador'));
}

       //Reseta Senhas das telas
       public function resetPasswords(Request $request)
       {
        session()->forget(['game_id', 'password_index', 'password_index2' ,'current_password', 'current_password2', 'tempo_restante','tempo_restante1','tempo_restante2', 'randomNumber', 'conta', 'conta2']);


        $randomNumber = session('randomNumber');
        $game_id = $request->input('game_id'); // Obtém o ID do jogo selecionado
        session(['game_id' => $game_id]); // armazena ID do Jogo na Sessão
        $passwords = Password::where('game_id', $game_id)->get(); // Busca as senhas associadas ao jogo
        $tempoRestante = session('tempo_restante');
        $tempoRestante1 = session('tempo_restante1');
        $tempoRestante2 = session('tempo_restante2');
        $Jogador1 = session('Jogador1_nome');
        $Jogador2 = session('Jogador2_nome');

        return view('show', compact('passwords', 'game_id', 'tempoRestante','tempoRestante1','tempoRestante2', 'randomNumber', 'Jogador1', 'Jogador2'));
       }


       //Função que Da acesso a senha para o usuario
       public function passwordacess(Request $request)
       {
               // Recupera o ID do jogo e o índice de progresso da sessão
            $token = session('game_token1');
    $game_id = session('game_id');
    $password_index = session('password_index', 0);

    // Certifique-se de que o game_id foi encontrado na sessão
    if ($game_id === null) {
        $game_id = 1;
    }

    // Busca todas as senhas associadas ao game_id
    $passwords = Password::where('game_id', $game_id)->get();

    // Verifica se o índice está dentro do intervalo de senhas disponíveis
    if ($password_index >= count($passwords)) {
        // Se o índice ultrapassar o número de senhas, finalizar o processo ou reiniciar
        session()->forget(['password_index']);
    }

    // Busca a senha atual com base no índice
    if($game_id != null){
    
        
    

    //colocar a senha atual na variavel conforme o index
    $current_password = $passwords[$password_index];
    
//coloca a senha atual na sessão
    session(['current_password' => $current_password->password]);
//dropa a sessão de senha atual
    session()->forget('current_password');
    }
    // Atualiza o índice de progresso para a próxima senha
    session(['password_index' => $password_index + 1]);

    // Retorna a view com a senha atual
    return view('senhacesso', compact('token', 'current_password' ));
       }


       public function passwordacess2(Request $request)
       {
        $token = session('game_token2');
               // Recupera o ID do jogo e o índice de progresso da sessão
    $game_id = session('game_id');
    $password_index2 = session('password_index2', 0);

    // Certifique-se de que o game_id foi encontrado na sessão
    if ($game_id === null) {
        $game_id = 1;
    }

    // Busca todas as senhas associadas ao game_id
    $passwords = Password::where('game_id', $game_id)->get();

    // Verifica se o índice está dentro do intervalo de senhas disponíveis
    if ($password_index2 >= count($passwords)) {
        // Se o índice ultrapassar o número de senhas, finalizar o processo ou reiniciar
        session()->forget([ 'password_index']);
    }

    // Busca a senha atual com base no índice
    if($game_id != null){
    

    $current_password = $passwords[$password_index2];
    
    
    session(['current_password' => $current_password->password]);

    session()->forget('current_password');
    }
    // Atualiza o índice de progresso para a próxima senha
    session(['password_index2' => $password_index2 + 1]);

    // Retorna a view com a senha atual
    return view('senhacesso', compact('current_password', 'token'));
       }

    public function filterByGame($game_id)
    {
        // Busca todas as senhas associadas ao game_id
        $passwords = Password::where('game_id', $game_id)->with('game')->get();

        // Passa as senhas filtradas para a view
        return view('index', compact('passwords'));
    }

   // Exibe o formulário para criar uma nova senha
   public function create()
   {
       $games = Game::all(); // Pega os jogos disponíveis
       return view('create', compact('games'));
   }

   // Armazena a nova senha
   public function store(Request $request)
   {
       // Valida os dados
       $request->validate([
           'password' => 'required',
           'game_id' => 'required|exists:game,id',
       ]);

       // Cria a nova senha
       Password::create($request->all());

       return redirect()->route('passwords.index')->with('success', 'Senha criada com sucesso!');
   }

   // Exibe o formulário para editar uma senha existente
   public function edit($id)
   {
       $password = Password::findOrFail($id);
       $games = Game::all();
       return view('edit', compact('password', 'games'));
   }

   // Atualiza a senha no banco de dados
   public function update(Request $request, $id)
   {
       // Valida os dados
       $request->validate([
           'password' => 'required',
           'game_id' => 'required|exists:game,id',
       ]);

       // Atualiza a senha existente
       $password = Password::findOrFail($id);
       $password->update($request->all());

       return redirect()->route('passwords.index')->with('success', 'Senha atualizada com sucesso!');
   }

   // Remove a senha do banco de dados
   public function destroy($id)
   {
       $password = Password::findOrFail($id);
       $password->delete();

       return redirect()->route('passwords.index')->with('success', 'Senha removida com sucesso!');
       return view('index', compact('passwords'));
   }
   public function resetGame(Request $request){
    session()->forget('available_numbers', 'available_numbers2');
    $games = Game::all(); // Pega todos os jogos
           return view('select', compact('games'));
    
}

}

