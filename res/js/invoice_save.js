/* Formulário de Fatura */

let idContract;
let fatura;
let fatura_itens;

$(function() { 
    idContract = $('#idContrato').val();
    loadDataNewInvoice(idContract);
});

function loadDataNewInvoice(idContract=false){ //carrega dados no formulário para a nova fatura
    
    if(idContract){
        $.getJSON(`/invoices/contract/${idContract}/create`, function(data) {
            console.log(data)

            loadFormInvoice(false, data);
        });

    }else{
        console.log(`idContract=${idContract}`)
    }

    $("#formInvoice #numeroFatura").prop('disabled', true );
    $("#formInvoice #vlTotal").prop('disabled', true );
}

function loadFormInvoice(idInvoice, dataNewInvoice=false) {
    fatura = dataNewInvoice.fatura;
    fatura_itens = dataNewInvoice.fatura_itens;

    if(dataNewInvoice) { //carrega campos para cadastrar nova fatura

        $('#dtInicio').val(fatura.dtInicio);
        $('#dtFim').val(fatura.dtFim);
        $('#dtEmissao').val(fatura.dtEmissao);
        $('#dtVencimento').val(fatura.dtVenc);

    }else{ //carrega campos de uma fatura existente
        
    }
}

$(function () {	

//    =========================== ALTERAR DEPOIS =================

	if(idFatura !== '0'){ //se for para ver uma fatura existente
		
		// console.log(`idFatura: ${idFatura}`)
		typeForm = 'view';

		$("#fk_idContrato").val(idContrato)
		$('#divListItens').attr('hidden', false); //mostra a parte da lista de produtos para adicionar

		$('#btnCart').hide();

		$('#btnDeleteInvoice').show();
		$('#btnShowPDF').show();
		$('#btnEmail').show();
		
	}
//============================================================

//adiciona as máscaraS

	loadFormInvoice(idFatura);

	/* Cadastrar ou Editar Contrato --------------------------------------------------------------*/
	$("#btnSaveInvoice").click(function (e) { //quando enviar o formulário de Fatura

		e.preventDefault();

		$("#formInvoice #idFatura").prop('disabled', false); //para poder mandar o campo quando enviar o Formulário
		$("#formInvoice #idContrato").prop('disabled', false);
      
		let form = $('#formInvoice');
		let formData = new FormData(form[0]);

		if ((idFatura == 0) || (idFatura == undefined)) { //se for para cadastrar --------------------------------------------------

			alert("ERRO: Id da fatura não definido");

		} else { /* se for para Editar -------------------------------------------------- */

			$.ajax({
				type: "POST",
				url: `/invoice/${idFatura}`, //rota para editar
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function () {
					clearErrors();
					$("#btnSaveInvoice").parent().siblings(".help-block").html(loadingImg("Verificando..."));

				},
				success: function (response) {
					clearErrors();
					console.log(response);
					if (JSON.parse(response).error) {
						console.log('erro ao atualizar Fatura!')

						response = JSON.parse(response)

						Swal.fire(
							'Erro!',
							'Ocorreu algum erro ao Atualizar',
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

						msg = 'Fatura atualizada!';

						Swal.fire(
							'Sucesso!',
							msg,
							'success'
						);
					}

				},
				error: function (response) {

                console.log(`Erro! Mensagem: ${response}`);

				}
			});
		}

		return false;
    });
});    