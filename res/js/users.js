$(function() { //quando a página carrega

	//carrega a tabela de Usuários
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR, //tradução
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true, 
		"ajax": {
			"url": "/users/list_datatables", //chama a rota para carregar os dados 
			"type": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});

	$('#btnAddUser').click(function(){
		limparCampos();

		clearErrors();
	});


	$("#btnChangePassword").click(function() {
		Swal.fire(
			'OPS! :(',
			'Essa função ainda não foi implementada!',
			'info'
		)
	 });

	 
	 
	/* Cadastrar ou Editar Usuario --------------------------------------------------------------*/	
	$("#btnSaveUser").click(function(e) { //quando enviar o formulário de Usuarios
		e.preventDefault();
		
		let form = $('#formUser');
		let formData = new FormData(form[0]);

		idUsuario = $('#idUsuario').val()
		//onsole.log("idUsuario:" + idUsuario)

		if(idUsuario == 0){ //se for para cadastrar --------------------------------------------------

			//console.log("você quer cadastrar")

			$.ajax({
				type: "POST",
				url: '/users/create',
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function() {
					clearErrors();
					$("#btnSaveUser").parent().siblings(".help-block").html(loadingImg("Verificando..."));
				
				},
				success: function (response) {
					clearErrors();
	
					if (JSON.parse(response).error) {
						console.log('erro ao cadastrar novo usuário!')
						response = JSON.parse(response)
						
						Swal.fire(
							'Erro!',
							'Por favor verifique os campos',
							'error'
						)
	
						if(response['error_list']){
							
							showErrorsModal(response['error_list'])
						}
						
					} else {
						$('#userModal').modal('hide');
						
						//console.log(response)
						Swal.fire(
							'Sucesso!',
							'Usuário cadastrado!',
							'success'
							)
	
						loadTableUsers();
						$('#formUser').trigger("reset");
						
					}
					
				},
				error: function (response) {
					//$('#userModal').modal('hide');
					$('#formUser').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
	
				}
			});

		}else{ /* se for para Editar -------------------------------------------------- */

			//console.log('você quer editar o usuario: ' + idUsuario)
			
			$.ajax({
				type: "POST",
				url: `/users/${idUsuario}`, //rota para editar
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function() {
					clearErrors();
					$("#btnSaveUser").parent().siblings(".help-block").html(loadingImg("Verificando..."));
				
				},
				success: function (response) {
					clearErrors();

					if (JSON.parse(response).error) {
						console.log('erro ao editar usuário!')

						response = JSON.parse(response)

						Swal.fire(
							'Erro!',
							'Por favor verifique os campos',
							'error'
						);

						if(response['error_list']){
							
							showErrorsModal(response['error_list'])
						}

					} else {
						$('#userModal').modal('hide');

						Swal.fire(
							'Sucesso!',
							'Usuário atualizado!',
							'success'
						);

						loadTableUsers();
						$('#formUser').trigger("reset");
					}
	
				},
				error: function (response) {
	
					//$('#userModal').modal('hide');
					$('#formUser').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
	
				}
			});
		}	

		return false;
	});

});


function loadTableUsers(){ //carrega a tabela de Usuários

	myTable.destroy(); //desfaz as paginações
	
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true,
		"ajax": {
			"url": "/users/list_datatables", //para chamar o método ajax_list_user
			"type": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});
}


