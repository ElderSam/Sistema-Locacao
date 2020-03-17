$(function() { //quando a página carrega

	//carrega a tabela de ProdType
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR, //tradução
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true, 
		"ajax": {
			"url": "/prod-types/list_datatables", //chama a rota para carregar os dados 
			"type": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});

	$('#btnAddProdType').click(function(){
		clearFieldsValues();
        loadCategories();
		clearErrors();
	});

	$('#idCategoria').change(function(){		
		showsNextNumber();
	});

	$('#ordem_tipo').change(function(){
		showsNextNumber();
	});
	 
	 
	/* Cadastrar ou Editar Usuario --------------------------------------------------------------*/	
	$("#btnSaveProdType").click(function(e) { //quando enviar o formulário de Usuarios
		e.preventDefault();
		
		let form = $('#formProdType');
		let formData = new FormData(form[0]);

		id = $('#id').val()
		//onsole.log("id:" + id)

		if((id == 0) || (id == undefined)){ //se for para cadastrar --------------------------------------------------

			//console.log("você quer cadastrar")

			$.ajax({
				type: "POST",
				url: '/prod-types/create',
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function() {
					clearErrors();
					$("#btnSaveProdType").parent().siblings(".help-block").html(loadingImg("Verificando..."));
				
				},
				success: function (response) {
					clearErrors();
	
					if (JSON.parse(response).error) {
						console.log('erro ao cadastrar novo Tipo de produto!')
						response = JSON.parse(response)
						
						Swal.fire(
							'Erro!',
							'Por favor verifique os campos',
							'error'
						)
	
						if(response['error_list']){
							
							showErrorsModal(response['error_list'])
						}
						
					} else {
						$('#prodTypeModal').modal('hide');
						
						//console.log(response)
						Swal.fire(
							'Sucesso!',
							'Tipo de produto cadastrado!',
							'success'
							)
	
						loadTableProdType();
						$('#formProdType').trigger("reset");
						
					}
					
				},
				error: function (response) {
					//$('#prodTypeModal').modal('hide');
					$('#formProdType').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
	
				}
			});

		}else{ /* se for para Editar -------------------------------------------------- */

			//console.log('você quer editar o usuario: ' + id)
			
			$.ajax({
				type: "POST",
				url: `/prod-types/${id}`, //rota para editar
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function() {
					clearErrors();
					$("#btnSaveProdType").parent().siblings(".help-block").html(loadingImg("Verificando..."));
				
				},
				success: function (response) {
					clearErrors();

					if (JSON.parse(response).error) {
						console.log('erro ao editar Tipo de produto!')

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
						$('#prodTypeModal').modal('hide');

						Swal.fire(
							'Sucesso!',
							'Tipo de produto atualizado!',
							'success'
						);

						loadTableProdType();
						$('#formProdType').trigger("reset");
					}
	
				},
				error: function (response) {
	
					//$('#prodTypeModal').modal('hide');
					$('#formProdType').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);

				}
			});
		}	

		return false;
	});

});

function showsNextNumber(){ //mostra o próximo número de série relacionado à categoria
	
	idCategoria = $('#idCategoria').val();
	ordem_tipo = $('#ordem_tipo').val();

	if((idCategoria != undefined) && (idCategoria != "") && (ordem_tipo != undefined) && (ordem_tipo != "")){

		$.ajax({
			type: "POST",
			url: `/prod-types/showsNextNumber/${idCategoria}/${ordem_tipo}`,
			contentType: false,
			processData: false,
			
			success: function (response) {
		
				$('#codTipo').val(response)						
									
			},
			error: function (response) {

				console.log(`Erro! Mensagem: ${response}`);		
			}
		});	
	}
}


//carrega as opções de Categoria de produto
async function loadCategories(category = false){

    $.getJSON(`/products/categories/json`, function (data) { //ajax
        
        let categories = `<option value="">(escolha)</option>`
        
        data.forEach(function(item){
            //console.log(item)
            categories += `<option value="${item.idCategoria}">${item.codCategoria} - ${item.descCategoria}</option>`
        });

        $('#idCategoria').html(categories)                

    }).then(() => { 
        
        if(category){
            $("#formProduct #idCategoria").val(category[0]).prop('disabled', true);
            //console.log('categoria: ' + category[0])
            
            return $("#formProduct #idCategoria").val()
        }
    
        //$("#productModal").modal();
    }).fail(function () {
        console.log("Rota não encontrada! (/products/categories/json)");
        return false
    });

}

