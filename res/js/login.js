
$("#btnLogin").click(function(e) {
    e.preventDefault(); //para não recarregar a página

    if(($('#login').val() != "") && ($('#password').val() != "")){

        console.log('logando')
            
        let form = $('#formLogin');
        let formData = new FormData(form[0]);

        $.ajax({
            type: "POST",
            url: '/login',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {
                clearErrors();
                $("#btnLogin").parent().siblings(".help-block").html(loadingImg("Verificando..."));
    
            },
            success: function (response) {

                clearErrors();
        
                if (JSON.parse(response).error) {
                    console.log('erro ao se logar!')
                    response = JSON.parse(response)
                    
                    Swal.fire(
                        'Atenção!',
                        'usuário e/ou senha incorretos!',
                        'error'
                    )
                
                    $("#login").addClass("has-error");
                    $("#login").siblings(".help-block").html('está correto?')
        
                    $("#password").addClass("has-error");
                    $("#password").siblings(".help-block").html('está correto?')
                
            
                }else{
                    
                    window.location = '/'; //manda para a rota principal
                }
                                  
            },
            error: function (response) {
                Swal.fire(
                    'Erro!',
                    'Não conseguimos efetuar o login',
                    'error'
                )
        
            }
        });

    }else{ // se tiver campo vazio

        clearErrors();

        if($("#login").val() == ""){
            $("#login").addClass("has-error");
            $("#login").siblings(".help-block").html('Preencha o campo')
        }

        if($("#password").val() == ""){
            $("#password").addClass("has-error");
            $("#password").siblings(".help-block").html('Preencha o campo')
        }


        Swal.fire(
            'Atenção!',
            'Preencha os campos',
            'error'
        )
    }

});


function clearErrors() {
	$(".has-error").removeClass("has-error");
	$(".help-block").html("");
}



/* para mostrar imagem que acabou de fazer upload antes de enviar --------------------- */
document.querySelector('#desImagePath').addEventListener('change', function(){
	
	var file = new FileReader();
	$('#loadingImg').html(loadingImg("Carregando imagem...")); //mostra ao usuário status de carregando
		
	file.onload = function() {
	
		document.querySelector('#image-preview').src = file.result;
		$('#loadingImg').html('');
	}

	file.readAsDataURL(this.files[0]);

});

function loadingImg(message="") { //para mostrar animação de carregando (círculo girando)
	return "<i class='fa fa-circle-o-notch fa-spin'></i>&nbsp;" + message
}
