/* Tabela de Faturas */
var myTable = null

$(function() { //quando a página carrega
	//carrega a tabela de Faturas
	loadTableInvoices();
});

// 	/* Cadastrar ou Editar Fatura --------------------------------------------------------------*/	
// 	$("#btnSaveContract").click(function(e) { //quando enviar o formulário de Fatura
// 		e.preventDefault();
		
// 		let form = $('#formContract');
// 		let formData = new FormData(form[0]);

// 		idContract = $('#idContrato').val()
// 		console.log("idContrato:" + idContract)

// 		if((idContract == 0) || (idContract == undefined)){ //se for para cadastrar --------------------------------------------------

// 			//console.log("você quer cadastrar")
//             Swal.fire(
//                 'Erro!',
//                 'Ocorreu algum erro ao carregar',
//                 'error'
//             );

// 		}else{ /* se for para Editar -------------------------------------------------- */

// 			console.log('você quer editar o Contrato: ' + idContract)
			
// 			$.ajax({
// 				type: "POST",
// 				url: `/contracts/${idContract}`, //rota para editar
// 				data: formData,
// 				contentType: false,
// 				processData: false,
// 				beforeSend: function() {
// 					clearErrors();
// 					$("#btnSaveContract").parent().siblings(".help-block").html(loadingImg("Verificando..."));
				
// 				},
// 				success: function (response) {
// 					clearErrors();

// 					if (JSON.parse(response).error) {
// 						console.log('erro ao atualizar Contrato!')

// 						response = JSON.parse(response)

// 						Swal.fire(
// 							'Erro!',
// 							'Ocorreu algum erro ao Atualizar',
// 							'error'
// 						);

// 						if(response['error_list']){
							
// 							showErrorsModal(response['error_list'])

// 							Swal.fire(
// 								'Atenção!',
// 								'Por favor verifique os campos',
// 								'error'
// 							);
// 						}

// 					} else {
// 						$('#ContractModal').modal('hide');

// 						Swal.fire(
// 							'Sucesso!',
// 							'Contrato atualizado!',
// 							'success'
// 						);

// 						loadTableContracts();
// 						$('#formContract').trigger("reset");
// 					}
	
// 				},
// 				error: function (response) {
	
// 					//$('#ContractModal').modal('hide');
// 					$('#formContract').trigger("reset");
// 					console.log(`Erro! Mensagem: ${response}`);
	
// 				}
// 			});
// 		}	

// 		return false;
// 	});

// });

function loadTableInvoices(){ //carrega a tabela de Faturas

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
			"url": "/invoices/list_datatables", //para chamar o método ajax_list_Contracts
			"type": "POST",
			dataSrc: function (json) {
				console.log('json')
				console.log(json)

				rows = [];
				let color = '';

				json.data.forEach(element => {
					console.log(element)

					row = []

					let statusPagamento, color;
					
					//0-pendente, 1-pago, 2-parcial, 3-cancelado, 4-perdido
					switch(parseInt(element.statusPagamento)) {
						case 0:
							statusPagamento = 'Pendente'
							color = 'red'
							break;
						case 1:
							statusPagamento = 'Parcial'
							break;
							color = 'orange'
						case 2:
							statusPagamento = 'Pago'
							break;
							color = 'green'
						case 3:
							statusPagamento = 'Cancelado'
							break;
							color = 'black'
						case 4:
							statusPagamento = 'Perdido'
							break;
							color = 'grey'
						default:
							statusPagamento = ''
							break;
					}

					//row['id'] = element.id
					row['numFatura'] = element.numFatura
					row['statusPagamento'] = `<strong style='color: ${color}'>${statusPagamento}</strong>`
					row['dtEmissao'] = formatDateToShow(element.dtEmissao)
					row['dtVencimento'] = formatDateToShow(element.dtVencimento)
					row['valorTotal'] = paraMoedaReal(Number(element.valorTotal))
					row['nomeCliente'] = element.nomeCliente
					
					row['options'] = `<a type='button' title='ver detalhes' class='btn btn-warning btnEdit'
					href='/invoices/${element.idFatura}'>
						<i class='fas fa-bars sm'></i>
					</a>`

					rows.push(row)				
				});

				return rows;
			},
		},
		"columns": [
			{ "data": "numFatura" },
			{ "data": "statusPagamento" },
			{ "data": "dtEmissao" },
			{ "data": "dtVencimento" },
			{ "data": "valorTotal" },
			{ "data": "nomeCliente" },
			{ "data": "options" },			        
		],
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});
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

