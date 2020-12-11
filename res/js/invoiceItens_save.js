let myTable = null;

$(function() {

	/* Cadastrar ou Editar Fatura --------------------------------------------------------------*/
	$("#btnSaveItem").click(function (e) { //para enviar o formulário de Fatura
		e.preventDefault();

		$('#periodoLocacao').prop('disabled', false);

		let form = $('#formItem');
		let formData = new FormData(form[0])
		sendFormItem(formData)

		$('#periodoLocacao').prop('disabled', true);

		return false;
	});
});

function loadTableItens(itens) { //carrega a tabela de Itens de fatura

	console.log('loading Table fatura_itens')
	
	if(myTable != null){
		myTable.destroy(); //desfaz as paginações
	}

    arrayColumnDefs = [
        { targets: "no-sort", orderable: false }, //para não ordenar
        { targets: "text-center", className: "text-center" },
    ]
     
	if(idFatura != "" && idFatura != undefined){

		/*if(idFatura == 0) {
			idFatura = $("#idFatura").val()
        }*/

        const data = () => {			
            rows = [];

            itens.forEach(el => {
                element = JSON.parse(el)
                //console.log(element)
                rows.push(loadRowItem(element))					
                
                /*let fretes = loadRowsFrete(element)
                rows.push(fretes[0]) //entrega
                rows.push(fretes[1]) //retirada*/
            });
            //atualizaValorTotal(); //valor total do orçamento/contrato
            console.log(rows)
            return rows;
        }
        

		myTable = $("#dataTable").DataTable({ 
            //retrieve: true,
			"oLanguage": DATATABLE_PTBR, //tradução
			"autoWidth": false, //largura
			"processing": true, //mensagem 'processando'
            "serverSide": false, 
            
            "data": data(),
			"columns": [
				//{ "data": "descricao" },
				{ "data": "periodoLocacao" },
				{ "data": "vlAluguelCobrado" },
				{ "data": "custoEntrega" },
				{ "data": "custoRetirada" },		
                { "data": "dtInicio" },
                { "data": "dtFim" },
				{ "data": "options" },						
			],
			"columnDefs": arrayColumnDefs
		});

	}else{

		myTable = $("#dataTable").DataTable({ 
			"oLanguage": DATATABLE_PTBR, //tradução
			"autoWidth": false, //largura
			"processing": true, //mensagem 'processando'
			"serverSide": false, 
			
			"columnDefs": arrayColumnDefs
		});
	}
}


function loadRowItem(element){
    console.log(element);

	let hidden;

	if(typeForm == 'view'){
		hidden = "hidden='true'";

	}else{ //save (create and edit)	
		hidden = "";
	}

	row = []

	//categoria = element.descCategoria.toUpperCase()
	//row['id'] = element.id
	//row['descricao'] = `<strong>${categoria} ${element.descricao}</strong>`
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

	row['vlAluguelCobrado'] = paraMoedaReal(Number(element.vlAluguelCobrado))

	//vlTotalFatura += vlTotal
	const showFrete = (custo) => {
		custo = Number(custo)
		if(custo == 0) {
			return '-'
		} else {
			return paraMoedaReal(custo)
		}
	}
    row['custoEntrega'] = showFrete(element.custoEntrega)
    row['custoRetirada'] = showFrete(element.custoRetirada)

    row['dtInicio'] = formatDateToShow(element.dtInicio)
    row['dtFim'] = formatDateToShow(element.dtFim)

	row['options'] = `<button type='button' title='ver detalhes' class='btn btn-warning btnEdit'
	onclick='loadItem(${element.idItemFatura});' ${hidden}>
		<i class='fas fa-bars sm'></i>
	</button>
	<button type='button' title='excluir' onclick='deleteItem(${element.idItemFatura});'
		class='btn btn-danger btnDelete' ${hidden}>
		<i class='fas fa-trash'></i>
	</button>`
	
	return row;
}

