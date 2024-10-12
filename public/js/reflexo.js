let tempoInicio = null;
let tempoReacao = null;
let jogoAtivo = false;

function iniciarJogo() {
    jogoAtivo = true;
    document.getElementById('resultado').textContent = '';
    document.getElementById('tempo').textContent = 'Espere a cor mudar!';
    document.getElementById('mensagem').textContent = ''; // Limpa a mensagem de erro
    document.getElementById('btn-iniciar').style.display = 'none'; // Esconde o botão de iniciar
    document.getElementById('Obtersenha').style.display = 'none';
    document.getElementById('tempoinsuficiente').style.display = 'none';
    document.getElementById('tempo').style.display = 'block'; // Mostra o tempo
    document.getElementById('botao').style.display = 'inline-block'; // Mostra o botão que muda de cor
    mudarCor();
}

function mudarCor() {
    const tempoEspera = Math.floor(Math.random() * 3000) + 1000;
    setTimeout(() => {
        if (jogoAtivo) {
            document.getElementById('botao').style.backgroundColor = '#26ab7e'; // Verde agua
            tempoInicio = new Date(); // Marca o início da reação
            document.getElementById('tempo').textContent = "Clique no botão!";
        }
    }, tempoEspera);
}

function clicar() {
    if (jogoAtivo) {
        if (tempoInicio) {
            const tempoFim = new Date();
            tempoReacao = (tempoFim - tempoInicio) / 1000; // Tempo em segundos
            document.getElementById('resultado').textContent = `Tempo de reação: ${tempoReacao.toFixed(2)} segundos!`;
            document.getElementById('resultado').style.display = 'block'; // Mostra o resultado
            document.getElementById('botao').style.backgroundColor = '#de2b10'; // Muda para vermelho após clicar
            if(tempoReacao < 0.40){
                document.getElementById('Obtersenha').style.display = 'block';
            } else{
                document.getElementById('tempoinsuficiente').style.display = 'block';
                document.getElementById('btn-recomecar').style.display = 'inline-block'; // Exibe botão de recomeçar
            }
            jogoAtivo = false; // Desativa o jogo
            
        } else {
            // Se clicar antes de o botão ficar verde
            document.getElementById('mensagem').textContent = "Você está sendo rápido demais!"; // Mensagem de erro
            document.getElementById('botao').style.display = 'none'; // Esconde o botão
            document.getElementById('btn-recomecar').style.display = 'inline-block'; // Exibe botão de recomeçar
            jogoAtivo = false; // Desativa o jogo
        }
    } else {
        // Mensagem de erro se tentar clicar após o jogo ter terminado
        document.getElementById('mensagem').textContent = "Você está sendo rápido demais!"; // Mensagem de erro
    }
}

function recomecar() {
    // Reseta o estado do jogo
    jogoAtivo = false;
    tempoInicio = null; // Reseta a variável para garantir que a mensagem de erro funcione
    document.getElementById('Obtersenha').style.display = 'none';
    document.getElementById('tempoinsuficiente').style.display = 'none';
    document.getElementById('botao').style.backgroundColor = '#b41111'; // Reseta a cor do botão
    document.getElementById('tempo').style.display = 'none'; // Esconde o tempo
    document.getElementById('resultado').style.display = 'none'; // Esconde o resultado
    document.getElementById('mensagem').textContent = ''; // Limpa a mensagem de erro
    document.getElementById('btn-recomecar').style.display = 'none'; // Esconde o botão de recomeçar
    document.getElementById('btn-iniciar').style.display = 'inline-block'; // Mostra o botão de iniciar novamente
    document.getElementById('botao').style.display = 'none'; // Esconde o botão de clicar
}

// O botão agora muda de cor e inicia o cronômetro quando clicado
document.getElementById('botao').onclick = function() {
    if (this.style.backgroundColor === 'rgb(255, 0, 0)') {
        return; // Se o botão estiver vermelho, não faz nada
    }
    clicar();
};
