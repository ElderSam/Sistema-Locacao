
let myTable = null;
let obra_idObra = '';
let idCliente = '';
let codigo = '0'

const idContrato = $("#idContrato").val();
let typeForm = '';


$(function () {	

	if(idContrato !== '0'){ //se for para ver um Contrato existente
		
		//alert("Entrou");
		console.log(`idContrato: ${idContrato}`)
		typeForm = 'view';

		$("#fk_idContrato").val(idContrato)
		$('#divListItens').attr('hidden', false); //mostra a parte da lista de produtos para adicionar

		$('#btnCart').hide();

		$('#btnDeleteContract').show();
		$('#btnShowPDF').show();
		$('#btnEmail').show();
		
		showsNextCode($("#formContract #dtEmissao").val());

		$("#formContract #dtEmissao").change(function(){
				showsNextCode($("#formContract #dtEmissao").val());
			}
		)
		
	}

	//adiciona as máscaras
	$("#telefone").mask("(00) 0000-00009");
	//$("#dtInicio").mask("dd/mm/aaaa");

	loadContract(idContrato);

	loadTableItens() //carrega a tabela de itens de contrato (produtos adicionados)

	/* Cadastrar ou Editar Contrato --------------------------------------------------------------*/
	$("#btnSaveContract").click(function (e) { //quando enviar o formulário de Contrato

		e.preventDefault();

		//adiciona as máscaras
		$("#telefone").unmask();

		$("#formContract #codigo").prop('disabled', false); //para poder mandar o campo quando enviar o Formulário
		$("#formContract #idCliente").prop('disabled', false);
		$("#formContract #obra_idObra").prop('disabled', false);

		let form = $('#formContract');
		let formData = new FormData(form[0]);

		if ((idContrato == 0) || (idContrato == undefined)) { //se for para cadastrar --------------------------------------------------

			alert("ERRO: Id do contrato não definido");

		} else { /* se for para Editar -------------------------------------------------- */

			$.ajax({
				type: "POST",
				url: `/contracts/${idContrato}`, //rota para editar
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function () {
					clearErrors();
					$("#btnSaveContract").parent().siblings(".help-block").html(loadingImg("Verificando..."));

				},
				success: function (response) {
					clearErrors();
					console.log(response);
					if (JSON.parse(response).error) {
						console.log('erro ao atualizar Contrato!')

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
						//$('#ContractModal').modal('hide');

						msg = 'Contrato atualizado!';

						Swal.fire(
							'Sucesso!',
							msg,
							'success'
						);

						//loadTableContracts();
						//$('#formContract').trigger("reset");
					}

				},
				error: function (response) {

					//$('#ContractModal').modal('hide');
					//$('#formContract').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);

				}
			});
		}

		return false;
	});

	
	$("#btnEmail").click(function () { //quando abrir o modal
		loadEmailFields(idContrato);
	});
});


