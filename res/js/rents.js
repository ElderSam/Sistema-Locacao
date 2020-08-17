var myTable = null

$(function() { //quando a página carrega

	//loadTableRents()

	$("#btnAddRent").click(function(){
		let i = 0
		clearFieldsValues();

		if(i == 0){
			showsNextNumber()
		}
		
	});
	 
	/* Cadastrar ou Editar Locacao --------------------------------------------------------------*/	
	$("#btnSaveRent").click(function(e) { //quando enviar o formulário de Locacao
		e.preventDefault(); 
		
		let form = $('#formRent');
		let formData = new FormData(form[0]);

		idLocacao = $('#idLocacao').val()
		//console.log("idFornecedor:" + idFornecedor)

		if((idFornecedor == 0) || (idFornecedor == undefined)){ //se for para cadastrar --------------------------------------------------

			//console.log("você quer cadastrar")

			$.ajax({
				type: "POST",
				url: '/rents/create',
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function() {
					clearErrors();
					$("#btnSaveRent").parent().siblings(".help-block").html(loadingImg("Verificando..."));
					
				},
				success: function (response) {
					clearErrors();
	
					if (JSON.parse(response).error) {
						console.log('erro ao cadastrar novo Fornecedor!')
						response = JSON.parse(response)
						
						Swal.fire(
							'Erro!',
							'Ocorreu algum problema ao cadastrar',
							'error'
						)
	
						if(response['error_list']){
							
							showErrorsModal(response['error_list'])

							Swal.fire(
								'Atenção!',
								'Por favor verifique os campos',
								'error'
							)
						}
						
					} else { // Se cadastrou com sucesso

						$('#RentModal').modal('hide');
						
						//console.log(response)
						Swal.fire(
							'Sucesso!',
							'Fornecedor cadastrado!',
							'success'
							)
	
						loadTableRents();
						$('#formRent').trigger("reset");
						
					}
					
				},
				error: function (response) {
					//$('#RentModal').modal('hide');
					$('#formRent').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
	
				}
			});

		}else{ /* se for para Editar -------------------------------------------------- */

			//console.log('você quer editar o Fornecedor: ' + idFornecedor)
			
			$.ajax({
				type: "POST",
				url: `/rents/${idFornecedor}`, //rota para editar
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function() {
					clearErrors();
					$("#btnSaveRent").parent().siblings(".help-block").html(loadingImg("Verificando..."));
				
				},
				success: function (response) {
					clearErrors();

					if (JSON.parse(response).error) {
						console.log('erro ao atualizar Fornecedor!')

						response = JSON.parse(response)

						Swal.fire(
							'Erro!',
							'Ocorreu algum erro ao Atualizar',
							'error'
						);

						if(response['error_list']){
							
							showErrorsModal(response['error_list'])

							Swal.fire(
								'Atenção!',
								'Por favor verifique os campos',
								'error'
							);
						}

					} else {
						$('#RentModal').modal('hide');

						Swal.fire(
							'Sucesso!',
							'Fornecedor atualizado!',
							'success'
						);

						loadTableRents();
						$('#formRent').trigger("reset");
					}
	
				},
				error: function (response) {
	
					//$('#RentModal').modal('hide');
					$('#formRent').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
	
				}
			});
		}	

		return false;
	});

});
/*
function showsNextNumber(){ //mostra o próximo número de série relacionado à categoria
    console.log('shows next number')
    $.ajax({
        type: "POST",
        url: `/rents/showsNextNumber`,
        contentType: false,
        processData: false,
        
        success: function (response) {
    
            //console.log('próximo código de fornecedor: ' + response)
            $('#codigo').val(response)						
                                
        },
        error: function (response) {

            console.log(`Erro! Mensagem: ${response}`);		
        }
    });	
}*/


function loadTableRents(){ //carrega a tabela de Locações/Aluguéis

	if(myTable != null){
		myTable.destroy(); //desfaz as paginações
	}

		//carrega a tabela de Rents
	//function 
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR, //tradução
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true, 
		"ajax": {
			"url": "/rents/list_datatables", //chama a rota para carregar os dados 
			"type": "POST",
			dataSrc: function (json) {
				
				rows = [];

				json.data.forEach(element => {
					//console.log(element)

					row = []

					//Essa variavel você pode apresentar
					var telFormatado = mascaraTelefone(element.telefone1);

					//row['id'] = element.id
					row['codLocacao'] = element.codLocacao
					row['nome'] = element.nome
					row['status'] = element.status
					row['telefone1'] = telFormatado
					row['cidade'] = element.cidade
					row['options'] = `<button type='button' title='ver detalhes' class='btn btn-warning btnEdit'
					onclick='loadRent(${element.id});'>
						<i class='fas fa-bars sm'></i>
					</button>
					<button type='button' title='excluir' onclick='deleteRent(${element.id});'
						class='btn btn-danger btnDelete'>
						<i class='fas fa-trash'></i>
					</button>`

					rows.push(row)
				
				});
				
				return rows;
			},


		},
		"columns": [
			{ "data": "codLocacao" },
			{ "data": "nome" },
			{ "data": "status" },
			{ "data": "telefone1" },
			{ "data": "cidade" },
			{ "data": "options" },
			        
		],
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});


	
}


