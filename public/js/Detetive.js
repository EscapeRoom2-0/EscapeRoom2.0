const totalItems = 4
let itemsFound = 0;

document.querySelectorAll('.item').forEach(item => {
    item.addEventListener('click', function() {
        if (!this.classList.contains('found')) {
            this.classList.add('found');
            itemsFound++;
            alert('Você encontrou um item: ' + this.querySelector('img').alt);
            this.style.visibility = 'hidden'; // Esconde o item após ser encontrado

            // Verifica se todos os itens foram encontrados
            if (itemsFound == totalItems) {
                document.getElementById('passwordScreen').style.display = 'block';
    
            } 
        }
        
    });

});


