/* Formulário de Fatura */

let idContract;
let fatura;
let fatura_itens;

let idFatura = $("#idFatura").val();
let typeForm = '';

let keys;

$(function() { 
	idContract = $('#idContrato').val();
	
	if(idFatura == '') { //se for cadastrar nova fatura
		$('#btnDeleteInvoice').hide();
		loadData(false, idContract);

	} else { //se for editar fatura
		loadData(idFatura, false);
	}

	/* Cadastrar ou Editar Fatura --------------------------------------------------------------*/
	$("#btnSaveInvoice").click(function (e) { //para enviar o formulário de Fatura
		e.preventDefault();

		/*$("#formInvoice #idFatura").prop('disabled', false); //para poder mandar o campo quando enviar o Formulário
		$("#formInvoice #idFatura").prop('disabled', false);
		/...
		*/

		$('#numFatura').prop('disabled', false);

		let form = $('#formInvoice');
		let formData = new FormData(form[0]);
		formData.append(
			'arrFatura_itens',
			JSON.stringify(fatura_itens)
		);

		sendForm(formData)

		$('#numFatura').prop('disabled', true);

		return false;
	});

	/*$("#btnEmail").click(function () { //quando abrir o modal
		loadEmailFields(idFatura);
	});*/
}); 

function loadData(idFatura, idContract) {
	loadTableItens(); //carrega a tabela de itens de fatura

	if(!idFatura) { //se vai cadastrar uma nova fatura --------------------------------------------------

		$('#btnSaveInvoice').show();
		$('#btnUpdate').hide();

		$('#divListItens').attr('hidden', true); //esconde a parte da lista de produtos para adicionar

		loadDataNewInvoice(idContract)

	}else { // se for para Editar -------------------------------------------------- 
		$('#btnSaveInvoice').hide();
		$('#btnUpdate').show();

		loadInvoice(idFatura)
	}
}

function loadDataNewInvoice(idContract){ //carrega dados no formulário para a nova fatura
    
	$.getJSON(`/invoices/contract/${idContract}/create`, function(data) {
		console.log(data)

		loadFormInvoice(false, data);
	});
    console.log(`idContract=${idContract}`)

    $("#formInvoice #numFatura").prop('disabled', true );
}

