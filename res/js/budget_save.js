let myTable = null;

let obra_idObra = '';
let idCliente = '';
let codigo = '0'

let idOrcamento = $("#idOrcamento").val();
let idContrato = $("#idOrcamento").val(); //para o arquivo budgetItems_save.js
let typeForm = '';

//Somente para orçamento
$('#dtInicio').parent().hide();
$('#dtFim').parent().hide();

$(function () {	

	if(idOrcamento !== '0'){ //se for para ver um Orçamento existente
		
		console.log(`idOrçamento: ${idOrcamento}`)
		typeForm = 'view';

		$("#fk_idOrcamento").val(idOrcamento)
		$('#divListItens').attr('hidden', false); //mostra a parte da lista de produtos para adicionar

		$('#btnCart').hide();

		$('#btnDeleteBudget').show();
		$('#btnShowPDF').show();
		$('#btnEmail').show();
		
	}else{ //se for para cadastrar
		typeForm = 'save';
		console.log('Novo Orçamento')
		$('#btnDeleteBudget').hide();
		$('#btnShowPDF').hide();
		$('#btnEmail').hide();
	}

	//adiciona as máscaras
	$("#telefone").mask("(00) 0000-00009");
	//$("#dtInicio").mask("dd/mm/aaaa");

	loadBudget(idOrcamento);

	loadTableItens() //carrega a tabela de itens de orçamento (produtos adicionados)

	/*$("#btnAddBudget").click(function(){
		
		loadBudget();
	});*/


	/* Cadastrar ou Editar Orcamento --------------------------------------------------------------*/
	$("#btnSaveBudget").click(function (e) { //quando enviar o formulário de Orcamento

		e.preventDefault();

		//adiciona as máscaras
		$("#telefone").unmask();

		$("#formBudget #codigo").prop('disabled', false); //para poder mandar o campo quando enviar o Formulário

		let form = $('#formBudget');
		let formData = new FormData(form[0]);

		idOrcamento = $('#idOrcamento').val()
		//console.log("idOrcamento:" + idOrcamento)

		if ((idOrcamento == 0) || (idOrcamento == undefined)) { //se for para cadastrar --------------------------------------------------

			//console.log("você quer cadastrar")
			
			$.ajax({
				type: "POST",
				url: '/budgets/create',
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function () {
					clearErrors();
					$("#btnSaveBudget").parent().siblings(".help-block").html(loadingImg("Verificando..."));

				},
				success: function (response) {
					clearErrors();
					$("#telefone").mask("(00) 0000-00009");

					if (JSON.parse(response).error) {
						console.log('erro ao cadastrar novo Orçamento!')
						response = JSON.parse(response)

						Swal.fire(
							'Erro!',
							'Ocorreu algum problema ao cadastrar',
							'error'
						)

						if (response['error_list']) {

							showErrorsModal(response['error_list'])

							Swal.fire(
								'Atenção!',
								'Por favor verifique os campos',
								'warning'
							)
						}

					} else { // Se cadastrou/atualizou com sucesso

						//$('#BudgetModal').modal('hide');

						res = JSON.parse(response)
						console.log("id do novo orçamento: " + res.idContrato)
						$('#fk_idOrcamento').val(res.idContrato)
						$('#idOrcamento').val(res.idContrato)
						

						$('#divListItens').attr('hidden', false); //mostra a parte da lista de produtos para adicionar

						Swal.fire(
							'Sucesso!',
							'Primeira parte do Orçamento cadastrada!',
							'success'
						)						

						$("#btnSaveBudget").hide();
						$("#btnFinalize").attr('hidden', false);						

						//loadTableBudgets();
						//$('#formBudget').trigger("reset");
						
						$('#btnDeleteBudget').show();
						$('#btnShowPDF').attr('href', `/budgets/${res.idContrato}/pdf/show`).show();
						$('#btnEmail').show();
					
					}

				},
				error: function (response) {
					//$('#BudgetModal').modal('hide');
					//$('#formBudget').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);

				}
			});

		} else { /* se for para Editar -------------------------------------------------- */

			//console.log('você quer editar o Orçamento: ' + idOrcamento)

			$.ajax({
				type: "POST",
				url: `/budgets/${idOrcamento}`, //rota para editar
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function () {
					clearErrors();
					$("#btnSaveBudget").parent().siblings(".help-block").html(loadingImg("Verificando..."));

				},
				success: function (response) {
					clearErrors();

					if (JSON.parse(response).error) {
						console.log('erro ao atualizar Orçamento!')

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
						//$('#BudgetModal').modal('hide');

						msg = 'Orçamento atualizado!';

						Swal.fire(
							'Sucesso!',
							msg,
							'success'
						);

						if($("#status").val() == "3"){ //se o orçamento foi aprovado
							window.location.assign(`/contracts/${idOrcamento}`);
						}
							
						//loadTableBudgets();
						//$('#formBudget').trigger("reset");
					}

				},
				error: function (response) {

					//$('#BudgetModal').modal('hide');
					//$('#formBudget').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);

				}
			});
		}

		return false;
	});

	$("#btnFinalize").click(function(){

		Swal.fire(
			'Sucesso!',
			'Orçamento cadastrado!',
			'success'
		)
	});

	$("#btnEmail").click(function () { //quando abrir o modal
		loadEmailFields(idOrcamento);
	});
});


