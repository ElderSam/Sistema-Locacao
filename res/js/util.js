$(function() { //quando a página carrega

    loadUsernameMenu();
});


//carrega o nome do usuário atual e a foto no menu superior
function loadUsernameMenu(){
	$.ajax({
		type: "POST",
		url: '/users/varUser', //pede as variáveis do usuario atual
		contentType: false,
		processData: false,
		success: function (response) {

			if (JSON.parse(response).error) {
				console.log('erro ao carregar nome e foto do usuário no menu superior!')					
				
			} else {
				response = JSON.parse(response)
				$('#username').html(response.nomeUsuario)
				$('#image-username').attr('src', response.foto)
				
			}
			
		}
	});
}


//tradução do DataTables para Português-----------------------------
const DATATABLE_PTBR = { 
    "sEmptyTable": "Nenhum registro encontrado",
    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
    "sInfoPostFix": "",
    "sInfoThousands": ".",
    "sLengthMenu": "_MENU_ resultados por página",
    "sLoadingRecords": "Carregando...",
    "sProcessing": "Processando...",
    "sZeroRecords": "Nenhum registro encontrado",
    "sSearch": "Pesquisar",
    "oPaginate": {
        "sNext": "Próximo",
        "sPrevious": "Anterior",
        "sFirst": "Primeiro",
        "sLast": "Último"
    },
    "oAria": {
        "sSortAscending": ": Ordenar colunas de forma ascendente",
        "sSortDescending": ": Ordenar colunas de forma descendente"
    }
}//fim da tradução do DataTables -------------------------------------


function clearErrors() {
	$(".has-error").removeClass("has-error");
	$(".help-block").html("");
}

function showErrors(error_list) {
	clearErrors();

	$.each(error_list, function(id, message) {
		$(id).parent().parent().addClass("has-error");
		$(id).parent().siblings(".help-block").html(message)
	})
} 

function showErrorsModal(error_list) {
	clearErrors();

	$.each(error_list, function(id, message) {
		$(id).addClass("has-error");
		$(id).siblings(".help-block").html(message)
	})
} 

/* para mostrar imagem que acabou de fazer upload antes de enviar --------------------- */
document.querySelector('#formUser #desImagePath').addEventListener('change', function(){
	
	var file = new FileReader();
	$('#formUser #loadingImg').html(loadingImg("Carregando imagem...")); //mostra ao usuário status de carregando
		
	file.onload = function() {
	
		document.querySelector('#formUser #image-preview').src = file.result;
		$('#formUser #loadingImg').html('');
	}

	file.readAsDataURL(this.files[0]);

});

function loadingImg(message="") { //para mostrar animação de carregando (círculo girando)
	return "<i class='fa fa-circle-o-notch fa-spin'></i>&nbsp;" + message
}