//carrega detalhes de uma Fatura existente
async function loadInvoice(idFatura) {
    $('#divListItens').attr('hidden', false); //mostra a parte da lista de produtos para adicionar

    if(idFatura > 0){ //se for para ver uma fatura existente
		
		// console.log(`idFatura: ${idFatura}`)
		typeForm = 'view';

		$("#idFatura").val(idFatura)
		$('#divListItens').attr('hidden', false); //mostra a parte da lista de produtos para adicionar

		$('#btnCart').hide();

		$('#btnDeleteInvoice').show();
		$('#btnShowPDF').show();
		//$('#btnEmail').show();
	}
	//console.log(`function loadInvoice(${idFatura})`)
	
	//clearFieldsValues();
	clearErrors();

	$('#title').html('Detalhes de Fatura')
	$('#btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
	$('#btnSaveInvoice').hide();
	$('#btnUpdate').show();

	$.getJSON(`/invoices/json/${idFatura}`, function (data) { //requisição
		console.log(data)

		fatura = JSON.parse(data.fatura)
		$("#idFatura").val(fatura.idFatura);
		//$("#formInvoice #dtEmissao").val(fatura.dtEmissao).prop('disabled', true);
		//...

	}).then((data) => {
		//$("#InvoiceModal").modal();

		loadFormInvoice(idFatura, data);

		/* Atualizar formInvoice ------------------------------------------------------------------ */
		$('#btnUpdate').click(function () { //se eu quiser atualizar o Contrato atual

			//typeForm = 'save';
			$('#dataTable button').prop('hidden', false)
			$('#title').html('Editar Fatura');
			$('#btnClose').html('Cancelar').removeClass('btn-primary').addClass('btn-danger');
			$('#btnSaveInvoice').val('Atualizar').show();
			$('#btnUpdate').hide();

			setDisabledFields(false);
		});
	});	
}


function setDisabledFields(value) {

	keys.forEach((field) => {
		if(field != 'numFatura')
			$(`#formInvoice #${field}`).prop('disabled', value);
	});	
}


function loadFormInvoice(idFatura, data=false) {

	fatura = data.fatura;
	if(typeof(fatura) == 'string')
		fatura = JSON.parse(fatura)

	fatura_itens = data.fatura_itens;

	keys = [
		'dtInicio',
		'dtFim',
		'dtEmissao',
		'dtVencimento'
	];

	if(idFatura) { //carrega campos de uma fatura existente

		fatura_itens = JSON.parse(fatura_itens)
		
		keys2 = [
			'adicional',
			'dtCadastro',
			'dtCobranca',
			'dtEnvio',
			'dtPagamento',
			'dtVerificacao',
			'emailEnvio',
			'enviarPorEmail',
			'especCobranca',
			'formaPagamento',
			'idContrato',
			'idFatura',
			'numBoletoBanco',
			'numBoletoInt',
			'numFatura',
			'numNF',
			'observacoes',
			'statusPagamento',
			'valorPago'
		];

		keys = keys.concat(keys2);
	}
	
	setValuesToForm(fatura, keys);

	if(idFatura) {
		setDisabledFields(true);
		loadTableItens(fatura_itens) //carrega a tabela de itens de fatura
	}
		
}

function setValuesToForm(fatura, keys) {
	keys.forEach(function(field) {
		if(field != undefined) {
			//console.log(field, fatura[field])
			$(`#formInvoice #${field}`).val(fatura[field]);
		}		
	});
}

function sendForm(formData) {
	console.log('sendForm')

	if((idFatura == 0) || (idFatura == '')) {
		route = 'create';
		msg1 = 'cadastrar';
		msg2 = 'Fatura cadastrada!';

	}else {
		route = idFatura; //parte da rota para editar
		msg1 = 'atualizar';
		msg2 = 'Fatura atualizada!';
	}

	$.ajax({
		type: "POST",
		url: `/invoices/${route}`,
		data: formData,
		contentType: false,
		processData: false,
		beforeSend: function () {
			clearErrors();
			$("#btnSaveInvoice").parent().siblings(".help-block").html(loadingImg("Verificando..."));

		},
		success: function (response) {
			clearErrors();

			response = JSON.parse(response)
			console.log(response);

			if (response.error) {
				console.log(`error: ${response.error}`)
				console.log(`erro ao ${msg1} Fatura!`)

				Swal.fire(
					'Erro!',
					`Ocorreu algum erro ao ${msg1}`,
					'error'
				);

				if (response['error_list']) {

					showErrorsModal(response['error_list'])

					Swal.fire(
						'Atenção!',
						'Por favor verifique os campos',
						'warning'
					);
				}

			} else {

				let fatura = response.fatura;
				if(typeof(fatura == 'string'))
					//fatura = JSON.parse(fatura)
				
				if(route == 'create') {
					idFatura = fatura.idFatura;
					$('#idFatura').val(idFatura);
					
					console.log(`idFatura: ${$('#idFatura').val()}`);
					$('#numFatura').val(fatura.numFatura);

					$('#divListItens').attr('hidden', false);
					loadTableItens(response.fatura_itens);

					$('#btnDeleteInvoice').show();

					$('#dataTable button').prop('hidden', false)
					$('#title').html('Editar Fatura');
					$('#btnClose').html('Voltar').removeClass('btn-primary').addClass('btn-danger');
					$('#btnSaveInvoice').val('Atualizar').show();
					$('#btnUpdate').hide();
				}

				Swal.fire(
					'Sucesso!',
					msg2,
					'success'
				);
			}
		},
		error: function (response) {

		console.log(`Erro! Mensagem: ${response}`);

		}
	});

}

function deleteInvoice() {
	idFatura = $('#idFatura').val();
	numFatura = $("#numFatura").val();

	if(idFatura == ''){ //se a fatura acabou de ser cadastrada
		console.log('idFatura == 0')	
	}

	Swal.fire({
		title: `Você tem certeza de excluir a fatura nº ${numFatura}?`,
		text: "Você não será capaz de reverter isso! OBS: Todos os itens da fatura também serão excluídos",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#d33',
		cancelButtonColor: '#3085d6',
		confirmButtonText: 'Sim, apagar!'

	}).then((result) => {

		if (result.value) {

			$.ajax({
				type: "POST",
				url: `/invoices/${idFatura}/delete`,
				beforeSend: function () {

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
							'Fatura apagada!',
							'success'
						)

						console.log(`fatura ${idFatura} deletada`)
						window.location.assign("/invoices"); //vai para a página de orçamentos

						//loadTableInoices();
					}
				},
				error: function (response) {
					Swal.fire(
						'Erro!',
						'Não foi possível excluir a fatura',
						'error'
					)
					console.log(`Erro! Mensagem: ${response}`);
				}
			});

		}
	})

	$('.swal2-cancel').html('Cancelar');
}