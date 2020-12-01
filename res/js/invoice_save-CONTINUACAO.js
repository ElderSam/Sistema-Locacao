invoice_save.js

continuação ...

//carrega detalhes de uma Fatura existente
async function loadInvoice(idFatura) {
    $('#divListItens').attr('hidden', false); //mostra a parte da lista de produtos para adicionar

    if(idFatura !== '0'){ //se for para ver uma fatura existente
		
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

		$("#idFatura").val(data.idFatura);
		$("#formInvoice #dtEmissao").val(data.dtEmissao).prop('disabled', true);
		//...
	}).then((data) => {
		$("#InvoiceModal").modal();
		
		/* Atualizar formInvoice ------------------------------------------------------------------ */
		$('#btnUpdate').click(function () { //se eu quiser atualizar o Contrato atual

			typeForm = 'save';

			$('#title').html('Editar Fatura');
			$('#btnClose').html('Cancelar').removeClass('btn-primary').addClass('btn-danger');
			$('#btnSaveInvoice').val('Atualizar').show();
			$('#btnUpdate').hide();

			//...
			setDisabledFields(arrFields, false);
		});
	});

}

function setDisabledFields(arrFields, value) {

	arrFields.forEach((field) => {
		$(`#formContract #${field}`).prop('disabled', value);
	});	
}

function deleteInvoice(idFatura) {

	if(idFatura == 0){ //se o orçamento acabou de ser cadastrado
		console.log('idFatura == 0')
		idFatura = $('#idFatura').val();
		codigo = $("#codigo").val();
	}

	Swal.fire({
		title: `Você tem certeza de excluir a fatura nº ${codigo}?`,
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
				url: `/contracts/${idFatura}/delete`,
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
							'Contrato apagado!',
							'success'
						)

						console.log(`contrato ${idFatura} deletado`)
						window.location.assign("/contracts"); //vai para a página de orçamentos

						//loadTableContract();
					}
				},
				error: function (response) {
					Swal.fire(
						'Erro!',
						'Não foi possível excluir o contrato',
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
function clearFieldsValues() {

	//$("#formContract #codigo").prop('disabled', true)
	$('#btnUpdate').hide();
	//$('#title').html('Cadastrar Orçamento');
	$('#btnClose').html('Fechar').removeClass('btn-danger').addClass('btn-secondary');
	$('#btnSaveContract').val('Cadastrar').show();

	//$('#dtCadastro').parent().hide(); //aparece a data de cadastro (só para visualizar)

	//$("#formContract #codigo").prop('disabled', false);

	//$('#dtCadastro').parent().hide();

	$('#codigo').val('');
	$('#nome').val('');

	$('#endereco').html('');

	$('#idCliente').val('');
	$('#obra_idObra').val('');

	//$('#status').val('1');
	//$('#dtEmissao').val('');
	$('#solicitante').val('');
	$('#telefone').val('');
	$('#email').val('');
	//$('#referencia').val('');

	$("#temMedicao").prop('checked', false);
}


/************************************************** EMAIL ********************************************** */
//detalhes do Contrato
/*async function loadEmailFields(idContrato) {
 //...
}*/
   