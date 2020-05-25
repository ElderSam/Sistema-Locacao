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


function loadTableItens(){ //carrega a tabela de Itens

	console.log('loading Table ContractItens')

	idContrato = $("#fk_idOrcamento").val()
	if(myTable != null){
		myTable.destroy(); //desfaz as paginações
	}

	if(idContrato != "" && idContrato != undefined){

		myTable = $("#dataTable").DataTable({ 
			"createdRow": function( row, data, dataIndex){

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
						rows.push(loadRowProduct(element))					
						
						let fretes = loadRowsFrete(element)
						rows.push(fretes[0]) //entrega
						rows.push(fretes[1]) //retirada
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
				{ "data": "options" },						
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
/* ************ para testes ******************
const typeForm = 'save';

const obj = {
	descCategoria: 'Container',
	id: 1,
	descricao: '6M Sanitário DC',
	periodoLocacao: '2',
	vlAluguel: '350.98',
	quantidade: '4',
	observacao: 'Entrega em 5DD',
}

loadRowProduct(obj);*/

function loadRowProduct(element){
	let hidden;

	if(typeForm == 'view'){
		hidden = "hidden='true'";

	}else{ //save (create and edit)	
		hidden = "";
	}

	row = []

	categoria = element.descCategoria.toUpperCase()
	//row['id'] = element.id
	row['descricao'] = `<strong>${categoria} ${element.descricao}</strong>`
	let periodo;
	
	switch (element.periodoLocacao) {
		case "1":
			periodo = "diário";
			break;						
		case "2":
			periodo = "semanal";
			break;					
		case "3":
			periodo = "quinzenal";
			break;			
		case "4":
			periodo = "mensal";
			break;
		default:
			periodo = "";
			break;
	}
	row['periodoLocacao'] = periodo

	row['vlAluguel'] = /*paraMoedaReal(*/Number(element.vlAluguel)/*)*/
	row['quantidade'] = element.quantidade

	vlTotal = element.vlAluguel * element.quantidade
	//vlTotalContrato += vlTotal
	row['vlTotal'] = /*paraMoedaReal(*/vlTotal/*)*/

	row['observacao'] = element.observacao

	row['options'] = `<button type='button' title='ver detalhes' class='btn btn-warning btnEdit'
	onclick='loadItem(${element.id});' ${hidden}>
		<i class='fas fa-bars sm'></i>
	</button>
	<button type='button' title='excluir' onclick='deleteItem(${element.id});'
		class='btn btn-danger btnDelete' ${hidden}>
		<i class='fas fa-trash'></i>
	</button>`
	
	return row;
}

function loadRowsFrete(element){ //carrega valores de Entrega e Retirada
	/*
	descrição: Entrega ou Retirada
	vlAluguel: custoEntrega ou custoRetirada
	quantidade: 
	vlTotal = (custoEntrega OU custoRetirada) * quantidade
	*/
	qtd = element.quantidade

	arrFrete = [
		{
			'desc': 'Entrega',
			'custo': element.custoEntrega,
		},
		{
			'desc': 'Retirada',
			'custo': element.custoRetirada,
		},
	];

	let arrResult = [];
	let row = [];

	for(i=0; i<2; i++){ //2x -> Entrega [0] e Retirada [1]
		row = []
		row['descricao'] = `<div class='frete'></div> ${arrFrete[i].desc}`
		row['periodoLocacao'] = ""
		row['vlAluguel'] = paraMoedaReal(Number(arrFrete[i].custo))
		row['quantidade'] = qtd
	
		vlTotal = arrFrete[i].custo * qtd
		//vlTotalContrato += vlTotal
		row['vlTotal'] = paraMoedaReal(vlTotal)
		row['observacao'] = ""
		row['options'] = ""

		arrResult.push(row)
	}

	return arrResult;
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


/********************************************************** ITENS DE ORÇAMENTO ************************************************************************** */
$(function () {

	//console.log('----- itens de orçamento -----')
	/* Itens de orçamento */
	$("#btnAddProduct").on('click', function (e) { //ao clicar para adicionar um novo (dentro do formulário de itens)
		e.preventDefault()
		searchProduct();
	});

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

function teste(){
    console.log('test import');
}