function loadTableProdType(){ //carrega a tabela de ProdType

	myTable.destroy(); //desfaz as paginações
	
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true,
		"ajax": {
			"url": "/prod-types/list_datatables", //para chamar o método ajax_list_prodType
			"type": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});
}


//detalhes do ProdType
function loadProdType(id) { //carrega todos os campos do modal referente ao ProdType escolhido
    
    clearErrors();
    loadCategories();
    
	$('#modalTitle').html('Detalhes do Tipo de produto')
	$('#btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
	$('#btnSaveProdType').hide();
	$('#btnUpdate').show();
	
	$('#dtCadastro').parent().show(); //aparece a data de cadastro (só para visualizar)

	$.getJSON(`/prod-types/json/${id}`, function (data) {
		//console.log(data)
		//$("#formProdType").data("id", id);

		$("#formProdType #descTipo").val(data.descTipo).prop('disabled', true);
		$("#formProdType #codTipo").val(data.codTipo).prop('disabled', true);
		$("#formProdType #idCategoria").val(data.idCategoria).prop('disabled', true);
		$("#formProdType #ordem_tipo").val(data.ordem_tipo).prop('disabled', true);
		$("#id").val(data.id);

		//console.log('load View ProdType id: ' + $("#id").val())

		dtCadastro = formatDate(data.dtCadastro)
		//console.log('data: ' + dtCadastro)
		$("#formProdType #dtCadastro").val(dtCadastro);


		/* Atualizar ProdType ------------------------------------------------------------------ */
		$('#btnUpdate').click(function(){ //se eu quiser atualizar o ProdType atual

			$('#modalTitle').html('Editar Tipo de produto');
			$('#btnClose').html('Cancelar').removeClass('btn-primary').addClass('btn-danger');
			$('#btnSaveProdType').val('Atualizar').show();
			$('#btnUpdate').hide();

			$("#formProdType #descTipo").prop('disabled', false);
			$("#formProdType #codTipo").prop('disabled', false);
			$("#formProdType #idCategoria").prop('disabled', false);
			$("#formProdType #ordem_tipo").prop('disabled', false);
				
		}); /* Fim Atualizar ProdType ---------------------------------------------------------- */
			
	}).then(() => { 

		$("#prodTypeModal").modal();
	}).fail(function () {
		console.log("Rota não encontrada!");
	});

}

function deleteProdType(id){

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
				url: `/prod-types/${id}/delete`,
				contentType: false,
				processData: false,
				/*beforeSend: function() {
					//...
				},*/
				success: function (response) {
		
					if (JSON.parse(response).error) {
						console.log('erro ao excluir!')
						response = JSON.parse(response)
						
						Swal.fire(
							'Erro!',
							'Por favor verifique os campos',
							'error'
						)
						
					} else {					
										
						Swal.fire(
							'Excluído!',
							'Registro apagado!',
							'success'
						)

						loadTableProdType();						
					}					
				},
				error: function (response) {

					console.log(`Erro! Mensagem: ${response}`);		
				}
			});		

		}
	})

	$('.swal2-cancel').html('Cancelar');
}


//limpar campos do modal para Cadastrar
function clearFieldsValues(){

	$('#modalTitle').html('Cadastrar Tipo de produto');
	$('#btnClose').html('Fechar').removeClass('btn-danger').addClass('btn-secondary');
	$('#btnSaveProdType').val('Cadastrar').show();
	$('#btnUpdate').hide();

	$('#dtCadastro').parent().hide(); //aparece a data de cadastro (só para visualizar)


	$("#formProdType #descTipo").prop('disabled', false);
	$("#formProdType #codTipo").prop('disabled', false);
	$("#formProdType #idCategoria").prop('disabled', false);
	$("#formProdType #ordem_tipo").prop('disabled', false);
	
	$('#descTipo').val('');
	$('#codTipo').val('');
	$('#idCategoria').val('0');
    $('#ordem_tipo').val('0');
    $('#dtCadastro').val('');

	$('#id').val('0');
	//...	
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