//detalhes do Orcamento
async function loadBudget(idOrcamento) {
	//console.log(`function loadBudget(${idOrcamento})`)
	//console.log("idOrcamento:" + idOrcamento)

	//clearFieldsValues();
	clearErrors();

	$('#title').html('Detalhes do Orçamento')
	$('#btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
	$('#btnSaveBudget').hide();
	$('#btnUpdate').show();

	let idCliente, obra_idObra;

	if ((idOrcamento == '0') || (idOrcamento == undefined)) { //se for cadastrar um novo orçamento
		
		await clearFieldsValues();

		await showsNextNumber();

		disable = true

		$("#formBudget #dtInicio").parent().hide();
		$("#formBudget #dtFim").parent().hide();

		loadCostumers(idCliente, obra_idObra); //carrega clientes

	}else{ //se for para Editar um orçamento existente
		disable = false

	
		idCliente, obra_idObra = await loadFieldsBudget(idOrcamento); //busca os valores dos campos de Orçamento para preencher	
	}

	$("#formBudget #dtInicio").prop('disabled', disable);
	$("#formBudget #dtFim").prop('disabled', disable);

	$("#formBudget #codigo").prop('disabled', true);

	$("#idCliente").change(async function () {

		await loadConstructions($("#idCliente").val(), obra_idObra);
	});

	//await loadTableProducts(); //carrega lista de produtos para colocar no Carrinho
}

function showsNextNumber() { //mostra o próximo número de série relacionado à categoria
	//console.log('shows next number')
	$.ajax({
		type: "POST",
		url: `/budgets/showsNextNumber`,
		contentType: false,
		processData: false,

		success: function (response) {

			//console.log('próximo código de orçamento: ' + response)
			$('#codigo').val(response)

		},
		error: function (response) {

			console.log(`Erro! Mensagem: ${response}`);
		}
	});
}


async function loadFieldsBudget(idOrcamento) {
	console.log(`function loadFieldsBudget(${idOrcamento})`)

	$.getJSON(`/budgets/json/${idOrcamento}`, function (data) { //ajax
		console.log(data)

		$("#idOrcamento").val(data.idContrato);
		//console.log('load View Orcamento idOrcamento: ' + $("#idOrcamento").val())

		$("#formBudget #codigo").val(data.codContrato).prop('disabled', true);
		codigo = data.codContrato //seta o valor para mostrar no modal excluir
		$("#formBudget #nomeEmpresa").val(data.nomeEmpresa).prop('disabled', true);
		$("#formBudget #obra_idObra").prop('disabled', true);

		$("#formBudget #dtEmissao").val(data.dtEmissao).prop('disabled', true);
		$("#formBudget #solicitante").val(data.solicitante).prop('disabled', true);
		
		$("#telefone").unmask();
		$("#formBudget #telefone").val(data.telefone).prop('disabled', true);

		$("#formBudget #email").val(data.email).prop('disabled', true);
		//$("#formBudget #referencia").val(data.referencia).prop('disabled', true);

		$("#formBudget #status").val(data.statusOrcamento).prop('disabled', true);
		$("#formBudget #dtInicio").val(data.dtInicio).prop('disabled', true);
		$("#formBudget #dtAprovacao").val(data.dtAprovacao).prop('disabled', true);
		$("#formBudget #dtFim").val(data.dtFim).prop('disabled', true);
		$("#formBudget #notas").val(data.notas).prop('disabled', true);

		$("#formBudget #notas").val(data.notas).prop('disabled', true);


	}).then((data) => {
		
		$("#telefone").mask('(00) 0000-00009');
		
		$("#BudgetModal").modal();

		//$("#formBudget #idCliente").val(data.idCliente).prop('disabled', true);
		idCliente = data.idCliente;
		//console.log(`received idCliente: ${idCliente}`)

		//$("#formBudget #obra_idObra").val(data.obra_idObra).prop('disabled', true);
		obra_idObra = data.obra_idObra;
		//console.log(`received obra_idObra: ${obra_idObra}`)

		loadCostumers(idCliente, obra_idObra); //carrega clientes

		
		/* Atualizar Orcamento ------------------------------------------------------------------ */
		$('#btnUpdate').click(function () { //se eu quiser atualizar o Orcamento atual

			typeForm = 'save';

			$('#title').html('Editar Orçamento');
			$('#btnClose').html('Cancelar').removeClass('btn-primary').addClass('btn-danger');
			$('#btnSaveBudget').val('Atualizar').show();
			$('#btnUpdate').hide();

			//$("#formBudget #codigo").prop('disabled', false);
			
			$("#formBudget #nomeEmpresa").prop('disabled', false);
			$("#formBudget #idCliente").prop('disabled', false);
			$("#formBudget #obra_idObra").prop('disabled', false);
			$("#formBudget #dtEmissao").prop('disabled', false);
			$("#formBudget #solicitante").prop('disabled', false);
			$("#formBudget #telefone").prop('disabled', false);
			$("#formBudget #email").prop('disabled', false);

			$("#formBudget #status").prop('disabled', false);
			$("#formBudget #dtInicio").prop('disabled', false);
			$("#formBudget #dtAprovacao").prop('disabled', false);
			$("#formBudget #dtFim").prop('disabled', false);
			$("#formBudget #notas").prop('disabled', false);

			$('#btnCart').show();
			$('#divListItens .btnEdit').show();
			$('#divListItens .btnDelete').show();

			$('#divListItens .btnEdit').attr('hidden', false)
			$('#divListItens .btnDelete').attr('hidden', false)
			

		}); /* Fim Atualizar Orcamento ---------------------------------------------------------- */

		return [idCliente, obra_idObra];
		
	}).fail(function () {
		console.log("Rota não encontrada! (/budgets/json/:idOrcamento)");
	});
}

function deleteBudget(idOrcamento) {

	if(idOrcamento == 0){ //se o orçamento acabou de ser cadastrado

		idOrcamento = $('#idOrcamento').val();
		codigo = $("#codigo").val();
	}

	Swal.fire({
		title: `Você tem certeza de excluir o Orçamento nº ${codigo}?`,
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
				url: `/budgets/${idOrcamento}/delete`,
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
							'Orçamento apagado!',
							'success'
						)

						console.log(`orçamento ${idOrcamento} deletado`)
						window.location.assign("/budgets"); //vai para a página de orçamentos

						//loadTableBudget();
					}
				},
				error: function (response) {
					Swal.fire(
						'Erro!',
						'Não foi possível excluir o orçamento',
						'error'
					)
					console.log(`Erro! Mensagem: ${response}`);
				}
			});

		}
	})

	$('.swal2-cancel').html('Cancelar');
}