//detalhes do Locacao
function loadRent(idLocacao) { //carrega todos os campos do modal referente ao Locacao escolhido
	clearFieldsValues();
	clearErrors();

	$('#modalTitle').html('Detalhes do Locacao')
	$('#btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
	$('#btnSaveRent').hide();
	$('#btnUpdate').show();

	$.getJSON(`/rents/json/${idLocacao}`, function (data) { //ajax
		console.log(data)

		$("#idLocacao").val(data.idLocacao);
		//console.log('load View Locacao idLocacao: ' + $("#idLocacao").val())

		$("#formRent #codigo").val(data.codLocacao).prop('disabled', true);
		$("#formRent #nome").val(data.nome).prop('disabled', true);
		
        $("#formRent #cnpj").val(data.cnpj).prop('disabled', true)
        
		telefone1 = mascaraTelefone(data.telefone1)
		telefone2 = mascaraTelefone(data.telefone2)

		$("#formRent #telefone1").val(telefone1).prop('disabled', true);
		$("#formRent #telefone2").val(telefone2).prop('disabled', true);
		
		$("#formRent #email1").val(data.email1).prop('disabled', true);
		$("#formRent #email2").val(data.email2).prop('disabled', true);

		$("#formRent #endereco").val(data.endereco).prop('disabled', true);
		$("#formRent #numero").val(data.numero).prop('disabled', true);
		$("#formRent #bairro").val(data.bairro).prop('disabled', true);
		$("#formRent #complemento").val(data.complemento).prop('disabled', true);
		$("#formRent #cep").val(data.cep).prop('disabled', true);

		$("#formRent #cidade").val(data.cidade).prop('disabled', true);
		$("#formRent #status").val(data.status).prop('disabled', true);
		$("#formRent #uf").val(data.uf).prop('disabled', true);
		
		/* Atualizar Locacao ------------------------------------------------------------------ */
		$('#btnUpdate').click(function(){ //se eu quiser atualizar o Locacao atual

			$('#modalTitle').html('Editar Locacao');
			$('#btnClose').html('Cancelar').removeClass('btn-primary').addClass('btn-danger');
			$('#btnSaveRent').val('Atualizar').show();
			$('#btnUpdate').hide();
		
			//$('#desImagePath').parent().show();

			$("#formRent #codigo").prop('disabled', false);
			
			$("#formRent #nome").prop('disabled', false);
			$("#formRent #cnpj").prop('disabled', false);
			$("#formRent #telefone1").prop('disabled', false);
			$("#formRent #telefone2").prop('disabled', false);
			$("#formRent #email1").prop('disabled', false);
			$("#formRent #email2").prop('disabled', false);
			$("#formRent #endereco").prop('disabled', false);
			$("#formRent #numero").prop('disabled', false);
			$("#formRent #bairro").prop('disabled', false);
			$("#formRent #complemento").prop('disabled', false);
			$("#formRent #cidade").prop('disabled', false);
			$("#formRent #uf").prop('disabled', false);
			$("#formRent #cep").prop('disabled', false);
			$("#formRent #status").prop('disabled', false);

			
				
		}); /* Fim Atualizar Locacao ---------------------------------------------------------- */
			

	}).then(() => { 

		$("#RentModal").modal();
	}).fail(function () {
		console.log("Rota não encontrada! (/rents/json/:idLocacao)");
	});

}

function deleteRent(idLocacao){

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
				url: `/rents/${idLocacao}/delete`,
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

						loadTableRents();						
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
function clearFieldsValues(){

	//$("#formRent #codigo").prop('disabled', true)
	$('#modalTitle').html('Cadastrar Locacao');
	$('#btnClose').html('Fechar').removeClass('btn-danger').addClass('btn-secondary');
	$('#btnSaveRent').val('Cadastrar').show();
	$('#btnUpdate').hide();

	$('#dtCadastro').parent().hide(); //aparece a data de cadastro (só para visualizar)
	//$('#desImagePath').parent().show();

	$("#formRent #codigo").prop('disabled', false);
	$("#formRent #nome").prop('disabled', false);
	$("#formRent #cnpj").prop('disabled', false);
	$("#formRent #telefone1").prop('disabled', false);
	$("#formRent #telefone2").prop('disabled', false);
	$("#formRent #email1").prop('disabled', false);
	$("#formRent #email2").prop('disabled', false);
	$("#formRent #endereco").prop('disabled', false);
	$("#formRent #numero").prop('disabled', false);
	$("#formRent #bairro").prop('disabled', false);
	$("#formRent #cidade").prop('disabled', false);
	$("#formRent #uf").prop('disabled', false);
	$("#formRent #cep").prop('disabled', false);
	$("#formRent #status").prop('disabled', false);

	//$('#image-preview').attr('src', "/res/img/rents/rent-default.jpg");
	$('#dtCadastro').parent().hide();

	$('#codigo').val('');
	$('#nome').val('');
	$('#cnpj').val('');
	$('#telefone1').val('');
	$('#telefone2').val('');
	$('#email1').val('');
	$('#email2').val('');

	$('#endereco').html('');
	$('#numero').html('');
	$('#bairro').html('');
	$('#cidade').html('');

	$('#uf').val('SP');
	$('#cep').val('');

	
	$('#status').val('1');
	$('#dtCadastro').val('');
	
	$('#idLocacao').val('0');
	
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