function loadItem(idItem) {
	console.log(`load item: ${idItem}`)
	$("#itemModal").modal('show');

	clearFieldsItem();
	clearErrors();
	
	$("#itemModal #modalTitle").html('Detalhes do Item')
    $('#itemModal #btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
    $('#itemModal #btnSaveItem').hide();
    $('#itemModal #btnUpdate').show();

	$.getJSON(`/invoice_itens/json/${idItem}`, function (data) { //ajax
		data = data[0]
		console.log(data)

		/*
					<div id="divCliente">Cliente: <b>${data.codigoCliente} - ${data.nomeCliente}</b></div>
			<div id="divContrato">Contrato: <b>${data.codContrato}</b></div>
		*/

		$("#divDetailsItem").html(`
			<div id="divItem">Item: <b>${data.descCategoria} ${data.descricao}</b></div>
			<div id="divProduto">Cod. produto: <b>${data.codigoEsp}</b></div>
		`).show();


		$("#formItem #idItemFatura").val(data.idItemFatura);
		$("#formItem #idFatura").val(data.idFatura);
		$("#formItem #idAluguel").val(data.idAluguel);
        $("#formItem #periodoLocacao").val(data.periodoLocacao).prop('disabled', true);
        $("#formItem #vlAluguelCobrado").val(data.vlAluguelCobrado).prop('disabled', true);
        $("#formItem #custoEntrega").val(data.custoEntrega).prop('disabled', true);
        $("#formItem #custoRetirada").val(data.custoRetirada).prop('disabled', true);
		$("#formItem #dtInicio").val(data.dtInicio).prop('disabled', true);
		$("#formItem #dtFim").val(data.dtFim).prop('disabled', true);
	
		const vlTotal = () => {
			let { vlAluguelCobrado, custoEntrega, custoRetirada} = data;
			let total = parseFloat(vlAluguelCobrado) + parseFloat(custoEntrega) + parseFloat(custoRetirada);
			return paraMoedaReal(Number(total))
		}

		$("#formItem #vlTotal").html(vlTotal())

	}).then((data) => {

		//searchProduct(true);
        $('#itemModal #btnUpdate').click(function () {

			//$("#formItem #periodoLocacao").prop('disabled', false);
			$("#formItem #vlAluguelCobrado").prop('disabled', false);
			$("#formItem #custoEntrega").prop('disabled', false);
			$("#formItem #custoRetirada").prop('disabled', false);
			$("#formItem #dtInicio").prop('disabled', false);
			$("#formItem #dtFim").prop('disabled', false);

			$("#itemModal #modalTitle").html('Editar Item')
			$('#itemModal #btnClose').val('Cancelar').removeClass('btn-primary').addClass('btn-danger')
			$('#itemModal #btnSaveItem').val('Atualizar').show();
			$('#itemModal #btnUpdate').hide();			
		});

	}).fail(function () {
		console.log("Rota não encontrada! (/budgetItens/json/:idItem)");
	});
   
}

function sendFormItem(formData) {
	console.log('sendForm Item')
	idItem = $("#formItem #idItemFatura").val();

	if((idItem == 0) || (idItem == undefined) || (idItem == '')) {
		/*route = 'create';
		msg1 = 'cadastrar';
		msg2 = 'Item de Fatura cadastrada!';*/
		return false;

	}else {
		route = idItem; //parte da rota para editar
		msg1 = 'atualizar';
		msg2 = 'Item de Fatura atualizada!';
	}

	$.ajax({
		type: "POST",
		url: `/invoice_itens/${route}`,
		data: formData,
		contentType: false,
		processData: false,
		beforeSend: function () {
			clearErrors();
			$("#btnSaveInvoice").parent().siblings(".help-block").html(loadingImg("Verificando..."));

		},
		success: function (response) {
			clearErrors();

			response = JSON.parse(response)
			console.log(response);

			if (response.error) {
				console.log(`error: ${response.error}`)
				console.log(`erro ao ${msg1} Fatura!`)

				Swal.fire(
					'Erro!',
					`Ocorreu algum erro ao ${msg1}`,
					'error'
				);

				if (response['error_list']) {

					showErrorsModal(response['error_list'])

					Swal.fire(
						'Atenção!',
						'Por favor verifique os campos',
						'warning'
					);
				}

			} else {

				$('#itemModal').modal('hide');

				Swal.fire(
					'Sucesso!',
					msg2,
					'success'
				);

				loadTableItens();
				$('#formItem').trigger("reset");
			}
		},
		error: function (response) {
			console.log(`Erro! Mensagem: ${response}`);
		}
	});
}

//limpar campos do modal para Cadastrar
function clearFieldsItem() {
	$("#divDetailsItem").html("").hide();

	$('#btnUpdate').hide();
	$('#btnClose').html('Fechar').removeClass('btn-danger').addClass('btn-secondary');
	$('#btnSaveItem').val('Cadastrar').show();

	$("#formItem #idItemFatura").val('');
	$("#formItem #idFatura").val('');
	$("#formItem #periodoLocacao").val('')
	$("#formItem #vlAluguelCobrado").val('')
	$("#formItem #custoEntrega").val('')
	$("#formItem #custoRetirada").val('')
	$("#formItem #dtInicio").val('')
	$("#formItem #dtFim").val('')

	//$('#endereco').html('');
}
