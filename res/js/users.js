$(function() {

	
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


	$("#btnChangePassword").click(function() {
		alert('Essa função ainda não foi implementada!')
	 });

	
	/* cadastrar Usuario --------------------------------------------------------------*/
	$("#btnCreateUser").click(function(e) { //quando enviar o formulário de Usuarios
		e.preventDefault();
		/*if (!formValidate("formCreateUser")) {
			return;
		};*/

		let form = $(`#formCreateUser`);
		let formData = new FormData(form[0]);

		$.ajax({
			type: "POST",
			url: '/users/create',
			data: formData,
			contentType: false,
			processData: false,
			beforeSend: function() {
				clearErrors();
				$("#btnCreateUser").parent().siblings(".help-block").html(loadingImg("Verificando..."));
			
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
					$('#addModal').modal('hide');
					
					console.log(response)
					Swal.fire(
						'Sucesso!',
						'Usuário cadastrado!',
						'success'
					  )

					loadUsers();
					$('#formCreateUser').trigger("reset");
					
				}

				limparCampos();
				
			},
			error: function (response) {

				$('#formCreateUser').trigger("reset");
				console.log(`Erro! Mensagem: ${response}`);

			}
		});

		return false;
	});

	function loadUsers(){

		myTable.destroy();
		
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
});

//limpar campos do modal Cadastrar
function limparCampos(){
	$('#image-preview').attr('src', "/res/img/users/user-default.jpg");
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

function loadEditUser(idUsuario) {

	$.getJSON(`/users/json/${idUsuario}`, function (data) {
		//console.log(data)
		$("#formEditUser").data("id", idUsuario);

		$("#formEditUser #nomeCompleto").val(data.nomeCompleto);
		$("#formEditUser #funcao").val(data.funcao);
		$("#formEditUser #nomeUsuario").val(data.nomeUsuario);
		$("#formEditUser #senha").val(data.senha);
		$("#formEditUser #email").val(data.email);
		$("#formEditUser #administrador").val(data.administrador);
		
		dtCadastro = formatDate(data.dtCadastro)
		//console.log('data: ' + dtCadastro)
		$("#formEditUser #dtCadastro").val(dtCadastro);
			
		$("#formEditUser #image").attr("src", data.foto);
		//$("#desOldImagePath").val(data.foto);
		
		

	}).then(() => {

		$("#editUserModal").modal();
	}).fail(function () {
		console.log("Rota não encontrada!");
	});




	$("#btnEditUser").on('click', function (e) {

		e.preventDefault();

		if (!formValidate("formEditUser")) {
			return;
		};

		let form = $('#formEditUser');
		let formData = new FormData(form[0]);
		let idUsuario = $("#formEditUser").data('id');


		$.ajax({
			type: "POST",
			url: `/user/update/${idUsuario}`,
			data: formData,
			contentType: false,
			processData: false,
			success: function (data) {

				if (JSON.parse(data).error) {
					swal("Erro!", JSON.parse(data).message, "error");
				} else {
					$('#editUserModal').modal('hide');
					alert('editado com sucesso!')
					//swal("Tudo certo!", "Usuário editado com sucesso!", "success");
					loadUsers();
					$(`#formEditUser`).trigger("reset");
					//$("#dataTable").DataTable();
				}

			},
			error: function (data) {

				$('#editUserModal').modal('hide');
				$(`#formEditUser`).trigger("reset");
				console.log(`Erro! Mensagem: ${data}`);

			}
		});

	});

}
