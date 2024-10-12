<?php

namespace App\Http\Controllers;

use App\Models\Relatorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class RelatorioController extends Controller
{

    // Exibe a lista de relatórios (Read)
    public function index()
    {
        $relatorios = Relatorio::orderBy('tempo', 'asc')->get();

        return view('mostrarelatorio', compact('relatorios'));
    }


    

    // Armazena o novo relatório no banco de dados (Store)
    public function store(Request $request)
    {
        // Valida os dados do formulário
        $validatedData = $request->validate([
            'nome' => 'required|string',
            'tempo' => 'required|string',
            'contato' => 'required|string',
        ]);

        // Inserir no banco de dados principal (usando o modelo Relatorio)
        $relatorio = Relatorio::create($validatedData);

        // Inserir os mesmos dados no segundo banco de dados
        DB::connection('second_db')->table('relatorio')->insert([
            'nome' => $validatedData['nome'],
            'tempo' => $validatedData['tempo'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Redireciona para a página de relatórios com uma mensagem de sucesso
        return redirect()->route('relatorios.index')->with('success', 'Relatório criado com sucesso!');
    }

    // Exibe um relatório específico (Read)
    public function show(Relatorio $relatorio)
    {
        return view('relatorios.show', compact('relatorio'));
    }

    // Atualiza o relatório existente no banco de dados (Update)
    public function update(Request $request, Relatorio $relatorio)
    {
        $request->validate([
            'nome' => 'required|string',
            'tempo' => 'required|string',
            'contato' => 'required|string',
        ]);

        $relatorio->update($request->all());

        return redirect()->route('relatorios.index')->with('success', 'Relatório atualizado com sucesso!');
    }

    // Exclui um relatório (Delete)
    public function destroy(Relatorio $relatorio)
    {
        $relatorio->delete();

        // Deleta o relatório do segundo banco de dados usando o mesmo ID
        DB::connection('second_db')->table('relatorio')->where('id', $relatorio->id)->delete();

        // Redireciona de volta para a lista com uma mensagem de sucesso
        return redirect()->route('relatorios.index')->with('success', 'Relatório excluído com sucesso!');
    }
}
