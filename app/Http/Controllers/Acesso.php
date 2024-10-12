<?php

namespace App\Http\Controllers;
use App\Models\Password;
use App\Models\Game;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

class Acesso extends Controller
{
    
    public function acessar(Request $request)
{
    // Obtém a senha digitada pelo usuário no formulário
    $password = $request->input('password');


    // Senha de administrador (ou senha especial)
    if ($password == '776688') { 
        // Se for a senha de administrador, pega todas as senhas e retorna a view index
        $passwords = Password::with('game')->get();
        return view('index', compact('passwords'));

    } else { 
            // Se a senha for incorreta, retorna com uma mensagem de erro
            return back()->withInput()->withErrors(['password' => 'Senha incorreta.']); 
        }
    }

    public function acess(Request $request)
    {
        return view('senhacesso');
    }
    public function criartoken2(Request $request)
{
    // Define o range de números de 1 a 10 (para verificar o progresso dos jogos)
    $numbers = range(1, 10);

    // Verifica se a sessão já contém números não utilizados
    $availableNumbers = session()->get('available_numbers2', $numbers);

    // Se todos os números ainda estão disponíveis (jogo novo), cria o token
    if (count($availableNumbers) === 10) {
        // Gera um token aleatório
        $token = 'Jogador2';
        
        // Definindo o jogador (pode ser 0 para Jogador 1 e 1 para Jogador 2)
        $jogador = 0; // Supondo que este seja o Jogador 1
        
        // Armazena o token e o ID do jogador na sessão
        session(['game_token2' => $token, 'Jogador2_id' => $jogador]);

        // Retorna uma resposta de sucesso ou redireciona para o próximo passo do jogo
        return redirect()->route('start-game2', ['token' => $token]);
    } else {
        // Se o jogo já começou (alguns números já foram utilizados), não cria novo token
        $token = session('game_token2'); // Recupera o token da sessão existente

        // Verifica se o token já foi criado anteriormente e redireciona
        if (!$token) {
            $token = 'Jogador2';
            session(['game_token2' => $token]);
        }

        // Caso o jogo já tenha sido iniciado, retorna a view correspondente ou redireciona
        return redirect()->route('start-game2', ['token' => $token]);
    }
    return redirect()->route('start-game2', ['token' => $token]);
}
    
public function startGame2($token, Request $request)
{
    $tempo_restante = session('tempo_restante2');
    $cont = session('conta2');
    $cont++;

    session(['conta2' => $cont]);
    // Array de números de 1 a 5

    $randomNumber = session('randomNumber');
    $numbers = range(1, 10);

    // Verifica se já existe uma sessão com números não utilizados
    $availableNumbers = session()->get('available_numbers2', $numbers);
    

    // Pega um número aleatório sem repetição
    
    $number = $this->getRandomNumber2($availableNumbers);

    // Verifica se não há mais números disponíveis (o jogo acabou)
    if ($number === null) {
        // Limpa a sessão para reiniciar o jogo
        session()->forget('available_numbers2');
    }
   

    // Remove o número já utilizado
    $availableNumbers = array_diff($availableNumbers, [$number]);
    

    // Atualiza a sessão com os números restantes
    session(['available_numbers2' => $availableNumbers]);

    $token = session('game_token2');

    if($cont === $randomNumber){
        return view('Erro', compact('token', 'tempo_restante'));
    }else{

    if(count($availableNumbers) != 4){
        
    // Switch case para retornar views diferentes com base no número
    switch ($number) {
        
        case 1:
            return view('puzzle', compact('token', 'tempo_restante'));  // Exibe a view para puzzle
            break;

        case 2:
            return view('ParOuImpar', compact('token', 'tempo_restante'));  // Exibe a view para ParOuImpar
            break;

        case 3:
            return view('7Erros', compact('token', 'tempo_restante'));  // Exibe a view para 7Erros
            break;

            case 4:
                return view('Detetive', compact('token', 'tempo_restante'));  // Exibe a view para Detetive
                break;
                case 5:
                    return view('Portas', compact('token', 'tempo_restante'));  // Exibe a view para Portas
                    break;
                    case 6:
                        return view('Lanterna', compact('token', 'tempo_restante'));  // Exibe a view para Lanterna
                        break;
                        case 7:
                            return view('memorygame', compact('token', 'tempo_restante'));  // Exibe a view para memorygame
                            break;
                            case 8:
                                return view('Reflexo', compact('token', 'tempo_restante'));  // Exibe a view para Reflexo
                                break;
                                case 9:
                                    return view('Detetive2', compact('token', 'tempo_restante'));  // Exibe a view para Detetve 2
                                    break;
                                    case 10:
                                        return view('JogoDaVelha', compact('token', 'tempo_restante'));  // Exibe a view para Detetve 2
                                        break;
                                
        default:
        session()->forget('available_numbers2');
        
    }
    
}else{
    session()->forget('available_numbers2');
    return view('welcome2');
}
}
}
public function criartoken(Request $request)
{
    // Define o range de números de 1 a 8 (para verificar o progresso dos jogos)
    $numbers = range(1, 10);

    // Verifica se a sessão já contém números não utilizados
    $availableNumbers = session()->get('available_numbers', $numbers);

    // Se todos os números ainda estão disponíveis (jogo novo), cria o token
    if (count($availableNumbers) === 10) {
        // Gera um token aleatório
        $token = 'Jogador1';
        
        // Definindo o jogador (pode ser 0 para Jogador 1 e 1 para Jogador 2)
        $jogador = 0; // Supondo que este seja o Jogador 1
        
        // Armazena o token e o ID do jogador na sessão
        session(['game_token1' => $token, 'Jogador1_id' => $jogador]);

        // Retorna uma resposta de sucesso ou redireciona para o próximo passo do jogo
        return redirect()->route('start-game', ['token' => $token]);
    } else {
        // Se o jogo já começou (alguns números já foram utilizados), não cria novo token
        $token = session('game_token1'); // Recupera o token da sessão existente

        // Verifica se o token já foi criado anteriormente e redireciona
        if (!$token) {
            $token = 'Jogador1';
            session(['game_token1' => $token]);
        }

        // Caso o jogo já tenha sido iniciado, retorna a view correspondente ou redireciona
        return redirect()->route('start-game', ['token' => $token]);
    }
    return redirect()->route('start-game', ['token' => $token]);
}

    

// Função de startar O jogo
    public function startGame($token1, Request $request)
    {
        $tempo_restante = session('tempo_restante1');
        $cont = session('conta');
        $cont++;

        session(['conta' => $cont]);
      

        $randomNumber = session('randomNumber');
        $numbers = range(1, 10);
    
        // Verifica se já existe uma sessão com números não utilizados
        $availableNumbers = session()->get('available_numbers', $numbers);
        
    
        // Pega um número aleatório sem repetição
        
        $number = $this->getRandomNumber($availableNumbers);
    
        // Verifica se não há mais números disponíveis (o jogo acabou)
        if ($number === null) {
            // Limpa a sessão para reiniciar o jogo
            session()->forget('available_numbers');
            
            // Redireciona para a view de fim de jogo ou reinício
        }
       
    
        // Remove o número já utilizado
        $availableNumbers = array_diff($availableNumbers, [$number]);
        
    
        // Atualiza a sessão com os números restantes
        session(['available_numbers' => $availableNumbers]);

        $token = session('game_token1');

        if($cont === $randomNumber){
            return view('Erro', compact('token', 'tempo_restante'));
        }else{

        if(count($availableNumbers) != 4){
            
        // Switch case para retornar views diferentes com base no número
        switch ($number) {
            
            case 1:
                return view('puzzle', compact('token', 'tempo_restante'));  // Exibe a view para puzzle
                break;
    
            case 2:
                return view('ParOuImpar', compact('token', 'tempo_restante'));  // Exibe a view para ParOuImpar
                break;
    
            case 3:
                return view('7Erros', compact('token', 'tempo_restante'));  // Exibe a view para 7Erros
                break;
    
                case 4:
                    return view('Detetive', compact('token', 'tempo_restante'));  // Exibe a view para Detetive
                    break;
                    case 5:
                        return view('Portas', compact('token', 'tempo_restante'));  // Exibe a view para Portas
                        break;
                        case 6:
                            return view('Lanterna', compact('token', 'tempo_restante'));  // Exibe a view para Lanterna
                            break;
                            case 7:
                                return view('memorygame', compact('token', 'tempo_restante'));  // Exibe a view para memorygame
                                break;
                                case 8:
                                    return view('Reflexo', compact('token', 'tempo_restante'));  // Exibe a view para Reflexo
                                    break;
                                    case 9:
                                        return view('Detetive2', compact('token', 'tempo_restante'));  // Exibe a view para Detetive2
                                        break;
                                        case 10:
                                            return view('JogoDaVelha', compact('token', 'tempo_restante'));  // Exibe a view para JogoDaVelha
                                            break;
            default:
            session()->forget('available_numbers');
  
            
        }
        
    }else{
        session()->forget('available_numbers');
        return view('welcome');
    }
}
}
    
    // Função auxiliar para pegar um número aleatório
    private function getRandomNumber($availableNumbers)
    {
        // Se não houver mais números disponíveis, retorna null
        if (empty($availableNumbers)) {
            return null;
        }
    
        // Retorna um número aleatório do array de números disponíveis
        return $availableNumbers[array_rand($availableNumbers)];
    }
    private function getRandomNumber2($availableNumbers2)
    {
        // Se não houver mais números disponíveis, retorna null
        if (empty($availableNumbers2)) {
            return null;
        }
    
        // Retorna um número aleatório do array de números disponíveis
        return $availableNumbers2[array_rand($availableNumbers2)];
    }


}

