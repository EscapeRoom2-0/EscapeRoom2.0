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
<h1>Criar Temporizador</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('temporizador.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="tempT">Tempo Total (tempT)</label>
            <input type="number" name="tempT" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="tempJ1">Tempo Jogador 1 (tempJ1)</label>
            <input type="number" name="tempJ1" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="tempJ2">Tempo Jogador 2 (tempJ2)</label>
            <input type="number" name="tempJ2" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Criar</button>
    </form>
</body>
</html>

