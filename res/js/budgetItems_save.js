if($("#fk_idOrcamento").val() == undefined){
	const idContrato = $("#fk_idContrato").val()
}else{
	const idOrcamento = $("#fk_idOrcamento").val()
}

/*----------------------------------------------- Itens de orçamento --------------------------------------- */
function searchProduct(update = false) {
	console.log('searchProduct:')

    if(update){
        methodRoute = 'searchItemInContract';
    }else{
        methodRoute = 'addItemToContract';        
    }

	if (($('#codeProduct').val() != undefined) && ($('#codeProduct').val() != "")) {

		code = $('#codeProduct').val();

		$('#btnAddProduct').html(`<div class="help-block">${loadingImg("Buscando...")}</div>`)
							.removeClass('btn-success')
							.addClass('btn-light'); //cor cinza-claro (Bootstrap)

		$.getJSON(`/budgetItens/${methodRoute}/${idContrato}/${code}`, function (response) { //requisição ajax que retorna um JSON

			console.log(response)

			if (response.error) {
				console.log('erro ao adicionar produto ao orçamento!')

				Swal.fire(
					'Atenção!',
					response.msg,
					'warning'
				)

			} else {

                if(!update){
                    Swal.fire(
                        'Adicionado!',
                        'O Produto foi relacionado ao item!',
                        'success'
                    )
                }

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
            
            calculaTotalItem();
            calculaTotalEntrega();
            calculaTotalRetirada();

		}).fail(function () {
			console.log(`Rota não encontrada! (/budgetItens/addItemToContract/${code})`);
			return false
		});
	}

}

function loadTableItens(){ //carrega a tabela de Itens

	console.log('loading Table ContractItens')
	
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
	idItem: 1,
	descricao: '6M Sanitário DC',
	periodoLocacao: '2',
	vlAluguel: '350.98',
	quantidade: '4',
	observacao: 'Entrega em 5DD',
}

loadRowProduct(obj);*/

function loadRowProduct(element){
    console.log(element);

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

	row['vlAluguel'] = paraMoedaReal(Number(element.vlAluguel))
	row['quantidade'] = element.quantidade

	vlTotal = element.vlAluguel * element.quantidade
	//vlTotalContrato += vlTotal
	row['vlTotal'] = paraMoedaReal(vlTotal)

	row['observacao'] = element.observacao

	row['options'] = `<button type='button' title='ver detalhes' class='btn btn-warning btnEdit'
	onclick='loadItem(${element.idItem});' ${hidden}>
		<i class='fas fa-bars sm'></i>
	</button>
	<button type='button' title='excluir' onclick='deleteItem(${element.idItem});'
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

    $("#productModal #modalTitle").html('Adicionar Item ao Orçamento')
    $('#productModal #btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
    $('#productModal #btnSaveItem').val('Cadastrar').show();
    $('#productModal #btnUpdate').hide();

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

    $("#btnCart").click(function(){
        clearFieldsItem();
    });

    
	$("#btnSaveItem").click(function (e) { //salva o aluguel (az reserva do produto para alugar)
		e.preventDefault();

		let form = $('#formItem'); //formulário de aluguel do produto
		let formData = new FormData(form[0]);

		idItem = $('#idItem').val()
		console.log("salvar item, idItem:" + idItem)

        let route

		if ((idItem == 0) || (idItem == undefined)) { //se for para cadastrar --------------------------------------------------
            route = 'create'
            msgError = 'adicionar'
            msgSuccess = 'adicionado'

        }else{ //update
            route = idItem
            msgError = 'atualizar'
            msgSuccess = 'atualizado'
        }

        //console.log("você quer cadastrar um Aluguel")

        $.ajax({
            type: "POST",
            url: `/budgetItens/${route}`,
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {
                clearErrors();
                $("#btnSaveItem").parent().siblings(".help-block").html(loadingImg("Verificando..."));

            },
            success: function (response) {
                clearErrors();

                if (JSON.parse(response).error) {
                    console.log(`erro ao ${msgError} Item!`)
                    response = JSON.parse(response)

                    Swal.fire(
                        'Erro!',
                        response.msg,
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
                    console.log("id do orçamento: " + res.idContrato)

                    //$('#divListItens').attr('hidden', false) //mostra a parte da lista de produtos para adicionar

                    Swal.fire(
                        'Sucesso!',
                        `Item ${msgSuccess}!`,
                        'success'
                    )
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

                //loadTableBudget()
                $("#productModal").modal('hide'); //esconde o modal
                clearFieldsItem();
                
                loadTableItens();
            }
        });
		
	});
    

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

function loadItem(idItem){
    console.log(`load item: ${idItem}`)
    $("#productModal").modal('show');

	clearFieldsItem();
    clearErrors();
    
    $("#productModal #modalTitle").html('Detalhes do Item')
    $('#productModal #btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
    $('#productModal #btnSaveItem').hide();
    $('#productModal #btnUpdate').show();

    $.getJSON(`/budgetItens/json/${idItem}`, function (data) { //ajax
        console.log(data)

        $("#formItem #idItem").val(data.idItem);
        $("#formItem #vlAluguel").val(data.vlAluguel).prop('disabled', true);
        $("#formItem #codeProduct").val(data.codigoGen).prop('disabled', true);
        $("#formItem #quantidade").val(data.quantidade).prop('disabled', true);
        $("#formItem #custoEntrega").val(data.custoEntrega).prop('disabled', true);
        $("#formItem #custoRetirada").val(data.custoRetirada).prop('disabled', true);
        $("#formItem #periodoLocacao").val(data.periodoLocacao).prop('disabled', true);
        $("#formItem #observacao").val(data.observacao).prop('disabled', true);

        $("#qtdEntrega, #qtdRetirada").html(`${$("#quantidade").val()}`)

    }).then((data) => {

        searchProduct(true);

        $('#productModal #btnUpdate').click(function () {
          
            $("#formItem #vlAluguel").prop('disabled', false);
            $("#formItem #codeProduct").prop('disabled', false);
            $("#formItem #quantidade").prop('disabled', false);
            $("#formItem #custoEntrega").prop('disabled', false);
            $("#formItem #custoRetirada").prop('disabled', false);
            $("#formItem #periodoLocacao").prop('disabled', false);
            $("#formItem #observacao").prop('disabled', false);

            $("#productModal #modalTitle").html('Editar Item')
            $('#productModal #btnClose').val('Cancelar').removeClass('btn-primary').addClass('btn-danger')
            $('#productModal #btnSaveItem').val('Atualizar').show();
            $('#productModal #btnUpdate').hide();
        
        });

    }).fail(function () {
		console.log("Rota não encontrada! (/budgetItens/json/:idItem)");
	});
    
}

function deleteItem(idItem) {

	Swal.fire({
		title: `Você tem certeza de excluir esse Item ?`,
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
				url: `/budgetItens/${idItem}/delete`,
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
							response.msg,
							'error'
						)

					} else {

						Swal.fire(
							'Excluído!',
							'Item excluído!',
							'success'
						)

						console.log(`item ${idItem} excluído`)
						
						loadTableItens();
					}
				},
				error: function (response) {
					Swal.fire(
						'Erro!',
						'Não foi possível excluir o item',
						'error'
					)
					console.log(`Erro! Mensagem: ${response}`);
				}
			});
		}
	})

	$('.swal2-cancel').html('Cancelar');
}

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