//carrega os Clientes para colocar no Orçamento
function loadCostumers(idCliente = '', obra_idObra = '') {

	//console.log('loading costumers')

	$("#idCliente").html(`<option value="">(escolha)</option>`);
	let costumers

	$.getJSON(`/costumers/json`, function (data) { //ajax

		//console.log(data)

		data.forEach(function (item) {
			//console.log(item)
			costumers += `<option value="${item.idCliente}">${item.nome}</option>`
		});

		$('#idCliente').append(costumers)


	}).then(() => {

		if(idCliente !== ''){ //se já tem um cliente escolhido
			$("#idCliente").val(idCliente).prop('disabled', true);
			//console.log(`setting value idCliente: ${idCliente}`)
		}

		loadConstructions(idCliente, obra_idObra)

	}).fail(function () {
		console.log("Rota não encontrada! (/budgets/costumers/json)");
		return false
	});

}

//carrega as opções de Obras para colocar no Orçamento
function loadConstructions(idCliente = null, obra_idObra = '') { //Carrega as Obras e em seguida os campos de orçamento (chamando outra função)
	console.log('loading constructions')

	if(idCliente == null) return;

	$("#obra_idObra").html("");

	console.log('idCliente: ' + idCliente)

	$.getJSON(`/costumers/json/${idCliente}/constructions`, function (data) { //ajax

		console.log(data)

		let constructions = `<option value="">(escolha)</option>`

		if(data.length == 0){
			constructions = `<option value="">Sem obra cadastrada</option>`
		}else{
			data.forEach(function (item) {
				//console.log(item)
				constructions += `<option value="${item.idObra}">${item.codObra}</option>`
			});
		}

		$("#obra_idObra").html(constructions)

	}).then(() => {

		$("#obra_idObra").val(obra_idObra)

	}).fail(function () {
		console.log("Rota não encontrada! (/costumers/json/${idCliente}/constructions");
		return false
	});

}