//detalhes do Contrato
async function loadContract(idContrato) {
	//console.log(`function loadContract(${idContrato})`)
	//console.log("idContrato:" + idContrato)

	//clearFieldsValues();
	clearErrors();

	$('#title').html('Detalhes do Contrato')
	$('#btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
	$('#btnSaveContract').hide();
	$('#btnUpdate').show();

	let idCliente, obra_idObra;

	if ((idContrato == '0') || (idContrato == undefined)) { //se for cadastrar um novo Contrato
		
		alert("ERRO: Id não encontrado");
		
	}else{ //se for para Editar um Contrato existente
	
		idCliente, obra_idObra = await setFieldsContract(idContrato); //busca os valores dos campos de Contrato para preencher	
	}

	$("#formContract #codigo").prop('disabled', true);

	$("#idCliente").change(async function () {
		
		await loadConstructions(idCliente, obra_idObra);
	});

	//await loadTableProducts(); //carrega lista de produtos para colocar no Carrinho
}

function showsNextCode(dataEmissao) { //mostra o próximo número de série relacionado à categoria
	//console.log('shows next number')
	$.ajax({
		type: "GET",
		url: `/contracts/showsNextCode/${dataEmissao}`,
		contentType: false,
		processData: false,

		success: function (response) {

			//console.log('próximo código de Contrato: ' + response)
			$('#codigo').val(response)

		},
		error: function (response) {

			console.log(`Erro! Mensagem: ${response}`);
		}
	});
}


async function setFieldsContract(idContrato) {
	console.log(`function setFieldsContract(${idContrato})`)

	$.getJSON(`/contracts/json/${idContrato}`, function (data) { //ajax
		console.log(data)

		$("#idContrato").val(data.idContrato);
		//console.log('load View Orcamento idContrato: ' + $("#idContrato").val())

		$("#formContract #codigo").val(data.codContrato).prop('disabled', true);
		codigo = data.codContrato //seta o valor para mostrar no modal excluir
		
		$("#formContract #obra_idObra").prop('disabled', true);

		$("#formContract #dtEmissao").val(data.dtEmissao).prop('disabled', true);
		$("#formContract #solicitante").val(data.solicitante).prop('disabled', true);
		
		$("#telefone").unmask();
		$("#formContract #telefone").val(data.telefone).prop('disabled', true);

		$("#formContract #email").val(data.email).prop('disabled', true);
		//$("#formContract #referencia").val(data.referencia).prop('disabled', true);

		$("#formContract #status").val(data.statusOrcamento).prop('disabled', true);
		$("#formContract #dtInicio").val(data.dtInicio).prop('disabled', true);
		$("#formContract #dtAprovacao").val(data.dtAprovacao).prop('disabled', true);
		$("#formContract #dtFim").val(data.dtFim).prop('disabled', true);
		$("#formContract #notas").val(data.notas).prop('disabled', true);

	}).then((data) => {
		
		$("#telefone").mask('(00) 0000-00009');
		
		$("#ContractModal").modal();

		//$("#formContract #idCliente").val(data.idCliente).prop('disabled', true);
		idCliente = data.idCliente;
		//console.log(`received idCliente: ${idCliente}`)

		//$("#formContract #obra_idObra").val(data.obra_idObra).prop('disabled', true);
		obra_idObra = data.obra_idObra;
		//console.log(`received obra_idObra: ${obra_idObra}`)

		loadCostumers(idCliente, obra_idObra); //carrega clientes

		
		/* Atualizar formContract ------------------------------------------------------------------ */
		$('#btnUpdate').click(function () { //se eu quiser atualizar o Contrato atual

			typeForm = 'save';

			$('#title').html('Editar Contrato');
			$('#btnClose').html('Cancelar').removeClass('btn-primary').addClass('btn-danger');
			$('#btnSaveContract').val('Atualizar').show();
			$('#btnUpdate').hide();

			//$("#formContract #codigo").prop('disabled', false);
			//$("#formContract #idCliente").prop('disabled', false);
			//$("#formContract #obra_idObra").prop('disabled', false);
			$("#formContract #dtEmissao").prop('disabled', false);
			$("#formContract #solicitante").prop('disabled', false);
			$("#formContract #telefone").prop('disabled', false);
			$("#formContract #email").prop('disabled', false);
			$("#formContract #status").prop('disabled', false);
			$("#formContract #dtInicio").prop('disabled', false);
			$("#formContract #dtAprovacao").prop('disabled', false);
			$("#formContract #dtFim").prop('disabled', false);
			$("#formContract #notas").prop('disabled', false);
			


			$('#btnCart').show();
			$('#divListItens .btnEdit').show();
			$('#divListItens .btnDelete').show();

			$('#divListItens .btnEdit').attr('hidden', false)
			$('#divListItens .btnDelete').attr('hidden', false)
			

		}); /* Fim Atualizar Contrato ---------------------------------------------------------- */

		return [idCliente, obra_idObra];
		
	}).fail(function () {
		console.log("Rota não encontrada! (/contracts/json/:idContrato)");
	});
}

function deleteContract(idContrato) {

	if(idContrato == 0){ //se o orçamento acabou de ser cadastrado
		alert('idContrato == 0')
		idContrato = $('#idContrato').val();
		codigo = $("#codigo").val();
	}

	Swal.fire({
		title: `Você tem certeza de excluir o Contrato nº ${codigo}?`,
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
				url: `/contracts/${idContrato}/delete`,
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

						console.log(`contrato ${idContrato} deletado`)
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

//carrega os Clientes para colocar no Contrato
function loadCostumers(idCliente = '', obra_idObra = '') {

	//console.log('loading costumers')

	$("#idCliente").html(`<option value="">(escolha)</option>`);
	let costumers

	$.getJSON(`/costumers/json`, function (data) { //ajax

		//console.log(data)

		costumers = `<option value="">(escolha)</option>`

		data.forEach(function (item) {
			//console.log(item)
			costumers += `<option value="${item.idCliente}">${item.nome}</option>`
		});

		$('#idCliente').html(costumers)


	}).then(() => {

		if(idCliente !== ''){ //se já tem um cliente escolhido
			$("#idCliente").val(idCliente).prop('disabled', true);
			//console.log(`setting value idCliente: ${idCliente}`)
		}

		loadConstructions(idCliente, obra_idObra)

	}).fail(function () {
		console.log("Rota não encontrada! (/contracts/costumers/json)");
		return false
	});

}

//carrega as opções de Obras para colocar no Contrato
function loadConstructions(idCliente = '', obra_idObra = '') { //Carrega as Obras e em seguida os campos de orçamento (chamando outra função)
	console.log('loading constructions')

	$("#obra_idObra").html(`<option value="">(escolha)</option>`);

	//***************************************************
	$("#obra_idObra").append(`<option value="1">TESTE 1/2020 (número/ano)</option>`); //MUDAR ESSA LINHA QUANDO O CADSTRO DE OBRAS ESTIVER PRONTO
	
	if(obra_idObra == 1){
		$("#obra_idObra").val('1').prop('disabled', true); //tirar isso quando Obras estiver pronto ******************************************************************


	}
		/*$.getJSON(`/costumers/json/${idCliente}/constructions`, function (data) { //ajax

		console.log(data)

		let constructions = `<option value="">(escolha)</option>`

		data.forEach(function (item) {
			//console.log(item)
			constructions += `<option value="${item.idObra}">${item.codObra} - ${item.descCategoria}</option>`
		});

		$('#obra_idObra').html(constructions).prop('disabled', true);


	}).then(() => {

		$("#obra_idObra").val(obra_idObra)

	}).fail(function () {
		console.log("Rota não encontrada! (/contracts/constructions/json)");
		return false
	});*/

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
}

/************************************************** EMAIL ********************************************** */
//detalhes do Contrato
async function loadEmailFields(idContrato) {
	console.log(`function loadEmailFields(${idContrato})`)
	//console.log("idContrato:" + idContrato)

	//clearFieldsValues();
	clearErrors();

	if ((idContrato != '0') && (idContrato != undefined)) { //se já tiver um orçamento cadastrado
		$("#formEmail #name_from").val('TCC - teste (sistemalocacao)');
		$("#formEmail #toAdress").val(`${$("#formContract #email").val()}`)
		$("#formEmail #toName").val(`${$("#formContract #solicitante").val()}`)
		$("#formEmail #subject").val(
			`PROPOSTA N. ${$("#formContract #codigo").val()} - FORNECEDOR X`
		)
		//mensagem do email
		$("#formEmail #html").val(`OBS: Este é um e-mai teste
REF: PROPOSTA DA EMPRESA X PARA LOCAÇÃO
	Olá, segue em anexo o arquivo PDF do Contrato dos itens cotados para futura locação.

Atenciosamente,
Nome do Remetente
NOME DO FORNECEDOR - locações de equipamentos para construções`
		)
	
	}else{
		console.log('não pode enviar o e-mail, pois ainda não cadastrou o contrato')
	}
	
	/* Cadastrar ou Editar Contrato --------------------------------------------------------------*/
	$("#btnSendEmail").click(function (e) { //quando enviar o formulário de Contrato
		e.preventDefault();

		let form = $('#formEmail');
		let formData = new FormData(form[0]);

		if ((idContrato != 0) || (idContrato != undefined)) { //se o orçamento já foi cadastrado 
			console.log("você quer mandar um e-mail")

			$.ajax({
				type: "POST",
				url: `/contracts/${idContrato}/pdf/sendEmail`,
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function () {
					clearErrors();
					$("#btnSendEmail").parent().siblings(".help-block").html("<i class='fas fa-cog fa-spin fa-lg'></i>");

				},
				success: function (response) {
					res = JSON.parse(response)
					console.log(res)

					clearErrors();


					if (res.error) {
						console.log('erro ao enviar Contrato!')
						Swal.fire(
							'Erro!',
							res.msg, //'Ocorreu algum problema ao enviar o e-mail',
							'error'
						)

						if (res['error_list']) {
							showErrorsModal(res['error_list'])

							Swal.fire(
								'Atenção!',
								res.msg, //'Por favor verifique os campos',
								'warning'
							)
						}

					} else { // Se enviou com sucesso
						
						Swal.fire(
							'Sucesso!',
							res.msg,//'Contrato enviado para o cliente!',
							'success'
						)
					}

				},
				error: function (response) {
					//$('#ContractModal').modal('hide');
					//$('#formContract').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);

				}
			});

		} else { /* -------------------------------------------------- */

		}

		return false;
	});

}

