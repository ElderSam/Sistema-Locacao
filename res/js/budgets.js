$(function() { //quando a página carrega

	//carrega a tabela de Budgets
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR, //tradução
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true, 
		"ajax": {
			"url": "/budgets/list_datatables", //chama a rota para carregar os dados 
			"type": "POST",

		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});

	$("#btnAddBudget").click(function(){
		let i = 0
		clearFieldsValues();

		if(i == 0){
			showsNextNumber()
		}

		loadConstructions();
		
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

						$('#BudgetModal').modal('hide');
						
						//console.log(response)
						Swal.fire(
							'Sucesso!',
							'Orçamento cadastrado!',
							'success'
							)
	
						loadTableBudgets();
						$('#formBudget').trigger("reset");
						
					}
					
				},
				error: function (response) {
					//$('#BudgetModal').modal('hide');
					$('#formBudget').trigger("reset");
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
						$('#BudgetModal').modal('hide');

						Swal.fire(
							'Sucesso!',
							'Orçamento atualizado!',
							'success'
						);

						loadTableBudgets();
						$('#formBudget').trigger("reset");
					}
	
				},
				error: function (response) {
	
					//$('#BudgetModal').modal('hide');
					$('#formBudget').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
	
				}
			});
		}	

		return false;
	});

});


	function showsNextNumber(){ //mostra o próximo número de série relacionado à categoria
		console.log('shows next number')
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


function loadTableBudgets(){ //carrega a tabela de Orçamentos

	myTable.destroy(); //desfaz as paginações
	
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true,
		"ajax": {
			"url": "/budgets/list_datatables", //para chamar o método ajax_list_budgets
			"type": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});
}

function loadFieldsBudget(){
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
function loadBudget(idOrcamento) { //carrega todos os campos do modal referente ao Orcamento escolhido
	
	loadConstructinos(loadFieldsBudget, idBudget);

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


//carrega as opções de Obras para colocar no Orçamento
function loadConstructions(callback, idBudget = false){

	$("#obra_idObra").html(`<option value="">(escolha)</option>`);

	$.getJSON(`/budgets/constructions/json`, function (data) { //ajax
		
		//console.log(data)
		
		let constructions = `<option value="">(escolha)</option>`
		
		data.forEach(function(item){
			//console.log(item)
			constructions += `<option value="${item.idObra}">${item.codObra} - ${item.descCategoria}</option>`
		});

		$('#obra_idObra').html(constructions)
					

	}).then(() => { 
		
		
		if(idBudget){
			callback(idBudget)
		}
	
	
	}).fail(function () {
		console.log("Rota não encontrada! (/budgets/constructions/json)");
		return false
	});

}

//limpar campos do modal para Cadastrar
function clearFieldsValues(){

	//$("#formBudget #codigo").prop('disabled', true)
	$('#modalTitle').html('Cadastrar Orçamento');
	$('#btnClose').html('Fechar').removeClass('btn-danger').addClass('btn-secondary');
	$('#btnSaveBudget').val('Cadastrar').show();
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


	//$('#image-preview').attr('src', "/res/img/budgets/budget-default.jpg");
	$('#codigo').val('');
	$('#obra_idObra').val('0');
	$('#dtEmissao').val('');		
	$('#status').val('0');
	$('#dtAprovacao').val('');
	$('#dtAprovacao').val('');
	$('#custoEntrega').val('');
	$('#custoRetirada').val('');

	$('#notas').html('');
	$('#valorAluguel').html('');

	$('#idOrcamento').val('0');
	
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

