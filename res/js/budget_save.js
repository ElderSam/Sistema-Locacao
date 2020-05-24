var myTable = null

$(function () {	

	loadTableItens()
	//carrega a tabela de Budgets
	/*myTable = $("#dataTable").DataTable({
		"oLanguage": DATATABLE_PTBR, //tradução
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": false,
		"ajax": {
			"url": `/budgets/cart_list_datatables/${...}`, //chama a rota para carregar os dados 
			"type": "POST",

		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});*/

	loadCadaster();

	/*$("#btnAddBudget").click(function(){
		
		loadCadaster();
	});*/

	async function loadCadaster() {

		idOrcamento = $('#idOrcamento').val()
		//console.log("idOrcamento:" + idOrcamento)

		if ((idOrcamento == 0) || (idOrcamento == undefined)) {

			disable = true

			$("#formBudget #dtInicio").parent().hide();
			$("#formBudget #prazoDuracao").parent().hide();
			$("#formBudget #periodoFatura").parent().hide();

		}else{
			disable = false
		}

		$("#formBudget #dtInicio").prop('disabled', disable);
		$("#formBudget #prazoDuracao").prop('disabled', disable);
		$("#formBudget #periodoFatura").prop('disabled', disable);


		//adiciona as máscaras
		$("#telefone").mask("(00) 0000-00009", {placeholder:"(00)0000-0000"});
		$("#dtInicio").mask("dd/mm/aaaa");

		$("#formBudget #codigo").prop('disabled', true);


		await clearFieldsValues();

		await showsNextNumber();

		await loadCostumers(); //carrega clientes

		$("#idCliente").change(async function () {
			
			await loadConstructions();
		});

		//await loadTableProducts(); //carrega lista de produtos para colocar no Carrinho
	}

	$("#btnSaveRent").click(function (e) { //salva o aluguel (az reserva do produto para alugar)
		e.preventDefault();

		let form = $('#formRent'); //formulário de aluguel do produto
		let formData = new FormData(form[0]);

		idItem = $('#idItem').val()
		console.log("salvar item, idItem:" + idItem)

		if ((idItem == 0) || (idItem == undefined)) { //se for para cadastrar --------------------------------------------------

			//console.log("você quer cadastrar um Aluguel")

			$.ajax({
				type: "POST",
				url: '/contract_itens/create',
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function () {
					clearErrors();
					$("#btnSaveRent").parent().siblings(".help-block").html(loadingImg("Verificando..."));

				},
				success: function (response) {
					clearErrors();

					if (JSON.parse(response).error) {
						console.log('erro ao cadastrar Aluguel!')
						response = JSON.parse(response)

						Swal.fire(
							'Erro!',
							'Ocorreu algum problema ao adicionar produto',
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


					} else { // Se cadastrou com sucesso

						//$('#BudgetModal').modal('hide');

						res = JSON.parse(response)
						console.log("id do novo orçamento: " + res.idContrato)
						// $('#idOrcamento').val(res.idContrato)

						//$('#divListItens').attr('hidden', false) //mostra a parte da lista de produtos para adicionar

						Swal.fire(
							'Sucesso!',
							'Item adicionado ao Orçamento!',
							'success'
						)

						//loadTableBudget();
						//$('#formBudget').trigger("reset");

					}

				},
				error: function (response) {
					//$('#BudgetModal').modal('hide');
					//$('#formBudget').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);

				}
			}).then(response => {

				response = JSON.parse(response)

				if(!response['error_list']){ //se o array de erros está vazio
					
					//console.log('nova locação/aluguel: ')
					
					//console.log(response)

					//loadTableBudget()
					$("#productModal").modal('hide'); //esconde o modal
					clearFieldsItem();
					
					loadTableItens();

				}
				

			});

		}


	});

	/*function loadTableBudget() { //carrega a tabela dos produtos adicionados ao orçamento atual

		id = $('#fk_idOrcamento').val();

		myTable.destroy(); //desfaz as paginações

		myTable = $("#dataTable").DataTable({
			"oLanguage": DATATABLE_PTBR,
			"autoWidth": false, //largura
			"processing": true, //mensagem 'processando'
			"serverSide": true,
			"ajax": {
				"url": `/budgets/cart_list_datatables/${id}`, //para chamar o método ajax_list_budget
				"type": "POST",
			},
			"columnDefs": [
				{ targets: "no-sort", orderable: false }, //para não ordenar
				{ targets: "text-center", className: "text-center" },
			]
		});
	}*/

	/* Cadastrar ou Editar Orcamento --------------------------------------------------------------*/
	$("#btnSaveBudget").click(function (e) { //quando enviar o formulário de Orcamento

		e.preventDefault();

		//adiciona as máscaras
		$("#telefone").unmask();

		$("#formBudget #codigo").prop('disabled', false);

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



					} else { // Se cadastrou com sucesso

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

						$("#btnSaveBudget").attr('value', 'Finalizar');

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

						Swal.fire(
							'Sucesso!',
							'Orçamento atualizado!',
							'success'
						);

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


	/* Itens de orçamento */
	$("#btnAddProduct").on('click', function (e) { //ao clicar para adicionar um novo
		e.preventDefault()

		searchProduct();

	});

});

function removeProduct(id) {

	$(`#${id}`).html('').hide();

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


function loadFieldsBudget(idOrcamento) {
	alert('loadFieldsBudget')
	//console.log(`loading all fields of budget (id = ${idOrcamento}`)

	$.getJSON(`/budgets/json/${idOrcamento}`, function (data) { //ajax
		console.log(data)

		$("#idOrcamento").val(data.idContrato);
		//console.log('load View Orcamento idOrcamento: ' + $("#idOrcamento").val())

		$("#formBudget #codigo").val(data.codContrato).prop('disabled', true);
		$("#formBudget #obra_idObra").val(data.obra_idObra).prop('disabled', true);
		$("#formBudget #dtEmissao").val(data.dtEmissao).prop('disabled', true);
		$("#formBudget #solicitante").val(data.solicitante).prop('disabled', true);
		$("#formBudget #telefone").val(data.telefone).prop('disabled', true);
		$("#formBudget #email").val(data.email).prop('disabled', true);
		//$("#formBudget #referencia").val(data.referencia).prop('disabled', true);

		$("#formBudget #status").val(data.statusOrcamento).prop('disabled', true);

		$("#formBudget #dtAprovacao").val(data.dtAprovacao).prop('disabled', true);
		$("#formBudget #prazoDuracao").val(data.prazoDuracao).prop('disabled', true);

		/*$("#formBudget #custoEntrega").val(data.custoEntrega).prop('disabled', true);
		$("#formBudget #custoRetirada").val(data.custoRetirada).prop('disabled', true);*/
		$("#formBudget #notas").val(data.notas).prop('disabled', true);
		$("#formBudget #valorAluguel").val(data.valorAluguel).prop('disabled', true);


		/* Atualizar Orcamento ------------------------------------------------------------------ */
		$('#btnUpdate').click(function () { //se eu quiser atualizar o Orcamento atual

			$('#title').html('Editar Orcamento');
			$('#btnClose').html('Cancelar').removeClass('btn-primary').addClass('btn-danger');
			$('#btnSaveBudget').val('Atualizar').show();
			$('#btnUpdate').hide();

			//$("#formBudget #codigo").prop('disabled', false);
			$("#formBudget #obra_idObra").prop('disabled', false);
			$("#formBudget #dtEmissao").prop('disabled', false);
			//$("#formBudget #referencia").prop('disabled', false);

			$("#formBudget #status").prop('disabled', false);
			$("#formBudget #dtAprovacao").prop('disabled', false);
			$("#formBudget #prazoDuracao").prop('disabled', false);

			/*$("#formBudget #custoEntrega").prop('disabled', false);
			$("#formBudget #custoRetirada").prop('disabled', false);*/
			$("#formBudget #notas").prop('disabled', false);
			$("#formBudget #valorAluguel").prop('disabled', false);

		}); /* Fim Atualizar Orcamento ---------------------------------------------------------- */


	}).then(() => {

		$("#BudgetModal").modal();
	}).fail(function () {
		console.log("Rota não encontrada! (/budgets/json/:idOrcamento)");
	});
}


//detalhes do Orcamento
function loadBudget(idBudget) { //carrega todos os campos do modal referente ao Orcamento escolhido

	//console.log('loading budgets')

	loadConstructions(loadFieldsBudget, idBudget); //carrega as obras e em seguida, todos os campos de orçamento/contrato

	clearFieldsValues();
	clearErrors();

	$('#title').html('Detalhes do Orcamento')
	$('#btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
	$('#btnSaveBudget').hide();
	$('#btnUpdate').show();

}

function deleteBudget(idOrcamento) {

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
							'Registro apagado!',
							'success'
						)

						//loadTableBudget();
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

//carrega os Clientes para colocar no Orçamento
function loadCostumers() {

	console.log('loading costumers')

	$("#idCliente").html(`<option value="">(escolha)</option>`);
	let costumers

	$.getJSON(`/costumers/json`, function (data) { //ajax

		//console.log(data)

		costumers = `<option value="">(escolha)</option>`

		data.forEach(function (item) {
			//console.log(item)
			costumers += `<option value="${item.idCliente}">${item.nome}</option>`
		});


	}).then(() => {

		$('#idCliente').html(costumers)

	}).fail(function () {
		console.log("Rota não encontrada! (/budgets/costumers/json)");
		return false
	});

}

//carrega as opções de Obras para colocar no Orçamento
function loadConstructions(callback = '', idBudget = false) {
	//alert('loading constructions')
	console.log('loading constructions')

	$("#obra_idObra").html(`<option value="">(escolha)</option>`);
	$("#obra_idObra").append(`<option value="1">TESTE 1/2020 (número/ano)</option>`);

	$.getJSON(`/constructions/json`, function (data) { //ajax

		console.log(data)

		let constructions = `<option value="">(escolha)</option>`

		data.forEach(function (item) {
			//console.log(item)
			constructions += `<option value="${item.idObra}">${item.codObra} - ${item.descCategoria}</option>`
		});

		$('#obra_idObra').html(constructions)


	}).then(() => {

		if (idBudget) { //quando for atualizar um cadastro existente
			callback(idBudget) //executa a função loadFieldsBudget()
		}


	}).fail(function () {
		console.log("Rota não encontrada! (/budgets/constructions/json)");
		return false
	});

}


//limpar campos do modal para Cadastrar
function clearFieldsValues() {

	//$("#formBudget #codigo").prop('disabled', true)
	$('#title').html('Cadastrar Orçamento');
	$('#btnClose').html('Fechar').removeClass('btn-danger').addClass('btn-secondary');
	$('#btnSaveBudget').val('Cadastrar').show();
	$('#btnUpdate').hide();

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


	//$('#idFornecedor').val('0');

}



/*----------------------------------------------- Itens de orçamento --------------------------------------- */
function searchProduct() {
	console.log('searchProduct:')

	if (($('#codeProduct').val() != undefined) && ($('#codeProduct').val() != "")) {

		code = $('#codeProduct').val();

		$('#btnAddProduct').html(`<div class="help-block">${loadingImg("Buscando...")}</div>`)
							.removeClass('btn-success')
							.addClass('btn-light'); //cor cinza-claro (Bootstrap)

		idContrato = $("#fk_idOrcamento").val()

		$.getJSON(`/budgets/addProductToContract/${idContrato}/${code}`, function (response) { //requisição ajax que retorna um JSON

			console.log(response)

			if (response.error) {
				console.log('erro ao adicionar produto ao orçamento!')

				Swal.fire(
					'Atenção!',
					response.msg,
					'warning'
				)

			} else {

				Swal.fire(
					'Adicionado!',
					'O Produto foi relacionado ao item!',
					'success'
				)

				$('#idProduto_gen').val(response.idProduto_gen)

				$('#produto').css('border', '1px solid black')
				$('#produtoAdicionado').html(`<strong style="color: green;">Produto Selecionado!</strong>`)

				$('#prodCodigo').html(`<strong>Código:</strong> ${response.codigoGen}`)
				$('#prodDescricao').html(`<strong>Descrição:</strong> ${response.descCategoria} - ${response.descricao}`)

				$('#codeProduct').val('');
				console.log('vlBaseAluguel R$ '+ response.vlBaseAluguel)

				$('#vlAluguel').val(response.vlBaseAluguel)
			}


		}).then(() => {

			$('#btnAddProduct').html('Adicionar')
							.removeClass('btn-light')
							.addClass('btn-success'); //volta a cor verde (Bootstrap)

			clearErrors();

		}).fail(function () {
			console.log(`Rota não encontrada! (/products/addProductToContract/${code})`);
			return false
		});
	}

}


function loadTableItens(){ //carrega a tabela de Fornecedores

	console.log('loading Table ContractItens')

	idContrato = $("#fk_idOrcamento").val()
	if(myTable != null){
		myTable.destroy(); //desfaz as paginações
	}

		//carrega a tabela de itens
	//function 

	if(idContrato != "" && idContrato != undefined){

	
		myTable = $("#dataTable").DataTable({ 
			"createdRow": function( row, data, dataIndex){
				/*console.log('info data:')
				console.log(data)
				console.log(row)*/

				if( data['options'] == ""){ //se for linha de entrega ou retirada (frete)
					
					$(row).addClass('linhaFrete'); 
				}
			},
			"oLanguage": DATATABLE_PTBR, //tradução
			"autoWidth": false, //largura
			"processing": true, //mensagem 'processando'
			"serverSide": true, 
			"ajax": {
				"url": `/contract_itens/list_datatables/${idContrato}`, //chama a rota para carregar os dados 
				"type": "POST",
				dataSrc: function (json) {
					
					rows = [];

					json.data.forEach(element => {
						console.log(element)

						row = []

						categoria = element.descCategoria.toUpperCase()

						//row['id'] = element.id
						row['descricao'] = `<strong>${categoria} ${element.descricao}</strong>`
						row['periodoLocacao'] = element.periodoLocacao
						row['vlAluguel'] = paraMoedaReal(Number(element.vlAluguel))

						row['quantidade'] = element.quantidade

						vlTotal = element.vlAluguel * element.quantidade
						//vlTotalContrato += vlTotal
						row['vlTotal'] = paraMoedaReal(vlTotal)

						row['observacao'] = element.observacao

						row['options'] = `<button type='button' title='ver detalhes' class='btn btn-warning btnEdit'
						onclick='loadSupplier(${element.id});'>
							<i class='fas fa-bars sm'></i>
						</button>
						<button type='button' title='excluir' onclick='deleteSupplier(${element.id});'
							class='btn btn-danger btnDelete'>
							<i class='fas fa-trash'></i>
						</button>`

						rows.push(row)

						//if(element.custoEntrega != 0){ //se tem custo de entrega ou retirada
							//custo de Entrega
							row = []
							
							row['descricao'] = "<div class='frete'></div>Entrega"
							row['periodoLocacao'] = ""
							row['vlAluguel'] = paraMoedaReal(Number(element.custoEntrega))
							row['quantidade'] = element.quantidade
	
							vlTotal = element.custoEntrega * element.quantidade
							//vlTotalContrato += vlTotal
							row['vlTotal'] = paraMoedaReal(vlTotal)

							row['observacao'] = ""
							row['options'] = ""
							rows.push(row)

							//custo de Retirada
							row = []
							row['descricao'] = "<div class='frete'></div>Retirada"
							row['periodoLocacao'] = ""
							row['vlAluguel'] = paraMoedaReal(Number(element.custoRetirada))
							row['quantidade'] = element.quantidade
	
							vlTotal = element.custoRetirada * element.quantidade
							//vlTotalContrato += vlTotal
							row['vlTotal'] = paraMoedaReal(vlTotal)
							
							row['observacao'] = ""
							row['options'] = ""
							rows.push(row)				
					
					});

					//atualizaValorTotal(); //valor total do orçamento/contrato
					
					return rows;
				},


			},
			"columns": [
				{ "data": "descricao" },
				{ "data": "periodoLocacao" },
				{ "data": "vlAluguel" },
				{ "data": "quantidade" },
				{ "data": "vlTotal" },
				
				{ "data": "observacao" },

				//{ "data": "custoEntrega" },
				//{ "data": "custoRetirada" },
				{ "data": "options" },

				/*"idItem"=>$contractItem['idItem'],
				"idContrato"=>$contractItem['idContrato'],
				"idProduto_gen"=>$contractItem['idProduto_gen'],

				"descricao"=>$contractItem['descricao'],
				"periodoLocacao"=>$contractItem['periodoLocacao'],
				"vlAluguel"=>$contractItem['vlAluguel'],
				"quantidade"=>$contractItem['quantidade'],

				"custoEntrega"=>$contractItem['custoEntrega'],
				"custoRetirada"=>$contractItem['custoRetirada'], 
				"observacao"=>$contractItem['observacao'],      */   
						
			],
			"columnDefs": [
				{ targets: "no-sort", orderable: false }, //para não ordenar
				{ targets: "text-center", className: "text-center" },
			],
		});

	}else{

		myTable = $("#dataTable").DataTable({ 
			"oLanguage": DATATABLE_PTBR, //tradução
			"autoWidth": false, //largura
			"processing": true, //mensagem 'processando'
			"serverSide": false, 
			
			"columnDefs": [
				{ targets: "no-sort", orderable: false }, //para não ordenar
				{ targets: "text-center", className: "text-center" },
			]
		});

	}

}




//limpar campos do modal para Cadastrar
function clearFieldsItem() {

	$("#produtoAdicionado").html('');
	$("#prodCodigo").html('');
	$("#prodDescricao").html('');

	$('#idProduto_gen').val('');

	$('#vlAluguel').val('');
	$('#quantidade').val('');

	$('#custoEntrega').val('');
	$('#custoRetirada').val('');
	$('#observacao').val('');

	$("#qtdEntrega, #qtdRetirada").html('0');
	
	//quantidades e valores totais
	vlTotalItem = 0;
	vlTotalEntrega = 0;
	vlTotalRetirada = 0;

	$("#vlTotalItem").html(vlTotalItem)
	$("#vlTotalEntrega").html(vlTotalEntrega)
	$("#vlTotalRetirada").html(vlTotalRetirada)
	


}

$(function () {


	$("#vlAluguel").change(function(){
		calculaTotalItem();
	});

	$("#quantidade").change(function(){

		$("#qtdEntrega, #qtdRetirada").html(`${$("#quantidade").val()}`)
		calculaTotalItem();
		calculaTotalEntrega();
		calculaTotalRetirada();
	});

	$("#custoEntrega").change(function(){

		calculaTotalEntrega();
	});

	$("#custoRetirada").change(function(){

		calculaTotalRetirada();			
	});
});


//let vlTotalContrato = 0;

let vlTotalItem = 0;
let vlTotalEntrega = 0;
let vlTotalRetirada = 0;

function calculaTotalItem(){
			
	vlTotalItem = $("#vlAluguel").val() * $("#quantidade").val()	
	$("#vlTotalItem").html(paraMoedaReal(vlTotalItem))
}

function calculaTotalEntrega(){
	vlTotalEntrega = $("#custoEntrega").val() * $("#quantidade").val()
	$("#vlTotalEntrega").html(paraMoedaReal(vlTotalEntrega))
}

function calculaTotalRetirada(){
	vlTotalRetirada = $("#custoRetirada").val() * $("#quantidade").val()
	$("#vlTotalRetirada").html(paraMoedaReal(vlTotalRetirada))
}

/*function atualizaValorTotal(){ //Valor total do Orçamento

	$("#valorTotal").val(vlTotalContrato);
	$("#vlTotalContrato").html(paraMoedaReal(vlTotalContrato));
	
}*/