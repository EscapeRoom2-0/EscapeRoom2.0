var Game = function(positions){
	$this 		   = this;
	$this.vars 	   = { positions: positions, currentPositions: {}, markers: 0, started : false, ended : false };
	$this.defaults = { radius : 50 };
	$canvas_main = $("#canvas-main");
	$canvas = $('.canvas');
	var positions = positions;

	var totalTime = 60; // tempo total em segundos
    var timerElement = $('#temporizado');
	var cliquemouse = 0;

	function updateTimer() {
        var minutes = Math.floor(totalTime / 60);
        var seconds = totalTime % 60;
        timerElement.text(`${minutes}:${seconds < 10 ? '0' : ''}${seconds}`);
        totalTime--;

        if (totalTime < 0) {
            clearInterval(timerInterval); // Para o temporizador
			document.getElementById('defeat').style.display = 'block';
			document.querySelectorAll('.canvas').forEach(function(canvas) {
				canvas.classList.add('disabled'); // Desabilita o clique nas imagens
			});
            
        }
    }

    // Função para inicializar o temporizador
    function iniciarTemporizador() {
        timerInterval = setInterval(updateTimer, 1000); // Atualiza a cada 1 segundo
    }


	this.init = function(){
		$this.createCanvas();
		$this.bindMouseEvents();
	}

	this.startGame = function(){
		$this.vars.started = true;
		$this.vars.ended = false;
		$this.vars.accepts = 0;
		$canvas_main.find('.marker').remove();
		$this.vars.markers = 0;
		jQuery.extend($this.vars.currentPositions, $this.vars.positions);
		
		document.getElementById('startButton').style.display = 'none';
		totalTime = 120;
		$('#temporizado').text('2:00');
		iniciarTemporizador();
	}

	this.resetGame = function(){
		$this.vars.started = true;
		$this.vars.ended = false;
		$this.vars.accepts = 0;
		$canvas_main.find('.marker').remove();
		$this.vars.markers = 0;
		jQuery.extend($this.vars.currentPositions, $this.vars.positions);

		// Oculta os displays de vitoria e derrota
		document.getElementById('defeat').style.display = 'none';
		document.getElementById('victory').style.display = 'none';
		document.querySelectorAll('.canvas').forEach(function(canvas) {
			canvas.classList.remove('disabled'); // Desativa o clique nos canvas
		});

		// Acréscimo de 30s para tentar novamente
		totalTime = 30;
		clearInterval(timerInterval);
		quantidade_acerto = 0;
		$('#temporizado').text('0:30');
		iniciarTemporizador();
	}

	this.createCanvas = function(){
		$canvas_main.on('mousemove', function(e) {
			var parentOffset = $(this).parent().offset(); 
			var relX = e.pageX - parentOffset.left;
			var relY = e.pageY - parentOffset.top;

			$('.cursor').css({
				'left': relX,
				'top': relY
			});
		});

		$canvas_main.on('mouseenter', function() {
			$('.cursor').addClass('visible');
		});

		$canvas_main.on('mouseleave', function() {
			$('.cursor').removeClass('visible');
		});
	}

	this.bindMouseEvents = function() {
		$canvas_main.on('click', function(event){
			if( $this.vars.started == true && $this.vars.ended == false )
			{
				$this.addMarker(event);
			}
		});
	}

	this.addMarker = function(e){
		if( $this.vars.markers <= 6 ) {
			var parentOffset = $(e.target).parent().offset(); 
			var relX = e.pageX - parentOffset.left;
			var relY = e.pageY - parentOffset.top;

			$canvas_main.append('<div class="marker" data-x="'+relX+'" data-y="'+relY+'"></div>');

			$('.marker:last-child').css({
				'left': relX-($this.defaults.radius/2),
				'top': relY-($this.defaults.radius/2)
			});

			$this.vars.markers++;
			if( $this.vars.markers == 7) {
				$this.vars.ended = true;
				$this.verify();
			}

			return true;
		}else{
			return false;
		}
	}

	var quantidade_acerto = 0;
	this.verify = function(){
		var distancia, $marker, markerPosition;
		
		$canvas_main.find('.marker').each(function(i){
			$marker = $(this);
			markerPosition = { x : $marker.data('x'), y : $marker.data('y') };

			$.each( $this.vars.currentPositions, function( index, position ) {
				distancia = Math.sqrt( Math.pow(markerPosition.x - position.x, 2) + Math.pow(markerPosition.y - position.y, 2) );
				if( distancia < $this.defaults.radius )
				{
					quantidade_acerto++;
					$marker.addClass('accept');
					delete $this.vars.currentPositions[index];
					$this.vars.accepts++;
				}
				if(quantidade_acerto == 7 && totalTime > 0){
					clearInterval(timerInterval);
					document.getElementById('victory').style.display = 'block';
					document.getElementById('defeat').style.display = 'none';
				} else if(quantidade_acerto != 7 || totalTime == 0){
					clearInterval(timerInterval);
					document.getElementById('defeat').style.display = 'block';
					document.getElementById('victory').style.display = 'none';	
				}
			});

		});

		if( $this.vars.accepts == 7 )
		{
			return true;
		}else{
			return false;
		}
	}

	this.debug = function(){
		$.each( $this.vars.positions, function( index, position ) {
			$canvas_main.append('<div class="target"></div>');
			$canvas_main.find('.target:last-child').css({
				'left': (position.x-($this.defaults.radius/2)) + 'px',
				'top': (position.y-($this.defaults.radius/2)) + 'px'
			});
		});
	}

	$this.init();

	this.startGame();
	return $this;

}