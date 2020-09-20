var myTable = null

$(function() { //quando a página carrega

	loadTableRents()

	$("#btnAddRent").click(function(){ /* quando clica no botão para abrir o modal (cadastrar ou editar) */
		let i = 0
		clearFieldsValues();
		loadCostumers("");

		// if(i == 0){
		// 	showsNextNumber();
		// }
		
	});
	 
	/* Cadastrar ou Editar Locacao --------------------------------------------------------------*/	
	$("#btnSaveRent").click(function(e) { //quando enviar o formulário de Locacao
		e.preventDefault(); 

		//loadCostumers('');
		
		let form = $('#formRent');
		let formData = new FormData(form[0]);
		console.log(formData)
		idLocacao = $('#idHistoricoAluguel').val()
		//console.log("id:" + idHistoricoAluguel)

		//formData.append("field", "value")
		formData.append(
			"arrSelectedProductsEsp", //nome do array
			JSON.stringify(getSelectedProducts() //valor do array
		)); //insere o array de produtos selecionados no 'array' do corpo do formulário à ser enviado para o backend

		
		function sendForm() {

			console.log('sendForm')

			$.ajax({
				type: "POST",
				url: `/rents/${route}`, 
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
						console.log(`erro ao ${msgError} Locação!`)
	
						response = JSON.parse(response)
	
						Swal.fire(
							'Erro!',
							`Ocorreu algum erro ao ${msgError}`,
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
							msgSuccess,
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
		console.log(idLocacao)
		if((idLocacao == 0) || (idLocacao == undefined)){ //se for para cadastrar --------------------------------------------------

			console.log("você quer cadastrar")
		
			route = "create";
			msgError = "Cadastrar";
			msgSuccess = "Locação(ões) Cadastrada(s)";

			sendForm();

		}else{ /* se for para Editar -------------------------------------------------- */

			console.log('você quer editar o Aluguel: ' + idLocacao)
			
			route = idLocacao //parte rota para editar (/rents/:id)
			msgError = "Atualizar";
			msgSuccess = "Locação Atualizada";

			if($("#status").val() == 3) { //aluguel status=encerrado

				Swal.fire({
					title: 'Você tem certeza de Encerrar essa locação?',
					text: "Você não será capaz de reverter isso! OBS: o produto específico relacionado se tornará disponível para outras locações",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#d33',
					cancelButtonColor: '#3085d6',
					confirmButtonText: 'Sim, encerrar!'
			
				}).then((result) => {

					if(result.value) {
						console.log('atualizar, continua ...')
						sendForm();
					} else {
						console.log('atualizar, NÃO')
						return;
					}
				});

				$('.swal2-cancel').html('Cancelar');

			}else {
				sendForm();
			}
		}	


		return false;
	});

});

function getSelectedProducts() { //pega os produtos selecionados (checkboxes) e retorna um array
	listProductsEsp = document.querySelectorAll(".items input[type=checkbox]");
	console.log(listProductsEsp)
	let selectedProducts = [];

	listProductsEsp.forEach((product, index) => {
		if(product.checked){
			console.log(parseInt(product.value), index)
			selectedProducts.push({id: parseInt(product.value)})
		}

	});
	
	console.log(selectedProducts)
	return selectedProducts;
}


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
				console.log(json)
				rows = [];

				json.data.forEach(element => {
					//console.log(element)

					row = []

					//Essa variavel você pode apresentar
					// var telFormatado = mascaraTelefone(element.telefone1);

					//row['id'] = element.id

					if(element.dtFinal == "0000-00-00") {
						dtFinal = " - ";

					} else {
						dtFinal = formatDateToShow(element.dtFinal);
					}

					if (element.status == 0) {
						txtStatus = 'Entrega Pendente';
						color = 'red';
		
					} else if (element.status == 1) {
						txtStatus = 'Ativo ';
						color = 'green';
		
					} else if (element.status == 2) {
						txtStatus = 'Retirada Pendente';
						color = 'orange';
		
					} else if (element.status == 3) {
						txtStatus = 'Encerrado';
						color = 'grey';
					}
					

					row['codigo'] = element.codigo
					row['codContrato'] = element.codContrato
					row['produto'] = `<a href="./products_esp/${element.idProduto_gen}" target="_blank">${element.codigoEsp}</a>` //código do produto específico
					row['status'] = `<b style='color: ${color}'>${txtStatus}</b>`
					row['dtInicio'] = formatDateToShow(element.dtInicio)
					row['dtFinal'] = dtFinal
					//row['cliente'] = element.cliente_idCliente
					//row['contrato'] = element.contrato_idContrato
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
			{ "data": "codigo" },
			{ "data": "codContrato"},
			{ "data": "produto" },
			{ "data": "status" },
			{ "data": "dtInicio" },
			{ "data": "dtFinal" },
			/*{ "data": "cliente" },
			{ "data": "contrato" },*/
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
	
	console.log(`load rent id: ${idLocacao}`)
	clearFieldsValues();
	clearErrors();

	$('#modalTitle').html('Detalhes do Locação')
	$('#btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
	$('#btnSaveRent').hide();
	$('#btnUpdate').show();

	$.getJSON(`/rents/json/${idLocacao}`, function (data) { //ajax
		
		console.log(data)
			
		$("#idHistoricoAluguel").val(data.idHistoricoAluguel);
		console.log('load View Locacao idLocacao: ' + $("#idHistoricoAluguel").val())

		//cliente (codigoCliente + nomeCliente), contrato (codContrato), item (descCategoria + descricao) 
		$("#formRent #codigo").val(data.codigo).prop('disabled', true);
		
		$("#cliente").parent().hide();
		$("#contrato_idContrato").parent().hide();
		$("#itens").parent().hide();
		$("#formRent #quantidade").val("").prop('disabled', true).parent().hide();

		$("#divDetailsRent").html(`
			<div id="divCliente">Cliente: <b>${data.codigoCliente} - ${data.nomeCliente}</b></div>
			<div id="divContrato">Contrato: <b>${data.codContrato}</b></div>
			<div id="divItem">Item: <b>${data.descCategoria} ${data.descricao}</b></div>
			<div id="divProduto">Cod. produto: <b>${data.codigoEsp}</b></div>
		`).show();

		$("#list1").prop('disabled', true).hide();
		/*loadListProductEsp(data.idProduto_gen, {
			idProduto_esp: data.idProduto_esp, //manda o produto atual para ser selecionado por padrão
			codigo: data.codigoEsp
		})*/

		$("#formRent #status").val(data.status).prop('disabled', true);
		$("#formRent #vlAluguel").val(data.vlAluguel).prop('disabled', true);
		$("#formRent #dtInicio").val(data.dtInicio).prop('disabled', true);
		$("#formRent #dtFinal").val(data.dtFinal).prop('disabled', true);
		$("#formRent #custoEntrega").val(data.custoEntrega).prop('disabled', true);
		$("#formRent #custoRetirada").val(data.custoRetirada).prop('disabled', true);
		$("#formRent #observacao").val(data.observacao).prop('disabled', true);
		
		/* Atualizar Locacao ------------------------------------------------------------------ */
		$('#btnUpdate').click(function(){ //se eu quiser atualizar o Locacao atual

			$('#modalTitle').html('Editar Locacão');
			$('#btnClose').html('Cancelar').removeClass('btn-primary').addClass('btn-danger');
			$('#btnSaveRent').val('Atualizar').show();
			$('#btnUpdate').hide();
		
			//$('#desImagePath').parent().show();

			$("#formRent #idHistoricoAluguel").prop('disabled', false);
			//$("#formRent #codigo").prop('disabled', true);	
			$("#formRent #status").prop('disabled', false);
			$("#formRent #vlAluguel").prop('disabled', false);
			$("#formRent #dtInicio").prop('disabled', false);
			$("#formRent #dtFinal").prop('disabled', false);
			$("#formRent #custoEntrega").prop('disabled', false);
			$("#formRent #custoRetirada").prop('disabled', false);
			//$("#formRent #quantidade").prop('disabled', false);
			$("#formRent #observacao").prop('disabled', false);
				
		}); /* Fim Atualizar Locacao ---------------------------------------------------------- */
			
	}).then(() => { 

		$("#RentModal").modal();

	}).catch(function () {
		console.log(`Rota não encontrada! (/rents/json/${idLocacao})`);
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
	$('#modalTitle').html('Cadastrar Locacão');
	$('#btnClose').html('Fechar').removeClass('btn-danger').addClass('btn-secondary');
	$('#btnSaveRent').val('Cadastrar').show();
	$('#btnUpdate').hide();
	$("#formRent #idHistoricoAluguel").hide();
	//$('#dtInicio').parent().hide(); //aparece a data de cadastro (só para visualizar)
	//$('#desImagePath').parent().show();

	$("#divDetailsRent").html("").hide();
	$("#cliente").parent().show();
	$("#contrato_idContrato").parent().show();
	$("#itens").parent().show();
	$("#quantidade").parent().show();
	$("#list1").prop('disabled', false).show();

	$("#formRent #idHistoricoAluguel").prop('disabled', false);
	$("#formRent #codigo").prop('disabled', false);
	$("#formRent #cliente").prop('disabled', false);
	$("#formRent #contrato_idContrato").prop('disabled', false);
	$("#formRent #itens").prop('disabled', false);
	$("#formRent #status").prop('disabled', false);
	$("#formRent #vlAluguel").prop('disabled', false);
	//$("#formRent #group-dtInicio").hide();
	$("#formRent #dtInicio").prop('disabled', false);
	$("#formRent #dtFinal").prop('disabled', false);
	$("#formRent #custoEntrega").prop('disabled', false);
	$("#formRent #custoRetirada").prop('disabled', false);
	$("#formRent #quantidade").prop('disabled', false);
	$("#formRent #observacao").prop('disabled', false);

	$('#idHistoricoAluguel').val('');
	$('#codigo').val('');
	$('#cliente').val('');
	$('#contrato_idContrato').val('');
	$('#itens').val('');
	$('#status').val('0');
	$('#vlAluguel').val('');
	$('#dtInicio').val('');
	$('#dtFinal').val('');
	$('#custoEntrega').val('');
	$('#vlRetirada').val('');
	$('#quantidade').val('');
	$('#obseracao').val('');
	
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

//Usado para deixar visivel o itens do dropdown de produtos específicos
var checkList = document.getElementById('list1');

checkList.getElementsByClassName('anchor')[0].onclick = function (evt) {
	if (checkList.classList.contains('visible'))
		checkList.classList.remove('visible');
	else
		checkList.classList.add('visible');
}


//carrega os Clientes 
function loadCostumers(idCliente = '') {

	//console.log('loading costumers')

	$('#cliente').html("");

	let costumers = "<option value=''>(escolha)</option>";

	$.getJSON(`/costumers/json`, function (data) { //ajax


		data.forEach(function (item) {
			//console.log(item)
			costumers += `<option value="${item.idCliente}">${item.nome}</option>`
		});

		
		$('#cliente').append(costumers)


	}).then(() => {

		if(idCliente !== ''){ //se já tem um cliente escolhido
			$("#cliente").val(idCliente).prop('disabled', true);
		}
				
		$("#cliente").on("change", function() {
			var valor = $(this).val();   // aqui vc pega cada valor selecionado com o this
			//alert("evento disparado e o valor é: " + valor);
			loadContracts(valor);
		})


	}).fail(function () {
		console.log("Rota não encontrada!");
		return false
	});

}

//carrega os Contratos
function loadContracts(idCliente = ''){

	console.log(`Id Cliente: ${idCliente}, buscando contratos ...`);

	txtEscolha = `<option value="">(escolha)</option>`;
	$("#contrato_idContrato").html("");
	$("#itens").html(txtEscolha)
	
	$.getJSON(`/contracts/json/${idCliente}/contracts`, function (data) { //ajax
		console.log(data)

		//alert("Lista: " + data)
		contracts = ""; //esvazia a lista de opções

		if(data.length == 0){
			contracts = `<option value="">Sem contratos cadastrados</option>`
		}else{

			$("#contrato_idContrato").html(txtEscolha);

			data.forEach(function (item) {
				//console.log(item)
				contracts += `<option value="${item.idContrato}">${item.codContrato}</option>`
			});
		}

		$("#contrato_idContrato").append(contracts)

	}).then(() => {

		var comboNome = document.getElementById("contrato_idContrato");

        if (comboNome.options[comboNome.selectedIndex].value != "" ){
			var codigo = comboNome.options[comboNome.selectedIndex].value;
			$("#contrato_idContrato").val(codigo).prop('disabled', true);
		}	

		$("#contrato_idContrato").on("change", function() {
			var valor = $(this).val();   // aqui vc pega cada valor selecionado com o this
			
			loadContractItens(valor);
		})

	}).fail(function () {
		console.log(`Rota não encontrada! (//contracts/json/${idCliente}/contracts`);
		return false
	});

}

//carrega itens do Contrato selecionado
function loadContractItens(idContract = false){

	console.log(`carregando itens para o contrato id: ${idContract}`);

	$.getJSON(`/contract_itens/json/contract/${idContract}`, (data) => {
		console.log(data)
		
		$("#itens").html("");

	}).then((data) => {


		txtListItens = ""; //esvazia a lista de opções

		if(data.length == 0){
			txtListItens = `<option value="">Sem itens neste contrato</option>`;
		}else{

			txtListItens = `<option value="">(escolha)</option>`;
			let arrItens = [];

			data.forEach(function (item) {
				//console.log(item)
				arrItens.push(item);
				txtListItens += `<option value="${item.idItem}">${item.descCategoria} ${item.descricao}</option>`
			});	

			$("#itens").on("change", function() { //quando é escolhido um item ----------------------------------
				loadItemFields(arrItens);
			}); // ---------------------------------- 
		}

		$("#itens").append(txtListItens);



	}).catch(() => {

		console.log(`Rota não encontrada! (/contract_itens/json/contract/${idContract}`);
		return false
	})
}

function loadItemFields(arrItens) {

	let item; //item selecionado

	item = arrItens.find((item) => item.idItem == $("#itens").val());

	console.log("item selecionado:");
	console.log(item)

	//atribuindo valores do Item para os campos; vlAluguel, custoEntrega, custoRetirada e quantidade.
	$("#vlAluguel").val(item.vlAluguel);
	$("#custoEntrega").val(item.custoEntrega);
	$("#custoRetirada").val(item.custoRetirada);
	
	$("#quantidade").html("");

	if(item.quantidade > 0) {
		txtQuantidade = "<option value=''>(escolha)</option>";

		for(i=1; i<=item.quantidade; i++){ //gera opções de 1 até a quantidade máxima
			txtQuantidade += `<option value="${i}">${i}</option>`;
		}

	} else {
		txtQuantidade = "<option value=''>0</option>"; //Não possue mais quantidade disponível do contrato
	}


	$("#quantidade").append(txtQuantidade);
	  
	loadListProductEsp(item.idProduto_gen);
}

function loadListProductEsp(idProduto_gen, selected=false) { /* carrega os checkboxes de produtos específicos (que estão disponíveis)*/
	console.log(`buscando produtos específicos para o idProduto_gen: ${idProduto_gen}`);

	$("#listProductsEsp").html("") //esvazia a lista

	if(selected) {  //seleciona o produto
		$("#listProductsEsp").append(`<li><input type="checkbox" checked="true" value=${selected.idProduto_esp}/>${selected.codigo} <b>(atual)</b></li>`)
	}

	$route = `/products_esp/json/product_gen/${idProduto_gen}`;

	$.getJSON($route)
	.then((data) => {
		console.log(data);
		let txtListProducts;

		const getTxtProducts = (txt, { idProduto_esp, codigoEsp }) => { //retorna uma string com os inputs para escolher produtos específicos
			return txt + `<li><input type="checkbox" value=${idProduto_esp}/>${codigoEsp} </li>`
		}
		
		txtListProducts = data.reduce(getTxtProducts, "");
		console.log(txtListProducts)

		$("#listProductsEsp").append(txtListProducts)

	})
	.catch(() => {
		console.log(`Rota não encontrada! ${route}`);
		return false;
	})

}