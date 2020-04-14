$(function(){

    showsNextNumber();

	/*$("#btnAddBudget").click(function(){
		
		loadCadaster();	
	});

	async function loadCadaster(){

		await clearFieldsValues();
	
		await showsNextNumber();

		//await loadCostumers();		
		//await loadConstructions();
		//await loadTableProducts(); //carrega lista de produtos para colocar no Carrinho
	}*/

    $("#btnSaveRent").click(function(e){
        e.preventDefault();

        let form = $('#formRent'); //formulário de aluguel do produto
        let formData = new FormData(form[0]);

        idOrcamento = $('#idOrcamento').val()
        //console.log("idOrcamento:" + idOrcamento)

        if((idOrcamento == 0) || (idOrcamento == undefined)){ //se for para cadastrar --------------------------------------------------

            console.log("você quer cadastrar um Aluguel")

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
						console.log('erro ao cadastrar Aluguel!')
						response = JSON.parse(response)
						
						Swal.fire(
							'Erro!',
							'Ocorreu algum problema ao cadastrar Aluguel',
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

						//$('#BudgetModal').modal('hide');
                        
                        res = JSON.parse(response)
                        console.log("id do novo orçamento: " + res.idContrato)
                        $('#idOrcamento').val(res.idContrato)
                        
                        $('#formProdutos').attr('hidden', false) //mostra a parte da lista de produtos para adicionar

						Swal.fire(
							'Sucesso!',
							'Primeira parte do Orçamento cadastrada!',
							'success'
                            )
                        
	
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

    });
    	 
	/* Cadastrar ou Editar Orcamento --------------------------------------------------------------*/	
	$("#btnSaveBudget").click(function(e) { //quando enviar o formulário de Orcamento
		e.preventDefault();
		
		let form = $('#formBudget');
		let formData = new FormData(form[0]);

		idOrcamento = $('#idOrcamento').val()
		//console.log("idOrcamento:" + idOrcamento)

		if((idOrcamento == 0) || (idOrcamento == undefined)){ //se for para cadastrar --------------------------------------------------

			//console.log("você quer cadastrar")

			$.ajax({
				type: "POST",
				url: '/budgets/create',
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function() {
					clearErrors();
					$("#btnSaveBudget").parent().siblings(".help-block").html(loadingImg("Verificando..."));
				
				},
				success: function (response) {
					clearErrors();
	
					if (JSON.parse(response).error) {
						console.log('erro ao cadastrar novo Orçamento!')
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

						//$('#BudgetModal').modal('hide');
                        
                        res = JSON.parse(response)
                        console.log("id do novo orçamento: " + res.idContrato)
                        $('#idOrcamento').val(res.idContrato)
                        
                        $('#formProdutos').attr('hidden', false) //mostra a parte da lista de produtos para adicionar

						Swal.fire(
							'Sucesso!',
							'Primeira parte do Orçamento cadastrada!',
							'success'
                            )
                        
	
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

		}else{ /* se for para Editar -------------------------------------------------- */

			//console.log('você quer editar o Orçamento: ' + idOrcamento)
			
			$.ajax({
				type: "POST",
				url: `/budgets/${idOrcamento}`, //rota para editar
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function() {
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

						if(response['error_list']){
							
							showErrorsModal(response['error_list'])

							Swal.fire(
								'Atenção!',
								'Por favor verifique os campos',
								'error'
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
    

	$("#btnAddProduct").on('click', function (e) { //ao clicar para adicionar um novo
		e.preventDefault()

		searchProduct();
    });

});


function searchProduct(){


	if(($('#codeProduct').val() != undefined) && ($('#codeProduct').val() != "")){

		code = $('#codeProduct').val();
        alert('code ' + code);

        $('#btnAddProduct').html(`<div class="help-block">${loadingImg("Verificando...")}</div>`);

		$.getJSON(`/products/addToContract/${code}`, function (data) { //ajax
		
            console.log(data)        
            
            $('#produto').css('border', '1px solid black')
            $('#produtoAdicionado').html(`<strong style="color: green;">Produto Adicionado!</strong>`)

            $('#btnAddProduct').html('Adicionar');
            

            $('#prodCodigo').html(`<strong>Código:</strong> ${data.codigo}`)
            $('#prodDescricao').html(`<strong>Descrição:</strong> ${data.descCategoria} - ${data.tipo1} ${data.descricao}`)
			/*product = ''

			product += `<tr id="${data.idProduto}">`
	
			product += `<td>${data.codigo}</td>
						<td>${data.descCategoria}</td>
						<td>${data.tipo1} ${data.descricao}</td>
						<td><input type="number" name="vlAluguel_${data.idProduto}"></td>
						<td><input type="text" name="obs_${data.idProduto}"></td>
						<td>
							<button type='button' title='remover' onclick='removeProduct(${data.idProduto});'
								class='btn btn-danger btnDelete'>
								<i class='fas fa-trash'></i>
							</button>
						</td>
						</tr>`*/
					
	
		}).then(() => {
			
			//$('#cart').append(product)
			$('#codeProduct').val('');

			
				
		}).fail(function () {
			console.log("Rota não encontrada! (/budgets/constructions/json)");
			return false
		});
	}

}

function removeProduct(id){

	$(`#${id}`).html('').hide();

}


function showsNextNumber(){ //mostra o próximo número de série relacionado à categoria
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


function loadFieldsBudget(idOrcamento){

	//console.log(`loading all fields of budget (id = ${idOrcamento}`)

	$.getJSON(`/budgets/json/${idOrcamento}`, function (data) { //ajax
		console.log(data)

		$("#idOrcamento").val(data.idContrato);
		//console.log('load View Orcamento idOrcamento: ' + $("#idOrcamento").val())

		$("#formBudget #codigo").val(data.codContrato).prop('disabled', true);
		$("#formBudget #obra_idObra").val(data.obra_idObra).prop('disabled', true);
		$("#formBudget #dtEmissao").val(data.dtEmissao).prop('disabled', true);
		$("#formBudget #status").val(data.statusOrcamento).prop('disabled', true);
		$("#formBudget #dtAprovacao").val(data.dtAprovacao).prop('disabled', true);
		$("#formBudget #custoEntrega").val(data.custoEntrega).prop('disabled', true);
		$("#formBudget #custoRetirada").val(data.custoRetirada).prop('disabled', true);

		$("#formBudget #notas").val(data.notas).prop('disabled', true);
		$("#formBudget #valorAluguel").val(data.valorAluguel).prop('disabled', true);

		
		/* Atualizar Orcamento ------------------------------------------------------------------ */
		$('#btnUpdate').click(function(){ //se eu quiser atualizar o Orcamento atual

			$('#modalTitle').html('Editar Orcamento');
			$('#btnClose').html('Cancelar').removeClass('btn-primary').addClass('btn-danger');
			$('#btnSaveBudget').val('Atualizar').show();
			$('#btnUpdate').hide();
		
			$("#formBudget #codigo").prop('disabled', false);
			$("#formBudget #obra_idObra").prop('disabled', false);
			$("#formBudget #dtEmissao").prop('disabled', false);
			$("#formBudget #status").prop('disabled', false);
			$("#formBudget #dtAprovacao").prop('disabled', false);
			$("#formBudget #custoEntrega").prop('disabled', false);
			$("#formBudget #custoRetirada").prop('disabled', false);
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

	$('#modalTitle').html('Detalhes do Orcamento')
	$('#btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
	$('#btnSaveBudget').hide();
	$('#btnUpdate').show();

}




function deleteBudget(idOrcamento){

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

						loadTableBudgets();						
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
function loadCostumers(){

	//console.log('loading costumers')

	$("#idCliente").html(`<option value="">(escolha)</option>`);
	let costumers

	$.getJSON(`/budgets/costumers/json`, function (data) { //ajax
		
		console.log(data)
		
		costumers = `<option value="">(escolha)</option>`
		
		data.forEach(function(item){
			//console.log(item)
			costumers += `<option value="${item.idCliente}">${item.descCategoria}</option>`
		});
				

	}).then(() => {
		
		$('#idCliente').html(costumers)
	
	}).fail(function () {
		console.log("Rota não encontrada! (/budgets/constructions/json)");
		return false
	});

}

//carrega as opções de Obras para colocar no Orçamento
function loadConstructions(callback='', idBudget = false){

	//console.log('loading constructions')

	$("#obra_idObra").html(`<option value="">(escolha)</option>`);

	$.getJSON(`/budgets/constructions/json`, function (data) { //ajax
		
		console.log(data)
		
		let constructions = `<option value="">(escolha)</option>`
		
		data.forEach(function(item){
			//console.log(item)
			constructions += `<option value="${item.idObra}">${item.codObra} - ${item.descCategoria}</option>`
		});

		$('#obra_idObra').html(constructions)
					

	}).then(() => {
		
		
		if(idBudget){
			callback(idBudget) //executa a função loadFieldsBudget()
		}
	
	
	}).fail(function () {
		console.log("Rota não encontrada! (/budgets/constructions/json)");
		return false
	});

}
