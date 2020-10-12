var myTable = null

$(function() { //quando a página carrega

	idRent = parseInt($('#idRentURL').val()) //receives a number or false
	loadTableFreights(idRent)

	if(idRent) {
		$("#idLocacao").val(idRent);

		$("#btnAddFreight")
			.attr('hidden', false)
			.click(function(){ /* quando clica no botão para abrir o modal (cadastrar ou editar) */
				let i = 0
				clearFieldsValues();
			});
	}
	 
	/* Cadastrar ou Editar Locacao --------------------------------------------------------------*/	
	$("#btnSaveFreights").click(function(e) { //quando enviar o formulário de Locacao
		e.preventDefault(); 
		
		let form = $('#formFreights');
		let formData = new FormData(form[0]);
		console.log(formData)
		let idFrete = $('#id').val()
		console.log('id', idFrete)

		if((idFrete == 0) || (idFrete == undefined)){ //se for para cadastrar --------------------------------------------------

			console.log("você quer cadastrar")
		
			route = "create";
			msgError = "Cadastrar";
			msgSuccess = "Frete Cadastrado";

			sendForm();

		}else{  /* se for para Editar -------------------------------------------------- */

			
			$.ajax({
				type: "POST",
				url: `/freight/${idFreteAluguel}`, //rota para editar
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function() {
					clearErrors();
					$("#btnSaveFreights").parent().siblings(".help-block").html(loadingImg("Verificando..."));
				
				},
				success: function (response) {
					clearErrors();
	
					if (JSON.parse(response).error) {
						console.log('erro ao editar frete!')
	
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
						$('#freightModal').modal('hide');
	
						Swal.fire(
							'Sucesso!',
							'Frete atualizado!',
							'success'
						);
	
						loadTableFreights();
						$('#formFreights').trigger("reset");
					}
	
				},
				error: function (response) {
	
					//$('#CostumerModal').modal('hide');
					$('#formFreights').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
	
				}
			});
		}	
		
		return false;
	});
	
});

function loadTableFreights(idRent=false){ //carrega a tabela de Locações/Aluguéis

	let route;

	if(idRent) {
		route = `/freights/list_datatables/rent/${idRent}`;
	}else {
		route = '/freights/list_datatables';
	}

	if(myTable != null){
		myTable.destroy(); //desfaz as paginações
	}

		//carrega a tabela de Freights
	//function 
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR, //tradução
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true, 
		"ajax": {
			"url": route, //chama a rota para carregar os dados 
			"type": "POST",
			dataSrc: function (json) {
				console.log(json)
				rows = [];
				cont = 0;

				json.data.forEach(element => {
					//console.log(element)

					row = []

					//Essa variavel você pode apresentar
					// var telFormatado = mascaraTelefone(element.telefone1);

					//row['id'] = element.id

					if(element.tipo_frete == 0){
						tipoFrete = 'Entrega';
						colorFrete = 'green';

					}else if(element.tipo_frete == 1){
						tipo_frete = 'Retirada';
						colorFrete = 'Red';
					}

					if (element.status == 0) {
						txtStatus = 'Pendente ';
						color = 'Red';	
					}else if (element.status == 1) {
						txtStatus = 'Concluído';
						color = 'green';
					}

					//separa a string data_hora e trata campos data e hora, ex: 2020-10-02 09:30:00
					data = element.data_hora.substr(0, 10) //2020-10-02
					hora = element.data_hora.substr(11, 5) //09:30

					row['#'] = element.count;
					row['tipo_frete'] = `<b style='color: ${colorFrete}'>${tipo_frete}</b>`
					row['status'] = `<b style='color: ${color}'>${txtStatus}</b>`
					row['data_hora'] = element.data_hora //Ainda precisa formatar
					row['observacao'] = element.obeservacao;
					row['options'] = `<button type='button' title='ver detalhes' class='btn btn-warning btnEdit'
					onclick='loadFreight(${element.id});'>
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
			{ "data": "#" },
			{ "data": "tipoFrete"},
			{ "data": "status" },
			{ "data": "dataHora" },
			{ "data": "observacao" },
			{ "data": "options" },
		],
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});
	
}


//detalhes do Locacao
function loadFreight(idFreteAluguel) { //carrega todos os campos do modal referente ao Locacao escolhido
	
	//console.log(`load rent id: ${idFreteAluguel}`)
	clearFieldsValues();
	clearErrors();

	$('#modalTitle').html('Detalhes do Frete')
	$('#btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
	$('#btnSaveRent').hide();
	$('#btnUpdate').show();

	$.getJSON(`/freights/json/${idFreteAluguel}`, function (data) { //ajax
		
		console.log(data)
			
		$("#idFreteAluguel").val(data.id);
		$("#formFreights #locacao").val(data.idLocacao);
		$("#formFreights #tipoFrete").val(data.tipo_frete).prop('disabled', true);
		$("#formFreights #status").val(data.status).prop('disabled', true);
		$("#formFreights #dataHora").val(data.data_hora).prop('disabled', true);
		$("#formFreights #observacao").val(data.observacao).prop('disable', true);
		
	//Atualizar Frete	
	$('#btnUpdate').click(function(){

		$("#formFreights #tipoFrete").prop('disabled', false);
		$("#formFreights #status").prop('disabled', false);
		$("#formFreights #dataHora").prop('disabled', false);
		$("#formFreights #observacao").prop('disabled', false);

	});
	//Encerrar Frete

	}).then(()=>{
		$("#freightModal").modal();
	}).fail(function () {
		console.log("Rota não encontrada");
	});

}

//Deletar um frete
function deleteFreight(idFreteLocacao){

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
				url: `/freight/${idFreteLocacao}/delete`,
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

						loadTableFreights();						
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
	$('#modalTitle').html('Cadastrar Frete');
	$('#btnClose').html('Fechar').removeClass('btn-danger').addClass('btn-secondary');
	$('#btnSaveFreight').val('Cadastrar').show();
	$('#btnUpdate').hide();

	$("#formFreights #idFreteAluguel").hide();	
	$("#formFreights #locacao").prop('disabled', true);
	$("#formFreights #tipoFrete").prop('disabled', false);
	$("#formFreights #status").prop('disabled', false);
	$("#formFreights #dataHora").prop('disabled', false);
	$("#formFreights #observacao").prop('disabled', false);

	$('#id').val(0);
	//$("#idLocacao").val('')
	$('#tipo_frete').val('');
	$('#status').val('0');
	$('#data_hora').val(new Date());
	$('#observacao').val('');	
}