//detalhes do usuário
function loadUser(idUsuario) { //carrega todos os campos do modal referente ao usuário escolhido
	clearErrors();

	$('#modalTitle').html('Detalhes do Usuário')
	$('#btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
	$('#btnSaveUser').hide();
	$('#btnUpdate').show();
	

	$('#senha').parent().hide();
	$('#btnChangePassword').parent().show(); //botão para mudar senha
	$('#dtCadastro').parent().show(); //aparece a data de cadastro (só para visualizar)
	$('#desImagePath').parent().hide();


	$.getJSON(`/users/json/${idUsuario}`, function (data) {
		//console.log(data)
		//$("#formUser").data("id", idUsuario);

		$("#formUser #nomeCompleto").val(data.nomeCompleto).prop('disabled', true);
		$("#formUser #funcao").val(data.funcao).prop('disabled', true);
		$("#formUser #nomeUsuario").val(data.nomeUsuario).prop('disabled', true);
		//$("#formUser #senha").val(data.senha).prop('disabled', true);
		$("#formUser #email").val(data.email).prop('disabled', true);
		$("#formUser #administrador").val(data.administrador).prop('disabled', true);
		$("#idUsuario").val(data.idUsuario);

		//console.log('load View User idUsuario: ' + $("#idUsuario").val())

		dtCadastro = formatDate(data.dtCadastro)
		//console.log('data: ' + dtCadastro)
		$("#formUser #dtCadastro").val(dtCadastro);
			
		$("#formUser #image-preview").attr("src", data.foto); //mostra a imagem atual
		//$("#desmagePath").val(data.foto);

		/* Atualizar Usuário ------------------------------------------------------------------ */
		$('#btnUpdate').click(function(){ //se eu quiser atualizar o usuário atual

			$('#modalTitle').html('Editar Usuário');
			$('#btnClose').html('Cancelar').removeClass('btn-primary').addClass('btn-danger');
			$('#btnSaveUser').val('Atualizar').show();
			$('#btnUpdate').hide();
		
			$('#desImagePath').parent().show();

			$("#formUser #nomeCompleto").prop('disabled', false);
			$("#formUser #funcao").prop('disabled', false);
			$("#formUser #nomeUsuario").prop('disabled', false);
			//$("#formUser #senha").prop('disabled', false);
			$("#formUser #email").prop('disabled', false);
			$("#formUser #administrador").prop('disabled', false);
				
		}); /* Fim Atualizar Usuário ---------------------------------------------------------- */
			

	}).then(() => { 

		$("#userModal").modal();
	}).fail(function () {
		console.log("Rota não encontrada!");
	});

}

function deleteUser(idUsuario){

	Swal.fire({
		title: 'Você tem certeza?',
		text: "Você não será capaz de reverter isso!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#d33',
		cancelButtonColor: '#3085d6',
		confirmButtonText: 'Sim, apagar!'

	}).then((result) => {

		if (result.value) {

			$.ajax({
				type: "POST",
				url: `/users/${idUsuario}/delete`,
				contentType: false,
				processData: false,
				beforeSend: function() {
					
					$('.swal2-content').hide()
					$('.swal2-actions').hide()
					$('.swal2-title').html(`<div class="help-block">${loadingImg("Verificando...")}</div>`);
				
				},
				success: function (response) {
		
					if (JSON.parse(response).error) {
						console.log('erro ao excluir!')
						response = JSON.parse(response)
						
						Swal.fire(
							'Erro!',
							'Não foi possível excluir',
							'error'
						)
						
					} else {					
										
						Swal.fire(
							'Excluído!',
							'Registro apagado!',
							'success'
						)

						loadTableUsers();						
					}					
				},
				error: function (response) {
					Swal.fire(
						'Erro!',
						'Não foi possível excluir',
						'error'
					)

					console.log(`Erro! Mensagem: ${response}`);		
				}
			});		

		}
	})

	$('.swal2-cancel').html('Cancelar');
}


//limpar campos do modal para Cadastrar
function limparCampos(){

	$('#modalTitle').html('Cadastrar Usuário');
	$('#btnClose').html('Fechar').removeClass('btn-danger').addClass('btn-secondary');
	$('#btnSaveUser').val('Cadastrar').show();
	$('#btnUpdate').hide();

	$('#senha').parent().show();
	$('#btnChangePassword').parent().hide(); //botão para mudar senha
	$('#dtCadastro').parent().hide(); //aparece a data de cadastro (só para visualizar)
	$('#desImagePath').parent().show();


	$("#formUser #nomeCompleto").prop('disabled', false);
	$("#formUser #funcao").prop('disabled', false);
	$("#formUser #nomeUsuario").prop('disabled', false);
	//$("#formUser #senha").prop('disabled', false);
	$("#formUser #email").prop('disabled', false);
	$("#formUser #administrador").prop('disabled', false);
	

	$('#image-preview').attr('src', "/res/img/users/user-default.jpg");
	$('#btnChangePassword').parent().hide();
	$('#dtCadastro').parent().hide();
	$('#senha').parent().show();

	$('#nomeCompleto').val('');
	$('#funcao').val('');
	$('#nomeUsuario').val('');
	$('#senha').val('');
	$('#email').val('');
	$('#administrador').val('0');
	$('#idUsuario').val('0');
	//...	
}

function formatDate(dateX){ //format Date to input in Form
    var data = new Date(dateX),
        dia  = data.getDate().toString(),
        diaF = (dia.length == 1) ? '0'+dia : dia,
        mes  = (data.getMonth()+1).toString(), //+1 pois no getMonth Janeiro começa com zero.
        mesF = (mes.length == 1) ? '0'+mes : mes,
        anoF = data.getFullYear();
	//return diaF+"/"+mesF+"/"+anoF;
	return anoF+"-"+mesF+"-"+diaF;
}

