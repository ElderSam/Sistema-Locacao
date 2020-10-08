var myTable = null

$(function() { //quando a página carrega

	loadTableFreights()

	$("#btnAddFreights").click(function(){ /* quando clica no botão para abrir o modal (cadastrar ou editar) */
		let i = 0
		clearFieldsValues();
		loadFreigths("");

		// if(i == 0){
		// 	showsNextNumber();
		// }
		
	});
	
	/* Cadastrar ou Editar Locacao --------------------------------------------------------------*/	
	$("#btnSaveFreights").click(function(e) { //quando enviar o formulário de Locacao
		e.preventDefault(); 
		
		let form = $('#formFreights');
		let formData = new FormData(form[0]);
		console.log(formData)
		idFreteAluguel = $('#idFreteAluguel').val()
		//console.log("id:" + idHistoricoAluguel)

		//formData.append("field", "value")
		// formData.append(
		// 	"arrSelectedProductsEsp", //nome do array
		// 	JSON.stringify(getSelectedProducts() //valor do array
		// )); //insere o array de produtos selecionados no 'array' do corpo do formulário à ser enviado para o backend

		
		function sendForm() {

			console.log('sendForm')

			$.ajax({
				type: "POST",
				url: `/freights/${route}`, 
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
						console.log(`erro ao ${msgError} Frete!`)
	
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
						$('#freightModal').modal('hide');
	
						Swal.fire(
							'Sucesso!',
							msgSuccess,
							'success'
						);
	
						loadTableFreights();
						$('#formFreights').trigger("reset");
					}
	
				},
				error: function (response) {
	
					//$('#RentModal').modal('hide');
					$('#formFreights').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
	
				}
			});
		}			
		console.log(idFreteAluguel)
		if((idLocacao == 0) || (idLocacao == undefined)){ //se for para cadastrar --------------------------------------------------

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


function loadTableFreights(){ //carrega a tabela de Locações/Aluguéis

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
			"url": "/freights/list_datatables", //chama a rota para carregar os dados 
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
						tipoFrete = 'Pendente';
						colorFrete = 'Red';
					}

					if (element.status == 0) {
						txtStatus = 'Concluído';
						color = 'green';
		
					} else if (element.status == 1) {
						txtStatus = 'Pendente ';
						color = 'Red';
		
					} 	
					row['#'] = cont++;
					row['tipoFrete'] = `<b style='color: ${colorFrete}'>${tipoFrete}</b>`
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

	$('#idFreteAluguel').val('');
	$('#locacao').val('');
	$('#tipoFrete').val(0);
	$('#status').val('');
	$('#dataHora').val(new Date());
	$('#observacao').val('');
	
}

// function formatDate(dateX){ //format Date to input in Form
//     var data = new Date(dateX),
//         dia  = data.getDate().toString(),
//         diaF = (dia.length == 1) ? '0'+dia : dia,
//         mes  = (data.getMonth()+1).toString(), //+1 pois no getMonth Janeiro começa com zero.
//         mesF = (mes.length == 1) ? '0'+mes : mes,
//         anoF = data.getFullYear();
// 	//return diaF+"/"+mesF+"/"+anoF;
// 	return anoF+"-"+mesF+"-"+diaF;
// }

//Usado para deixar visivel o itens do dropdown de produtos específicos
// var checkList = document.getElementById('list1');

// checkList.getElementsByClassName('anchor')[0].onclick = function (evt) {
// 	if (checkList.classList.contains('visible'))
// 		checkList.classList.remove('visible');
// 	else
// 		checkList.classList.add('visible');
// }


//carrega os Clientes 
// function loadCostumers(idCliente = '') {

// 	//console.log('loading costumers')

// 	$('#cliente').html("");

// 	let costumers = "<option value=''>(escolha)</option>";

// 	$.getJSON(`/costumers/json`, function (data) { //ajax


// 		data.forEach(function (item) {
// 			//console.log(item)
// 			costumers += `<option value="${item.idCliente}">${item.nome}</option>`
// 		});

		
// 		$('#cliente').append(costumers)


// 	}).then(() => {

// 		if(idCliente !== ''){ //se já tem um cliente escolhido
// 			$("#cliente").val(idCliente).prop('disabled', true);
// 		}
				
// 		$("#cliente").on("change", function() {
// 			var valor = $(this).val();   // aqui vc pega cada valor selecionado com o this
// 			//alert("evento disparado e o valor é: " + valor);
// 			loadContracts(valor);
// 		})


// 	}).fail(function () {
// 		console.log("Rota não encontrada!");
// 		return false
// 	});

// }

// //carrega os Contratos
// function loadContracts(idCliente = ''){

// 	console.log(`Id Cliente: ${idCliente}, buscando contratos ...`);

// 	txtEscolha = `<option value="">(escolha)</option>`;
// 	$("#contrato_idContrato").html("");
// 	$("#itens").html(txtEscolha)
	
// 	$.getJSON(`/contracts/json/${idCliente}/contracts`, function (data) { //ajax
// 		console.log(data)

// 		//alert("Lista: " + data)
// 		contracts = ""; //esvazia a lista de opções

// 		if(data.length == 0){
// 			contracts = `<option value="">Sem contratos cadastrados</option>`
// 		}else{

// 			$("#contrato_idContrato").html(txtEscolha);

// 			data.forEach(function (item) {
// 				//console.log(item)
// 				contracts += `<option value="${item.idContrato}">${item.codContrato}</option>`
// 			});
// 		}

// 		$("#contrato_idContrato").append(contracts)

// 	}).then(() => {

// 		var comboNome = document.getElementById("contrato_idContrato");

//         if (comboNome.options[comboNome.selectedIndex].value != "" ){
// 			var codigo = comboNome.options[comboNome.selectedIndex].value;
// 			$("#contrato_idContrato").val(codigo).prop('disabled', true);
// 		}	

// 		$("#contrato_idContrato").on("change", function() {
// 			var valor = $(this).val();   // aqui vc pega cada valor selecionado com o this
			
// 			loadContractItens(valor);
// 		})

// 	}).fail(function () {
// 		console.log(`Rota não encontrada! (//contracts/json/${idCliente}/contracts`);
// 		return false
// 	});

// }

// //carrega itens do Contrato selecionado
// function loadContractItens(idContract = false){

// 	console.log(`carregando itens para o contrato id: ${idContract}`);

// 	$.getJSON(`/contract_itens/json/contract/${idContract}`, (data) => {
// 		console.log(data)
		
// 		$("#itens").html("");

// 	}).then((data) => {


// 		txtListItens = ""; //esvazia a lista de opções

// 		if(data.length == 0){
// 			txtListItens = `<option value="">Sem itens neste contrato</option>`;
// 		}else{

// 			txtListItens = `<option value="">(escolha)</option>`;
// 			let arrItens = [];

// 			data.forEach(function (item) {
// 				//console.log(item)
// 				arrItens.push(item);
// 				txtListItens += `<option value="${item.idItem}">${item.descCategoria} ${item.descricao}</option>`
// 			});	

// 			$("#itens").on("change", function() { //quando é escolhido um item ----------------------------------
// 				loadItemFields(arrItens);
// 			}); // ---------------------------------- 
// 		}

// 		$("#itens").append(txtListItens);



// 	}).catch(() => {

// 		console.log(`Rota não encontrada! (/contract_itens/json/contract/${idContract}`);
// 		return false
// 	})
// }

// function loadItemFields(arrItens) {

// 	let item; //item selecionado

// 	item = arrItens.find((item) => item.idItem == $("#itens").val());

// 	console.log("item selecionado:");
// 	console.log(item)

// 	//atribuindo valores do Item para os campos; vlAluguel, custoEntrega, custoRetirada e quantidade.
// 	$("#vlAluguel").val(item.vlAluguel);
// 	$("#custoEntrega").val(item.custoEntrega);
// 	$("#custoRetirada").val(item.custoRetirada);
	
// 	$("#quantidade").html("");

// 	if(item.quantidade > 0) {
// 		txtQuantidade = "<option value=''>(escolha)</option>";

// 		for(i=1; i<=item.quantidade; i++){ //gera opções de 1 até a quantidade máxima
// 			txtQuantidade += `<option value="${i}">${i}</option>`;
// 		}

// 	} else {
// 		txtQuantidade = "<option value=''>0</option>"; //Não possue mais quantidade disponível do contrato
// 	}


// 	$("#quantidade").append(txtQuantidade);
	  
// 	loadListProductEsp(item.idProduto_gen);
// }

// function loadListProductEsp(idProduto_gen, selected=false) { /* carrega os checkboxes de produtos específicos (que estão disponíveis)*/
// 	console.log(`buscando produtos específicos para o idProduto_gen: ${idProduto_gen}`);

// 	let txtListProducts = '';

// 	if(selected) {  //seleciona o produto
// 		txtListProducts += `<li><input type="checkbox" checked="true" value=${selected.idProduto_esp}/>${selected.codigo} <b>(atual)</b></li>`;
// 	}

// 	$route = `/products_esp/json/product_gen/${idProduto_gen}`;

// 	$.getJSON($route, function (data) {
// 		console.log(data);

// 	}).then((data) => {

// 		const getTxtProducts = (txt, data) => { //retorna uma string com os inputs para escolher produtos específicos
// 			return txt + `<li><input type="checkbox" value=${data.idProduto_esp}/>${data.codigoEsp} </li>`;
// 		}
		
// 		txtListProducts += data.reduce(getTxtProducts, "");
// 		console.log(txtListProducts)

// 		$("#listProductsEsp").html("") //esvazia a lista
// 		$("#listProductsEsp").append(txtListProducts)

// 	}).catch(() => {
// 		console.log(`Rota não encontrada! ${route}`);
// 		return false;
// 	})

// }