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
					row['options'] = `<a type='button' title='ver detalhes' class='btn btn-warning btnEdit'
					href='/budgets/${element.idContrato}'>
						<i class='fas fa-bars sm'></i>
					</a>
					<!--button type='button' title='excluir' onclick='deleteBudget(${element.idContrato});'
						class='btn btn-danger btnDelete'>
						<i class='fas fa-trash'></i>
					</button-->`

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
