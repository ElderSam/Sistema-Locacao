$(function() { //quando a página carrega pela primeira vez

	//carrega a tabela de Usuários
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR, //tradução
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true, 
		"ajax": {
			"url": "/costumers/list_datatables", //chama a rota para carregar os dados 
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


function loadTableUsers(){ //carrega a tabela de Usuários depois de  uma alteração

	myTable.destroy(); //desfaz as paginações
	
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true,
		"ajax": {
			"url": "/costumers/list_datatables", //para chamar o método ajax_list_user
			"type": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});
}


//detalhes do Cliente
function loadCostumer(idCliente) { //carrega todos os campos do modal referente ao usuário escolhido
	clearErrors();

	$('#modalTitle').html('Detalhes do Cliente')
	$('#btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
	$('#btnSaveUser').hide();
	$('#btnUpdate').show();	

	$('#dtCadastro').parent().show(); //aparece a data de cadastro (só para visualizar)


	$.getJSON(`/costumers/json/${idCliente}`, function (data) {
		//console.log(data)
		//$("#formUser").data("id", idUsuario);

		$("#formCostumer #codigo").val(data.idCliente).prop('disabled', true);
		$("#formCostumer #nome").val(data.nome).prop('disabled', true);
        $("#formCostumer #status").val(data.status).prop('disabled', true);
        $("#formCostumer #tipoCliente").val(data.tipoCliente).prop('disabled', true);
        $("#formCostumer #rg").val(data.rg).prop('disabled', true);
        $("#formCostumer #cpf").val(data.cpf).prop('disabled', true);
        $("#formCostumer #ie").val(data.ie).prop('disabled', true);
        $("#formCostumer #cnpj").val(data.cnpj).prop('disabled', true);
		$("#formCostumer #telefone1").val(data.telefone1).prop('disabled', true);
		$("#formCostumer #telefone2").val(data.telefone2).prop('disabled', true);
        $("#formCostumer #email1").val(data.email1).prop('disabled', true);
        $("#formCostumer #email2").val(data.email2).prop('disabled', true);
     
        dtCadastro = formatDate(data.dtCadastro);
        	//console.log('data: ' + dtCadastro)
        $("#formCostumer #dtCadastro").val(dtCadastro);
        
        $("#formCostumer #uf").val(data.uf).prop('disabled', true);
        $("#formCostumer #cep").val(data.cep).prop('disabled', true);
        $("#formCostumer #cidade").val(data.cidade).prop('disabled', true);
        $("#formCostumer #endereco").val(data.endereco).prop('disabled', true);
        $("#formCostumer #numero").val(data.numero).prop('disabled', true);
        $("#formCostumer #administrador").val(data.administrador).prop('disabled', true);

		//console.log('load View User idUsuario: ' + $("#idUsuario").val())

			
		/* Atualizar Usuário ------------------------------------------------------------------ */
		$('#btnUpdate').click(function(){ //se eu quiser atualizar o usuário atual

			$('#modalTitle').html('Editar Usuário');
			$('#btnClose').html('Cancelar').removeClass('btn-primary').addClass('btn-danger');
			$('#btnSaveUser').val('Atualizar').show();
			$('#btnUpdate').hide();
		

			$("#formUser #nomeCompleto").prop('disabled', false);
			$("#formUser #funcao").prop('disabled', false);
			$("#formUser #nomeUsuario").prop('disabled', false);
			//$("#formUser #senha").prop('disabled', false);
			$("#formUser #email").prop('disabled', false);
			$("#formUser #administrador").prop('disabled', false);
				
		}); /* Fim Atualizar Usuário ---------------------------------------------------------- */
			

	}).then(() => { 

		$("#costumerModal").modal();
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
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Sim, apagar!'

	}).then((result) => {

		if (result.value) {

			$.ajax({
				type: "POST",
				url: `/users/${idUsuario}/delete`,
				contentType: false,
				processData: false,
				/*beforeSend: function() {
					//...
				},*/
				success: function (response) {
		
					if (JSON.parse(response).error) {
						console.log('erro ao excluir!')
						response = JSON.parse(response)
						
						Swal.fire(
							'Erro!',
							'Por favor verifique os campos',
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

	//limpa os valores dos campos
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

