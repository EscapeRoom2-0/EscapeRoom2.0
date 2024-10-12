function delayRedirect(event) {
    event.preventDefault(); // Previne o comportamento padrão do link
    const link = event.target.href; // Obtém o href do link clicado
    document.querySelector('.loading').style.display = 'inline-block'; // Mostra o indicador de carregamento
    setTimeout(() => {
        window.location.href = link; // Redireciona para o link após 2000 milissegundos (2 segundos)
    }, 3000); // Tempo de atraso em milissegundos
}
