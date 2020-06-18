var myTable = null

$(function() { //quando a página carrega

	//carrega a tabela de Contracts
	loadTableContracts();

    
	/* Cadastrar ou Editar Contrato --------------------------------------------------------------*/	
	$("#btnSaveContract").click(function(e) { //quando enviar o formulário de Contrato
		e.preventDefault();
		
		let form = $('#formContract');
		let formData = new FormData(form[0]);

		idContract = $('#idContrato').val()
		console.log("idContrato:" + idContract)

		if((idContract == 0) || (idContract == undefined)){ //se for para cadastrar --------------------------------------------------

			//console.log("você quer cadastrar")
            Swal.fire(
                'Erro!',
                'Ocorreu algum erro ao carregar',
                'error'
            );

		}else{ /* se for para Editar -------------------------------------------------- */

			console.log('você quer editar o Contrato: ' + idContract)
			
			$.ajax({
				type: "POST",
				url: `/contracts/${idContract}`, //rota para editar
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function() {
					clearErrors();
					$("#btnSaveContract").parent().siblings(".help-block").html(loadingImg("Verificando..."));
				
				},
				success: function (response) {
					clearErrors();

					if (JSON.parse(response).error) {
						console.log('erro ao atualizar Contrato!')

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
						$('#ContractModal').modal('hide');

						Swal.fire(
							'Sucesso!',
							'Contrato atualizado!',
							'success'
						);

						loadTableContracts();
						$('#formContract').trigger("reset");
					}
	
				},
				error: function (response) {
	
					//$('#ContractModal').modal('hide');
					$('#formContract').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
	
				}
			});
		}	

		return false;
	});

});


function loadTableContracts(){ //carrega a tabela de Contratos

	if(myTable != null){
		myTable.destroy(); //desfaz as paginações
	}

	console.log('loading table ...')

	myTable = $("#dataTable").DataTable({ //carrega a tabela
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true,
		"ajax": {
			"url": "/contracts/list_datatables", //para chamar o método ajax_list_Contracts
			"type": "POST",
			dataSrc: function (json) {
				console.log('json')
				console.log(json)

				rows = [];
				let color = '';

				json.data.forEach(element => {
					console.log(element)

					row = []

					color = '';
					
					if (element.statusOrcamento == "Vencido"){
						color = 'red';
		
					}else if (element.statusOrcamento == "Aprovado"){
						color = 'orange';
		
					}else if (element.statusOrcamento == "Em Andamento"){
						color = 'green';
		
					}else if (element.statusOrcamento == "Encerrado"){
						color = 'grey';
		
					}

					//row['id'] = element.id
					row['statusOrcamento'] = `<strong style="color:${color}">${element.statusOrcamento}</strong>`
					row['codContrato'] = element.codContrato
					row['dtEmissao'] = element.dtEmissao
					row['obraCliente'] = element.obraCliente
					row['options'] = `<a type='button' title='ver detalhes' class='btn btn-warning btnEdit'
					href='/contracts/${element.idContrato}'>
						<i class='fas fa-bars sm'></i>
					</a>`
					

					rows.push(row)
				
				});
				
				return rows;
			},

		},
		"columns": [
			{ "data": "statusOrcamento" },
			{ "data": "codContrato" },
			{ "data": "dtEmissao" },
			{ "data": "obraCliente" },
			{ "data": "options" },
			        
		],
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});
}

// function loadFieldsContract(idContract){

// 	console.log(`loading all fields of Contract (id = ${idContract})`)

// 	$.getJSON(`/contracts/json/${idContract}`, function (data) { //ajax
// 		console.log(data)

// 		$("#idContrato").val(data.idContrato);
// 		console.log('load View Contrato idContrato: ' + $("#idContrato").val())

// 		$("#formContract #codigo").val(data.codContrato).prop('disabled', true);
// 		$("#formContract #obra_idObra").val(data.obra_idObra).prop('disabled', true);
// 		$("#formContract #dtEmissao").val(data.dtEmissao).prop('disabled', true);
// 		$("#formContract #status").val(data.statusOrcamento).prop('disabled', true);
// 		$("#formContract #dtAprovacao").val(data.dtAprovacao).prop('disabled', true);
// 		$("#formContract #custoEntrega").val(data.custoEntrega).prop('disabled', true);
//         $("#formContract #custoRetirada").val(data.custoRetirada).prop('disabled', true);
        
//         $("#formContract #dtInicio").val(data.dtInicio).prop('disabled', true);
//         $("#formContract #dtFim").val(data.dtFim).prop('disabled', true);

