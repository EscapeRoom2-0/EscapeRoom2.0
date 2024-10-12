<?php

namespace App\Http\Controllers;

use App\Models\Temporizador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;


class TemporizadorController extends Controller
{
    // Função para iniciar o temporizador
    public function iniciarTemporizador(Request $request)
    {

    
        // Obtém o ID do jogo selecionado do formulário
        $game_id = $request->input('game_id');
        $Jogador1 = $request->input('Jogador1');
        $Jogador2 = $request->input('Jogador2');
    
        // Recupera o temporizador do banco de dados (por exemplo, usando o ID 1)
        $temporizador = Temporizador::find(1); 
    
        // Armazena o tempo total e o jogo selecionado na sessão
        Session::put('tempo_restante', $temporizador->tempT);
        Session::put('tempo_restante1', $temporizador->tempJ1);
        Session::put('tempo_restante2', $temporizador->tempJ2);
        Session::put('game_id', $game_id);
        Session::put('Jogador1_nome', $Jogador1);
        Session::put('Jogador2_nome', $Jogador2);
    
        $tempoRestante = Session::get('tempo_restante');
        $tempoRestante1 = Session::get('tempo_restante1');
        $tempoRestante2 = Session::get('tempo_restante2');
    
        // Redireciona para a função que exibe as senhas do jogo selecionado
        return redirect()->route('passwords.showPasswords', [
            'game_id' => $game_id, 
            'tempoRestante' => $tempoRestante, 
            'tempoRestante1' => $tempoRestante1, 
            'tempoRestante2' => $tempoRestante2, 
            'Jogador1' => $Jogador1, 
            'Jogador2' => $Jogador2,
        ]);
    }


    public function atualizarTemporizador(Request $request)
    {
        $tempoRestante = session('tempo_restante1');
        return view('senha', compact('tempoRestante'));
    }
    public function atualizarTemporizador2(Request $request)
    {
        

        $tempoRestante = session('tempo_restante2');
        return view('senha2', compact('tempoRestante'));
    
         
    }

    public function obterTempo1()
{
    $tempoRestante = session('tempo_restante1'); // Ou outro método de armazenamento do tempo

    return response()->json(['tempoRestante' => $tempoRestante]);
}
public function obterTempo2()
{
    $tempoRestante = session('tempo_restante2'); // Ou outro método de armazenamento do tempo

    return response()->json(['tempoRestante' => $tempoRestante]);
}
    public function atualizarTempo(Request $request){
         // Recebe o tempo restante enviado via AJAX
    $tempoRestante = $request->input('tempo_restante');

    // Verifica se o tempo foi realmente enviado
    if ($tempoRestante != null) {
        // Atualiza o tempo restante na sessão
        $request->session()->put('tempo_restante', $tempoRestante);

        // Retorna uma resposta de sucesso com o tempo atualizado
        return response()->json([
            'status' => 'sucesso',
            'tempo_restante' => $tempoRestante
        ]);
    }

    // Caso o tempo não tenha sido enviado corretamente, retorna uma resposta de erro
    return response()->json([
        'status' => 'erro',
        'mensagem' => 'Tempo não fornecido'
    ], 400); // 400: Bad Request
    }
    public function atualizarTempo1(Request $request){
        // Recebe o tempo restante enviado via AJAX
   $tempoRestante1 = $request->input('tempo_restante1');

   // Verifica se o tempo foi realmente enviado
   if ($tempoRestante1 != null) {
       // Atualiza o tempo restante na sessão
       $request->session()->put('tempo_restante1', $tempoRestante1);

       // Retorna uma resposta de sucesso com o tempo atualizado
       return response()->json([
           'status' => 'sucesso',
           'tempo_restante1' => $tempoRestante1
       ]);
   }

   // Caso o tempo não tenha sido enviado corretamente, retorna uma resposta de erro
   return response()->json([
       'status' => 'erro',
       'mensagem' => 'Tempo não fornecido'
   ], 400); // 400: Bad Request
   }
   public function atualizarTempo2(Request $request){
    // Recebe o tempo restante enviado via AJAX
$tempoRestante2 = $request->input('tempo_restante2');

// Verifica se o tempo foi realmente enviado
if ($tempoRestante2 != null) {
   // Atualiza o tempo restante na sessão
   $request->session()->put('tempo_restante2', $tempoRestante2);

   // Retorna uma resposta de sucesso com o tempo atualizado
   return response()->json([
       'status' => 'sucesso',
       'tempo_restante2' => $tempoRestante2
   ]);
}

// Caso o tempo não tenha sido enviado corretamente, retorna uma resposta de erro
return response()->json([
   'status' => 'erro',
   'mensagem' => 'Tempo não fornecido'
], 400); // 400: Bad Request
}
    // Função para exibir a view do temporizador

    public function acabarTempo(){
        return view('welcome');
    }
    public function acabarTempo2(){
        return view('welcome2');
    }
    // Lista todos os temporizadores
    public function index()
    {
        $temporizadores = Temporizador::all();
        return view('indextemporizador', compact('temporizadores'));
    }

    // Exibe o formulário para criar um novo temporizador
    public function create()
    {
        return view('createmporizador');
    }

    // Armazena o novo temporizador no banco de dados
    public function store(Request $request)
    {
        $request->validate([
            'tempT' => 'required|integer',
            'tempJ1' => 'required|integer',
            'tempJ2' => 'required|integer',
        ]);

        Temporizador::create($request->all());
        return redirect()->route('temporizador.index')->with('success', 'Temporizador criado com sucesso!');
    }

    // Exibe o formulário para editar um temporizador
    public function edit($id)
    {
        $temporizador = Temporizador::findOrFail($id);
        return view('editemporizador', compact('temporizador'));
    }

    // Atualiza um temporizador no banco de dados
    public function update(Request $request, $id)
    {
        $request->validate([
            'tempT' => 'required|integer',
            'tempJ1' => 'required|integer',
            'tempJ2' => 'required|integer',
        ]);

        $temporizador = Temporizador::findOrFail($id);
        $temporizador->update($request->all());

        return redirect()->route('temporizador.index')->with('success', 'Temporizador atualizado com sucesso!');
    }

    // Remove um temporizador do banco de dados
    public function destroy($id)
    {
        $temporizador = Temporizador::findOrFail($id);
        $temporizador->delete();

        return redirect()->route('temporizador.index')->with('success', 'Temporizador removido com sucesso!');
    }
}
