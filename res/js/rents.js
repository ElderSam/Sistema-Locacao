var myTable = null

$(function() { //quando a página carrega

	//loadTableRents()

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

		idLocacao = $('#id').val()
		//console.log("idFornecedor:" + idFornecedor)

		if((idLocacao == 0) || (idLocacao == undefined)){ //se for para cadastrar --------------------------------------------------

			//console.log("você quer cadastrar")

			$.ajax({
				type: "POST",
				url: '/rents/create',
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
						console.log('erro ao cadastrar nova Locação!')
						response = JSON.parse(response)
						
						Swal.fire(
							'Erro!',
							'Ocorreu algum problema ao cadastrar',
							'error'
						)
	
						if(response['error_list']){
							
							showErrorsModal(response['error_list'])

							Swal.fire(
								'Atenção!',
								'Por favor verifique os campos',
								'error'
							)
						}
						
					} else { // Se cadastrou com sucesso

						$('#RentModal').modal('hide');
						
						//console.log(response)
						Swal.fire(
							'Sucesso!',
							'Aluguel cadastrado!',
							'success'
							)
	
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

		}else{ /* se for para Editar -------------------------------------------------- */

			//console.log('você quer editar o Fornecedor: ' + idFornecedor)
			
			$.ajax({
				type: "POST",
				url: `/rents/${idLocacao}`, //rota para editar
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
						console.log('erro ao atualizar Locação!')

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
						$('#RentModal').modal('hide');

						Swal.fire(
							'Sucesso!',
							'Locação atualizada!',
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

		return false;
	});

});
/*
function showsNextNumber(){ //mostra o próximo número de série relacionado à categoria
    console.log('shows next number')
    $.ajax({
        type: "POST",
        url: `/rents/showsNextNumber`,
        contentType: false,
        processData: false,
        
        success: function (response) {
    
            //console.log('próximo código de fornecedor: ' + response)
            $('#codigo').val(response)						
                                
        },
        error: function (response) {

            console.log(`Erro! Mensagem: ${response}`);		
        }
    });	
}*/


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
				
				rows = [];

				json.data.forEach(element => {
					//console.log(element)

					row = []

					//Essa variavel você pode apresentar
					// var telFormatado = mascaraTelefone(element.telefone1);

					//row['id'] = element.id

					row['codLocacao'] = element.codigo
					row['produto'] = element.produto_idProduto
					row['status'] = element.status
					row['dataInicio'] = dtInicio
					row['cliente'] = element.cliente_idCliente
					row['contrato'] = element.contrato_idContrato
					row['options'] = `<button type='button' title='ver detalhes' class='btn btn-warning btnEdit'
					onclick='loadRent(${element.idHistoricoAluguel});'>
						<i class='fas fa-bars sm'></i>
					</button>
					<button type='button' title='excluir' onclick='deleteRent(${element.idHistoricoAluguel});'
						class='btn btn-danger btnDelete'>
						<i class='fas fa-trash'></i>
					</button>`

					rows.push(row)
				
				});
				
				return rows;
			},


		},
		"columns": [
			{ "data": "codLocacao" },
			{ "data": "Produto" },
			{ "data": "status" },
			{ "data": "dataInicio" },
			{ "data": "cliente" },
			{ "data": "contrato" },
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
	clearFieldsValues();
	clearErrors();

	$('#modalTitle').html('Detalhes do Locação')
	$('#btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
	$('#btnSaveRent').hide();
	$('#btnUpdate').show();

	$.getJSON(`/rents/json/${idLocacao}`, function (data) { //ajax
		console.log(data)

		$("#idLocacao").val(data.idHistoricoAluguel);
		//console.log('load View Locacao idLocacao: ' + $("#idLocacao").val())

		$("#formRent #codigo").val(data.codigo).prop('disabled', true);
		$("#formRent #cliente").val(data.cliente_idCliente).prop('disabled', true);		
        $("#formRent #contratos").val(data.contrato_idContrato).prop('disabled', true);
		$("#formRent #itens").val(data.produto_idProduto).prop('disabled', true);
		$("#formRent #status").val(data.status).prop('disabled', true);
		$("#formRent #vlAluguel").val(data.vlAluguel).prop('disabled', true);
		$("#formRent #dtInicio").val(data.dtInicio).prop('disabled', true);
		$("#formRent #dtFim").val(data.dtFinal).prop('disabled', true);
		$("#formRent #vlEntrega").val(data.custoEntrega).prop('disabled', true);
		$("#formRent #vlRetirada").val(data.custoRetirada).prop('disabled', true);
		// $("#formRent #quantidade").val(data."").prop('disabled', true);
		$("#formRent #prodEpecifico").val(data.status).prop('disabled', true);
		$("#formRent #observacao").val(data.observacao).prop('disabled', true);
		
		/* Atualizar Locacao ------------------------------------------------------------------ */
		$('#btnUpdate').click(function(){ //se eu quiser atualizar o Locacao atual

			$('#modalTitle').html('Editar Locacão');
			$('#btnClose').html('Cancelar').removeClass('btn-primary').addClass('btn-danger');
			$('#btnSaveRent').val('Atualizar').show();
			$('#btnUpdate').hide();
		
			//$('#desImagePath').parent().show();

			$("#formRent #codigo").prop('disabled', true);	
			$("#formRent #cliente").prop('disabled', false);
			$("#formRent #contratoa").prop('disabled', false);
			$("#formRent #itens").prop('disabled', false);
			$("#formRent #status").prop('disabled', false);
			$("#formRent #vlAluguel").prop('disabled', false);
			$("#formRent #dtInicio").prop('disabled', false);
			$("#formRent #dtFim").prop('disabled', false);
			$("#formRent #vlEntrega").prop('disabled', false);
			$("#formRent #vlRetirada").prop('disabled', false);
			$("#formRent #quantidade").prop('disabled', false);
			$("#formRent #prodEspecifico").prop('disabled', false);
			$("#formRent #observacao").prop('disabled', false);
				
		}); /* Fim Atualizar Locacao ---------------------------------------------------------- */
			

	}).then(() => { 

		$("#RentModal").modal();
	}).fail(function () {
		console.log("Rota não encontrada! (/rents/json/:idLocacao)");
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

	$("#formRent #idHistoricoAluguel").prop('disabled', false);
	$("#formRent #codigo").prop('disabled', false);
	$("#formRent #cliente").prop('disabled', false);
	$("#formRent #contratos").prop('disabled', false);
	$("#formRent #itens").prop('disabled', false);
	$("#formRent #status").prop('disabled', false);
	$("#formRent #vlAluguel").prop('disabled', false);
	//$("#formRent #group-dtInicio").hide();
	$("#formRent #group-dtInicio").prop('disabled', false);
	$("#formRent #dtFim").prop('disabled', false);
	$("#formRent #vlEntrega").prop('disabled', false);
	$("#formRent #vlRetirada").prop('disabled', false);
	$("#formRent #quantidade").prop('disabled', false);
	$("#formRent #observacao").prop('disabled', false);

	$('#id').val('');
	$('#codigo').val('');
	$('#cliente').val('');
	$('#contratos').val('');
	$('#itens').val('');
	$('#status').val('0');
	$('#vlAluguel').val('');
	$('#dtInicio').val('');
	$('#dtFim').val('');
	$('#vlEntrega').val('');
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
	$("#contratos").html("");
	$("#itens").html(txtEscolha)
	
	$.getJSON(`/contracts/json/${idCliente}/contracts`, function (data) { //ajax
		console.log(data)

		//alert("Lista: " + data)
		contracts = ""; //esvazia a lista de opções

		if(data.length == 0){
			contracts = `<option value="">Sem contratos cadastrados</option>`
		}else{

			$("#contratos").html(txtEscolha);

			data.forEach(function (item) {
				//console.log(item)
				contracts += `<option value="${item.idContrato}">${item.codContrato}</option>`
			});
		}

		$("#contratos").append(contracts)

	}).then(() => {

		var comboNome = document.getElementById("contratos");

        if (comboNome.options[comboNome.selectedIndex].value != "" ){
			var codigo = comboNome.options[comboNome.selectedIndex].value;
			$("#contratos").val(codigo).prop('disabled', true);
		}	

		$("#contratos").on("change", function() {
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
	txtQuantidade = "<option value=''>(escolha)</option>";

	for(i=1; i<=item.quantidade; i++){ //gera opções de 1 até a quantidade máxima
		txtQuantidade += `<option value="${item.idItem}">${i}</option>`;
	}

	$("#quantidade").append(txtQuantidade);
	  
	loadListProductEsp(item.idProduto_gen);
}

function loadListProductEsp(idProduto_gen) { /* carrega os checkboxes de produtos específicos */
	console.log(`buscando produtos específicos para o idProduto_gen: ${idProduto_gen}`);

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

		$("#listProductsEsp")
			.html("")
			.append(txtListProducts)

	})
	.catch(() => {
		console.log(`Rota não encontrada! ${route}`);
		return false;
	})
	
}