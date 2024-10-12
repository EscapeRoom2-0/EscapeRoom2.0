(function(){ // Função anônima para manter as variáveis dentro de um escopo local

    var matches = 0; // Contador de pares encontrados
    var images = []; // Array que irá armazenar as imagens
    var flippedCards = []; // Array que armazena as cartas viradas para comparação
    var modalGameOver = document.querySelector("#modalGameOver"); // Modal de fim de jogo
    var imgMatchSign = document.querySelector("#imgMatchSign"); // Imagem que indica um par encontrado

    // Loop para criar 6 objetos de imagem e adicionar ao array 'images'
    for(var i = 0; i < 6; i++){
        var img = { 
            src: "../img/" + i + ".jpg", // Caminho da imagem
            id: i % 3 // ID usado para identificação de pares (o mesmo ID será usado duas vezes)
        };
        images.push(img); // Adiciona a imagem ao array
    }

    startGame(); // Inicia o jogo

    // Função que configura o jogo inicial
    function startGame(){
        matches = 0; // Reinicia o contador de pares
        flippedCards = []; // Limpa o array de cartas viradas
        images = randomSort(images); // Embaralha as imagens

        var frontFaces = document.getElementsByClassName("front"); // Faces frontais das cartas
        var backFaces = document.getElementsByClassName("back"); // Faces traseiras das cartas

        // Loop que configura a posição, tamanho e eventos das cartas
        for(var i = 0; i < 6; i++){
            frontFaces[i].classList.remove("flipped", "match"); // Remove classes de virado ou pareado
            backFaces[i].classList.remove("flipped", "match"); // Remove classes de virado ou pareado

            var card = document.querySelector("#card" + i); // Seleciona a carta correspondente

            // Define a posição da carta no tabuleiro
            card.style.left = i % 3 === 0 ? 5 + "px" : i % 3 * 165 + 5 + "px";
            card.style.top = i < 3 ? 5 + "px" : 250 + "px";

            card.addEventListener("click", flipcard, false); // Adiciona evento de clique para virar a carta

            // Ajusta o tamanho das imagens para cartas na linha inferior
            if(i > 2){
                frontFaces[i].style.width = 160 + "px";
                frontFaces[i].style.height = 240 + "px";
            }

            // Define a imagem da face frontal da carta
            frontFaces[i].style.background = "url('" + images[i].src + "')";
            frontFaces[i].setAttribute("id", images[i].id); // Atribui um ID para a carta
        }

        // Esconde o modal de fim de jogo
        modalGameOver.style.zIndex = -2;
        modalGameOver.style.opacity = -1;
        modalGameOver.removeEventListener("click", startGame, false); // Remove o evento de reiniciar o jogo
    }

    // Função para embaralhar as imagens de forma aleatória
    function randomSort(oldArray){
        var newArray = []; // Novo array que será retornado embaralhado

        // Loop para embaralhar o array original
        while(newArray.length !== oldArray.length){
            var i = Math.floor(Math.random() * oldArray.length); // Gera um índice aleatório

            if(newArray.indexOf(oldArray[i]) < 0){ // Verifica se a imagem já foi inserida
                newArray.push(oldArray[i]); // Adiciona a imagem ao novo array
            }
        }

        return newArray; // Retorna o array embaralhado
    }

    // Função que lida com o evento de virar as cartas
    function flipcard(){
        if(flippedCards.length < 2){ // Se menos de 2 cartas estão viradas
            var faces = this.getElementsByClassName("face"); // Obtém as faces da carta clicada

            if(faces[0].classList.length > 2){ // Se a carta já está virada ou pareada, retorna
                return;
            }

            faces[0].classList.toggle("flipped"); // Vira a face frontal
            faces[1].classList.toggle("flipped"); // Vira a face traseira

            flippedCards.push(this); // Adiciona a carta ao array de cartas viradas

            // Se duas cartas estão viradas, verifica se formam um par
            if(flippedCards.length === 2){
                if(flippedCards[0].childNodes[3].id === flippedCards[1].childNodes[3].id){ // Compara os IDs das cartas
                    flippedCards[0].childNodes[1].classList.toggle("match"); // Marca a primeira carta como pareada
                    flippedCards[0].childNodes[3].classList.toggle("match"); // Marca a segunda carta como pareada
                    flippedCards[1].childNodes[1].classList.toggle("match");
                    flippedCards[1].childNodes[3].classList.toggle("match");

                    matchCardSign(); // Mostra o sinal de par encontrado
                    matches++; // Incrementa o contador de pares
                    flippedCards = []; // Limpa o array de cartas viradas

                    // Se todos os pares foram encontrados, termina o jogo
                    if(matches === 3){
                        gameOver();
                    }
                }
            }
        }else{ // Se mais de duas cartas estão viradas, vira as duas primeiras de volta
            flippedCards[0].childNodes[1].classList.toggle("flipped");
            flippedCards[0].childNodes[3].classList.toggle("flipped");
            flippedCards[1].childNodes[1].classList.toggle("flipped");
            flippedCards[1].childNodes[3].classList.toggle("flipped");

            flippedCards = []; // Limpa o array de cartas viradas
        }
    }

    // Função que mostra o modal de fim de jogo
    function gameOver(){ 
        modalGameOver.style.zIndex = 10; // Traz o modal para a frente
        modalGameOver.style.opacity = 1; // Mostra o modal
        modalGameOver.addEventListener("click", startGame, false); // Adiciona evento para reiniciar o jogo
    }

    // Função que mostra um sinal visual de que um par foi encontrado
    function matchCardSign(){
        imgMatchSign.style.zIndex = 1; // Traz o sinal para a frente
        imgMatchSign.style.top = 150 + "px"; // Ajusta a posição
        imgMatchSign.style.opacity = 0; // Esconde o sinal inicialmente

        // Após 1,5 segundos, esconde o sinal novamente
        setTimeout(function(){
            imgMatchSign.style.zIndex = -1; // Esconde o sinal
            imgMatchSign.style.top = 250 + "px"; // Ajusta a posição original
            imgMatchSign.style.opacity = 1; // Mostra novamente para futuros pares
        }, 1500);
    }

}()); // Fim da função autoexecutável