// 		$("#formContract #notas").val(data.notas).prop('disabled', true);
// 		$("#formContract #valorAluguel").val(data.valorAluguel).prop('disabled', true);

		
// 		/* Atualizar Contrato ------------------------------------------------------------------ */
// 		$('#btnUpdate').click(function(){ //se eu quiser atualizar o Contrato atual

// 			$('#modalTitle').html('Editar Contrato');
// 			$('#btnClose').html('Cancelar').removeClass('btn-primary').addClass('btn-danger');
// 			$('#btnSaveContract').val('Atualizar').show();
// 			$('#btnUpdate').hide();
		
// 			$("#formContract #codigo").prop('disabled', false);
// 			$("#formContract #obra_idObra").prop('disabled', false);
// 			$("#formContract #dtEmissao").prop('disabled', false);
// 			$("#formContract #status").prop('disabled', false);
// 			$("#formContract #dtAprovacao").prop('disabled', false);
// 			$("#formContract #custoEntrega").prop('disabled', false);
// 			$("#formContract #custoRetirada").prop('disabled', false);
// 			$("#formContract #notas").prop('disabled', false);
//             $("#formContract #valorAluguel").prop('disabled', false);
            
//             $("#formContract #dtInicio").val(data.dtInicio).prop('disabled', false);
//             $("#formContract #dtFim").val(data.dtFim).prop('disabled', false);

// 		}); /* Fim Atualizar Contrato ---------------------------------------------------------- */
			

// 	}).then(() => { 

// 		$("#ContractModal").modal();
// 	}).fail(function () {
// 		console.log("Rota não encontrada! (/contracts/json/:idContract)");
// 	});
// }

//detalhes do Contrato
// function loadContract(idContract) { //carrega todos os campos do modal referente ao Contrato escolhido
	
// 	console.log('loading Contracts')
	
// 	loadConstructions(loadFieldsContract, idContract); //carrega as obras e em seguida, todos os campos de orçamento/contrato

// 	clearFieldsValues();
// 	clearErrors();

// 	$('#modalTitle').html('Detalhes do Contrato')
// 	$('#btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
// 	$('#btnSaveContract').hide();
// 	$('#btnUpdate').show();

// }


//carrega as opções de Obras para colocar no Contrato
// function loadConstructions(callback, idContract = false){

// 	//console.log('loading constructions')

// 	$("#obra_idObra").html(`<option value="">(escolha)</option>`);
// 	$("#obra_idObra").html(`<option value="1">obra teste 1</option>`);

// 	/*$.getJSON(`/contracts/constructions/json`, function (data) { //ajax
		
// 		console.log(data)
		
// 		let constructions = `<option value="">(escolha)</option>`
		
// 		data.forEach(function(item){
// 			//console.log(item)
// 			constructions += `<option value="${item.idObra}">${item.codObra} - ${item.descCategoria}</option>`
// 		});

// 		$('#obra_idObra').html(constructions)
					

// 	}).then(() => { */
		
		
// 		if(idContract){
// 			callback(idContract) //executa a função loadFieldsContract()
// 		}
// /*	
	
// 	}).fail(function () {
// 		console.log("Rota não encontrada! (/contracts/constructions/json)");
// 		return false
// 	});*/

// }

//limpar campos do modal para Cadastrar
// function clearFieldsValues(){

// 	//$("#formContract #codigo").prop('disabled', true)
// 	$('#modalTitle').html('Cadastrar Contrato');
// 	$('#btnClose').html('Fechar').removeClass('btn-danger').addClass('btn-secondary');
// 	$('#btnSaveContract').val('Cadastrar').show();
// 	$('#btnUpdate').hide();

// 	$("#formContract #codigo").prop('disabled', false);
// 	$("#formContract #obra_idObra").prop('disabled', false);
// 	$("#formContract #dtEmissao").prop('disabled', false);
// 	$("#formContract #status").prop('disabled', false);
// 	$("#formContract #dtAprovacao").prop('disabled', false);
// 	$("#formContract #custoEntrega").prop('disabled', false);
// 	$("#formContract #custoRetirada").prop('disabled', false);
// 	$("#formContract #notas").prop('disabled', false);
// 	$("#formContract #valorAluguel").prop('disabled', false);


// 	//$('#image-preview').attr('src', "/res/img/contracts/Contract-default.jpg");
// 	$('#codigo').val('');
// 	$('#obra_idObra').val('0');
// 	$('#dtEmissao').val('');		
// 	$('#status').val('0');
// 	$('#dtAprovacao').val('');
// 	$('#dtAprovacao').val('');
// 	$('#custoEntrega').val('');
// 	$('#custoRetirada').val('');

// 	$('#notas').val('');
// 	$('#valorAluguel').val('');

// 	$('#idContract').val('0');
	
// }
// /*
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

