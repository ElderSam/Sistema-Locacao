
//tradução do DataTables para Português-----------------------------
/*const DATATABLE_PTBR = { 
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
*/

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
document.querySelector('#formCreateUser #desImagePath').addEventListener('change', function(){
	
	var file = new FileReader();
	$('#formCreateUser #loadingImg').html(loadingImg("Carregando imagem...")); //mostra ao usuário status de carregando
		
	file.onload = function() {
	
	document.querySelector('#formCreateUser #image-preview').src = file.result;
	$('#formCreateUser #loadingImg').html('');
	}

	file.readAsDataURL(this.files[0]);

});

document.querySelector('#desOldImagePath').addEventListener('change', function(){
	
	var file = new FileReader();
	$('#formEditUser #loadingImg').html(loadingImg("Carregando imagem...")); //mostra ao usuário status de carregando
		
	file.onload = function() {
	
	document.querySelector('#formEditUser #image').src = file.result;
	$('#formEditUser #loadingImg').html('');
	}

	file.readAsDataURL(this.files[0]);

});


function loadingImg(message="") { //para mostrar animação de carregando (círculo girando)
	return "<i class='fa fa-circle-o-notch fa-spin'></i>&nbsp;" + message
}

