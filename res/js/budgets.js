var myTable = null

$(function() { //quando a página carrega

	//carrega a tabela de Orçamento (Budgets)
	loadTableBudgets();
});

function loadTableBudgets(){ //carrega a tabela de Orçamentos

	if(myTable != null){
		myTable.destroy(); //desfaz as paginações
	}

	console.log('loading table ...')
	myTable = $("#dataTable").DataTable({ //carrega a tabela
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true,
		"ajax": {
			"url": "/budgets/list_datatables", //para chamar o método ajax_list_budgets
			"type": "POST",
			dataSrc: function (json) {
				console.log('json')
				console.log(json)

				rows = [];
				let color = '';

				json.data.forEach(element => {
					console.log(element)

					row = []
					color = '';

					if (element.statusOrcamento == "Arquivado"){
						color = 'grey';
		
					}else if (element.statusOrcamento == "Pendente"){
						color = 'orange';
					}

					//row['id'] = element.id
					row['statusOrcamento'] = `<strong style="color:${color}">${element.statusOrcamento}</strong>`
					row['codContrato'] = element.codContrato
					row['dtEmissao'] = element.dtEmissao
					row['obraCliente'] = element.obraCliente
					row['options'] = `<button type='button' title='ver detalhes' class='btn btn-warning btnEdit'
					onclick='loadSupplier(${element.id});'>
						<i class='fas fa-bars sm'></i>
					</button>
					<button type='button' title='excluir' onclick='deleteSupplier(${element.id});'
						class='btn btn-danger btnDelete'>
						<i class='fas fa-trash'></i>
					</button>`

					rows.push(row)
				
				});
				
				return rows;
			},

		},
		"columns": [
			{ "data": "statusOrcamento" },
			{ "data": "codContrato" },
			{ "data": "dtEmissao" },
			{ "data": "obraCliente" },
			{ "data": "options" },
			        
		],
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});
}

//limpar campos do modal para Cadastrar
/*function clearFieldsValues(){

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

	$('#notas').val('');
	$('#valorAluguel').val('0');
	$('#valorAluguel').val('');

	$('#idOrcamento').val('0');
	
}*/



/*
function formatDate(dateX){ //format Date to input in Form
    var data = new Date(dateX),
        dia  = data.getDate().toString(),
        diaF = (dia.length == 1) ? '0'+dia : dia,
        mes  = (data.getMonth()+1).toString(), //+1 pois no getMonth Janeiro começa com zero.
        mesF = (mes.length == 1) ? '0'+mes : mes,
        anoF = data.getFullYear();
	//return diaF+"/"+mesF+"/"+anoF;
	return anoF+"-"+mesF+"-"+diaF;
}*/

