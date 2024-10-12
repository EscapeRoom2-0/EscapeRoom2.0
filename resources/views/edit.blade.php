<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Link para o CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Link para o arquivo CSS personalizado -->
<link rel="stylesheet" href="css/style.css">
    <title>Desarme a bomba</title>
</head>
<body>
<h1>Editar Senha</h1>

<form action="{{ route('passwords.update', $password->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="password">Senha</label>
        <input type="number" name="password" class="form-control" value="{{ $password->password }}" min="0" max="9" required>
    </div>
    <div class="form-group">
        <label for="game_id">Jogo</label>
        <select name="game_id" class="form-control" required>
            @foreach($games as $game)
                <option value="{{ $game->id }}" @if($password->game_id == $game->id) selected @endif>
                    {{ $game->id }}
                </option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-success">Salvar</button>
</form>
</body>
</html>