//limpar campos do modal para Cadastrar
function clearFieldsValues() {

	//$("#formBudget #codigo").prop('disabled', true)
	$('#btnUpdate').hide();
	$('#title').html('Cadastrar Orçamento');
	$('#btnClose').html('Fechar').removeClass('btn-danger').addClass('btn-secondary');
	$('#btnSaveBudget').val('Cadastrar').show();

	//$('#dtCadastro').parent().hide(); //aparece a data de cadastro (só para visualizar)

	//$("#formBudget #codigo").prop('disabled', false);

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
//detalhes do Orcamento
async function loadEmailFields(idOrcamento) {
	console.log(`function loadEmailFields(${idOrcamento})`)
	//console.log("idOrcamento:" + idOrcamento)

	//clearFieldsValues();
	clearErrors();

	if ((idOrcamento != '0') && (idOrcamento != undefined)) { //se já tiver um orçamento cadastrado
		$("#formEmail #name_from").val('TCC - teste (sistemalocacao)');
		$("#formEmail #toAdress").val(`${$("#formBudget #email").val()}`)
		$("#formEmail #toName").val(`${$("#formBudget #solicitante").val()}`)
		$("#formEmail #subject").val(
			`PROPOSTA N. ${$("#formBudget #codigo").val()} - FORNECEDOR X`
		)
		//mensagem do email
		$("#formEmail #html").val(`OBS: Este é um e-mai teste
REF: PROPOSTA DA EMPRESA X PARA LOCAÇÃO
	Olá, segue em anexo o arquivo PDF do Orçamento dos itens cotados para futura locação.

Atenciosamente,
Nome do Remetente
NOME DO FORNECEDOR - locações de equipamentos para construções`
		)
	
	}else{
		console.log('não pode enviar o e-mail, pois ainda não cadastrou o orçamento')
	}
	
	/* Cadastrar ou Editar Orcamento --------------------------------------------------------------*/
	$("#btnSendEmail").click(function (e) { //quando enviar o formulário de Orcamento
		e.preventDefault();

		let form = $('#formEmail');
		let formData = new FormData(form[0]);

		if ((idOrcamento != 0) || (idOrcamento != undefined)) { //se o orçamento já foi cadastrado 
			console.log("você quer mandar um e-mail")

			$.ajax({
				type: "POST",
				url: `/budgets/${idOrcamento}/pdf/sendEmail`,
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
						console.log('erro ao enviar Orçamento!')
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
							res.msg,//'Orçamento enviado para o cliente!',
							'success'
						)
					}

				},
				error: function (response) {
					//$('#BudgetModal').modal('hide');
					//$('#formBudget').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);

				}
			});

		} else { /* -------------------------------------------------- */

		}

		return false;
	});

}

