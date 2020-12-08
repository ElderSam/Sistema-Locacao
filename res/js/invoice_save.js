/* Formulário de Fatura */

let myTable = null;

let idContract;
let fatura;
let fatura_itens;

const idFatura = $("#idFatura").val();
let typeForm = '';

$(function() { 
    idContract = $('#idContrato').val();
	loadData(idContract);

	//loadTableItens() //carrega a tabela de itens de fatura

	/* Cadastrar ou Editar Contrato --------------------------------------------------------------*/
	$("#btnSaveInvoice").click(function (e) { //para enviar o formulário de Fatura
		e.preventDefault();

		/*$("#formInvoice #idFatura").prop('disabled', false); //para poder mandar o campo quando enviar o Formulário
		$("#formInvoice #idContrato").prop('disabled', false);
		/...
		*/

		$('#numFatura').prop('disabled', false);
		$('#valorTotal').prop('disabled', false);

		let form = $('#formInvoice');
		let formData = new FormData(form[0]);

		sendForm(formData)

		$('#numFatura').prop('disabled', true);
		$('#valorTotal').prop('disabled', true);

		return false;
	});

	/*$("#btnEmail").click(function () { //quando abrir o modal
		loadEmailFields(idFatura);
	});*/
}); 

function loadData(idContract) {

	if((idFatura == '') || (idFatura == undefined)) { //se vai cadastrar uma nova fatura --------------------------------------------------
		$('#btnSaveContract').show();
		$('#btnUpdate').hide();

		$('#divListItens').attr('hidden', true); //esconde a parte da lista de produtos para adicionar

		loadDataNewInvoice(idContract)

	}/*else { // se for para Editar -------------------------------------------------- 
		$('#btnSaveContract').hide();
		$('#btnUpdate').show();

		loadInvoice(idFatura)
	}*/
	
}

function loadDataNewInvoice(idContract){ //carrega dados no formulário para a nova fatura
    
	$.getJSON(`/invoices/contract/${idContract}/create`, function(data) {
		console.log(data)

		loadFormInvoice(false, data);
	});
    console.log(`idContract=${idContract}`)

    $("#formInvoice #numeroFatura").prop('disabled', true );
    $("#formInvoice #valorTotal").prop('disabled', true );
}

function loadFormInvoice(idFatura, dataNewInvoice=false) {
    fatura = dataNewInvoice.fatura;
    fatura_itens = dataNewInvoice.fatura_itens;

    if(!idFatura) { //carrega campos para cadastrar nova fatura

        $('#formInvoice #dtInicio').val(fatura.dtInicio);
        $('#formInvoice #dtFim').val(fatura.dtFim);
        $('#dtEmissao').val(fatura.dtEmissao);
        $('#dtVencimento').val(fatura.dtVenc);

    }else{ //carrega campos de uma fatura existente
        
    }
}

function sendForm(formData) {
	console.log('sendForm')

	if((idFatura == 0) || (idFatura == undefined)) {
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

				if(route == 'create') {
					$('#idFatura').val(response.idFatura);
					console.log(`idFatura: ${$('#idFatura').val()}`);
					$('#numFatura').val(response.numFatura);
					$('#divListItens').attr('hidden', false);
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
