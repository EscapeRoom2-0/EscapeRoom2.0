<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\Acesso;
use App\Http\Controllers\Acesso2;
use App\Http\Controllers\Jogo;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\TemporizadorController;
use App\Http\Controllers\RelatorioController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/senhacesso', function () {
    return view('senhacesso');
});
Route::get('/senha', function () {
    return view('senha');
});

Route::get('/puzzle', function () {
    return view('puzzle');
});

Route::get('/resultado', function () {
    return view('resultado');
});

Route::get('/ParOuImpar', function () {
    return view('ParOuImpar');
});
Route::get('/7Erros', function () {
    return view('7Erros');
});

Route::get('/Detetive', function () {
    return view('Detetive');
});
Route::get('/Detetive2', function () {
    return view('Detetive2');
});
Route::get('/Portas', function () {
    return view('Portas');
});
Route::get('/Lanterna', function () {
    return view('Lanterna');
});
Route::get('/memorygame', function () {
    return view('memorygame');
});
Route::get('/index', function (){
    return view('index');
});
Route::get('/create', function (){
    return view('create');
});
Route::get('/select', function (){
    return view('select');
});
Route::get('/show', function (){
    return view('show');
});
Route::get('/senhaoutro', function (){
    return view('senhaoutro');
});
Route::get('/Jogador2', function (){
    return view('welcome2');
});
Route::get('/senhajogador2', function (){
    return view('senha2');
});
Route::get('/indextemporizador', function (){
    return view('indextemporizador');
});
Route::get('/createmporizador', function (){
    return view('createmporizador');
});
Route::get('/TelaErrada', function (){
    return view('Erro');
});
Route::get('/Reflexo', function (){
    return view('Reflexo');
});
Route::get('/Admin', function (){
    return view('Admin');
});
Route::get('/FormularioG', function (){
    return view('FormularioG');
});
Route::get('/mostrarelatorio', function (){
    return view('mostrarelatorio');
});
Route::get('/JogoDaVelha', function (){
    return view('JogoDaVelha');
});







// Rota para processar o formulário do jogo

//PasswordController 

Route::resource('passwords', PasswordController::class)->except(['show']);

Route::get('/passwords', [PasswordController::class, 'index'])->name('passwords.index');

Route::get('/passwords/game/{game_id}', [PasswordController::class, 'filterByGame'])->name('passwords.filterByGame');

// Rota para o formulário de seleção de jogo
Route::get('/passwords/select', [PasswordController::class, 'selectGame'])->name('passwords.selectGame');

// Rota para exibir as senhas do jogo selecionado
Route::get('/passwords/show', [PasswordController::class, 'showPasswords'])->name('passwords.showPasswords');

// Rota para resetar computadores, chamando o método do PasswordController
Route::get('/resetGame', [PasswordController::class, 'resetGame'])->name('passwords.resetGame');

// Rota para armazenar o jogo selecionado e inicializar a sessão
Route::post('/show', [PasswordController::class, 'showPasswords'])->name('show');

// Rota para exibir a próxima senha

Route::get('/recuperartoken1', [Acesso::class, 'recuperartoken1'])->name('recuperartoken1');
Route::get('/recuperartoken2', [Acesso::class, 'recuperartoken2'])->name('recuperartoken2');

Route::get('/passwordacess/{token}', [PasswordController::class, 'passwordacess'])->name('passwords.passwordacess');
Route::get('/passwordacess2/{token}', [PasswordController::class, 'passwordacess2'])->name('passwords.passwordacess2');

Route::get('/resetPasswords', [PasswordController::class, 'resetPasswords'])->name('passwords.resetPasswords');

Route::get('/gotoform', [PasswordController::class, 'gotoform'])->name('passwords.gotoform');


//GameController

Route::post('/play1/{token}', [GameController::class, 'play1'])->name('play1');
Route::post('/play2/{token}', [GameController::class, 'play2'])->name('play2');

// Rota para mostrar o resultado do jogo


//Acesso

Route::get('/criartoken', [Acesso::class, 'criartoken'])->name('criartoken');
Route::get('/criartoken2', [Acesso::class, 'criartoken2'])->name('criartoken2');

Route::get('/start-game/{token}', [Acesso::class, 'startGame'])->name('start-game');
Route::get('/start-game2/{token}', [Acesso::class, 'startGame2'])->name('start-game2');

Route::post('/acessar', [Acesso::class, 'acessar'])->name('acessar.submit');
Route::get('/acess', [Acesso::class, 'acess'])-> name('acess');



//GameController
Route::get('/parouimpar', [GameController::class, 'showForm'])->name('parouimpar'); 

Route::post('/processGame1/{token}', [GameController::class, 'processGame1'])->name('processGame1');
Route::post('/processGame2/{token}', [GameController::class, 'processGame2'])->name('processGame2');

Route::get('/puzzle1/{token}', [GameController::class, 'puzzle1'])->name('puzzle1');
Route::get('/puzzle2/{token}', [GameController::class, 'puzzle2'])->name('puzzle2');

Route::get('/parouimpar1/{token}', [GameController::class, 'parouimpar1'])->name('parouimpar1');
Route::get('/parouimpar2/{token}', [GameController::class, 'parouimpar2'])->name('parouimpar2');


// TemporizadorController

Route::resource('temporizador', TemporizadorController::class);
Route::get('/indextemp', [TemporizadorController::class, 'index'])->name('temporizador.index');

Route::get('/iniciar-temporizador', [TemporizadorController::class, 'iniciarTemporizador'])->name('temporizador.iniciar');

Route::get('/atualizar-temporizador', [TemporizadorController::class, 'atualizarTemporizador'])->name('temporizador.atualizar');
Route::get('/temporizador2', [TemporizadorController::class, 'atualizarTemporizador2'])->name('temporizador.atualizar2');

Route::get('/mostrar-temporizador', [TemporizadorController::class, 'mostrarTemporizador'])->name('temporizador.mostrar');

Route::post('/atualizar-Tempo', [TemporizadorController::class, 'atualizarTempo'])->name('temporizador.atualizarTempo');
Route::post('/atualizar-Tempo1', [TemporizadorController::class, 'atualizarTempo1'])->name('temporizador.atualizarTempo1');
Route::post('/atualizar-Tempo2', [TemporizadorController::class, 'atualizarTempo2'])->name('temporizador.atualizarTempo2');

Route::get('/Jogador1', [TemporizadorController::class, 'acabarTempo'])->name('temporizador.TempoAcabado');
Route::get('/Jogador2', [TemporizadorController::class, 'acabarTempo2'])->name('temporizador.TempoAcabado2');



Route::get('/obterTempo1', [TemporizadorController::class, 'obterTempo1'])->name('temporizador.obterTempo1');
Route::get('/obterTempo2', [TemporizadorController::class, 'obterTempo2'])->name('temporizador.obterTempo2');

//Relatorios

Route::resource('relatorios', RelatorioController::class);