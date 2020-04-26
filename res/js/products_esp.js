//pega o último parâmetro passado na URL (o id que vem depois da última barra)
var idProduct_gen = location.href.substring(location.href.lastIndexOf('/') + 1)
//alert(idProduct_gen);


$(function() { //quando a página carrega



	//carrega a tabela de Products
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR, //tradução
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true, 
		"ajax": {
			"url": `/products_esp/list_datatables/${idProduct_gen}`, //chama a rota para carregar os dados 
			"type": "POST",

		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});


	$('#btnAddProduct').click(function(){ //quando escolho a opção para criar novo, abrindo um modal
		
		clearFieldsValues();

		clearErrors();

		//alert('cadastrar para id: ' + $('#idProduto_gen').val())

		$.getJSON(`/products/json/${$('#idProduto_gen').val()}`, function (data) { //ajax
			console.log(data)
	
			produto = data[0]
			tipos = data[1]
			container = data[2]

			//console.log('load View Produto idProduto_esp: ' + $("#idProduto_esp").val())
	
			$("#formProduct #categoria").val(produto.categoria).prop('disabled', true);
			$("#formProduct #descricao").val(produto.descricao);
			//alert('categoria : ' + produto.idCategoria)
			
			if((produto.idCategoria != 3) && (produto.idCategoria != 4)){ //se não for Andaime nem Escora
				showsNextNumber(produto.idCategoria);
			}else{
				$("#formProduct #numSerie").parent().hide()	
			}
			
			//carrega categorias, seleciona uma e em seguida carrega valores nos campos de Container
			loadCategories([produto.idCategoria, produto.codCategoria], loadContainer, container);
	
			
		}).then(() => { 
	
			$("#productModal").modal();
		}).fail(function () {
			console.log("Rota não encontrada! (/products/json/:idProduto_gen)");
		});

		loadSuppliers(); //Carrega fornecedores
		
	});
	 
	 
	/* Cadastrar ou Editar Produto --------------------------------------------------------------*/	
	$("#btnSaveProduct").click(function(e) { //quando enviar o formulário de Produto
		e.preventDefault();

		$('#categoria').prop('disabled', false) //estou mandando a categoria no formulário, pois é considerada no Controller

		let form = $('#formProduct');
		let formData = new FormData(form[0]);

		idProduto_esp = $('#idProduto_esp').val()
		//console.log("idProduto_esp:" + idProduto_esp)

		if(idProduto_esp == 0){ //se for para cadastrar --------------------------------------------------

			//console.log("você quer cadastrar")

			$.ajax({
				type: "POST",
				url: '/products_esp/create',
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function() {
					clearErrors();
					$("#btnSaveProduct").parent().siblings(".help-block").html(loadingImg("Verificando..."));
				
				},
				success: function (response) {
					clearErrors();
	
					if (JSON.parse(response).error) {
						console.log('erro ao cadastrar novo Produto!')
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
						
					} else {
						$('#productModal').modal('hide');
						
						//console.log(response)
						Swal.fire(
							'Sucesso!',
							'Produto cadastrado!',
							'success'
							)
	
						loadTableProducts();
						$('#formProduct').trigger("reset");
						$('#categoria').prop('disabled', true)
						
					}
					
				},
				error: function (response) {
					//$('#productModal').modal('hide');
					$('#formProduct').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
					$('#categoria').prop('disabled', true)

	
				}
			});

		}else{ /* se for para Editar -------------------------------------------------- */

			//console.log('você quer editar o produto: ' + idProduto_esp)
			
			$.ajax({
				type: "POST",
				url: `/products_esp/${idProduto_esp}`, //rota para editar
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function() {
					clearErrors();
					$("#btnSaveProduct").parent().siblings(".help-block").html(loadingImg("Verificando..."));
				
				},
				success: function (response) {
					clearErrors();

					if (JSON.parse(response).error) {
						console.log('erro ao atualizar Produto!')

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
						$('#productModal').modal('hide');

						Swal.fire(
							'Sucesso!',
							'Produto atualizado!',
							'success'
						);

						loadTableProducts();
						$('#formProduct').trigger("reset");
					}
	
				},
				error: function (response) {
	
					//$('#productModal').modal('hide');
					$('#formProduct').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
	
				}
			});
		}	

		$('#categoria').prop('disabled', true)

		return false;
	});

});

//carrega as opções de Categoria de produto
async function loadCategories(category = false, callback, container = false, update=false){
    $.getJSON(`/products/categories/json`, function (data) { //ajax
        
        //console.log(data)
        
        let categories = `<option value="">(escolha)</option>`
        
        data.forEach(function(item){
            //console.log(item)
            categories += `<option value="${item.idCategoria}-${item.codCategoria}">${item.codCategoria} - ${item.descCategoria}</option>`
        });

        $('#categoria').html(categories)
                    

    }).then(() => { 
        
        if(category){
            $("#formProduct #categoria").val(category[0]+'-'+category[1]).prop('disabled', true);
            
            if(container){
                callback(container, update)
            }
            
            return $("#formProduct #categoria").val()
        }
    
        //$("#productModal").modal();
    }).fail(function () {
        console.log("Rota não encontrada! (/products/categories/json)");
        return false
    });

}

//carrega as opções de Fornecedor de um produto de categoria específico
function loadSuppliers(fornecedor = false){
    $.getJSON(`/suppliers/json`, function (data) { //ajax
        
        //console.log(data)
        
        let suppliers = `<option value="">(escolha)</option>`			

        data.forEach(function(item){
            //console.log(item)
            suppliers += `<option value="${item.idFornecedor}-${item.codFornecedor}">${item.codFornecedor} - ${item.nome}</option>`
        });

        $('#fornecedor').html(suppliers)
                    

    }).then(() => { 
        
        if(fornecedor){ //se já tem fornecedor escolhido (para modal Atualizar)
            $("#formProduct #fornecedor").val(fornecedor[0]+'-'+fornecedor[1]).prop('disabled', true);

        }

        //$("#productModal").modal();
    }).fail(function () {
        console.log("Rota não encontrada! (/suppliers/json)");
    });

}

function loadContainer(container = false, update = false){
    //alert('você escolheu ' + $('#categoria').val())

	let codCategoria = $('#categoria').val().substring(2, 5);

    if(codCategoria != '001'){ //se não for um container
        
        $('#tipoPorta').parent().hide();
        $('#forrado').parent().hide();
        $('#janelasLat').parent().hide();
        $('#janelasCirc').parent().hide();			
        $('#sanitarios').parent().hide();
        $('#eletrificado').parent().hide();
        $('#entradasAC').parent().hide();
        $('#tomadas').parent().hide();
        $('#lampadas').parent().hide();
        $('#chuveiro').parent().hide();

    }else{

        $('#tipoPorta').parent().show();
        $('#forrado').parent().show();
        $('#janelasLat').parent().show();
        $('#janelasCirc').parent().show();			
        $('#sanitarios').parent().show();
        $('#eletrificado').parent().show();
        $('#entradasAC').parent().show();
        $('#tomadas').parent().show();
        $('#lampadas').parent().show();
        $('#chuveiro').parent().show();

        
        if(container && update){
            //console.log('tipoporta: ' + container.tipoPorta)
            $('#tipoPorta').val(container.tipoPorta).prop('disabled', false);		
            $('#janelasLat').val(container.janelasLat).prop('disabled', true);
            $('#janelasCirc').val(container.janelasCirc).prop('disabled', true);			
            $('#sanitarios').val(container.sanitarios).prop('disabled', true);
            $('#entradasAC').val(container.entradasAC).prop('disabled', true);
            $('#tomadas').val(container.tomadas).prop('disabled', true);
            $('#lampadas').val(container.lampadas).prop('disabled', true);
            
            if(container.forrado == 1){
                $('#forrado').prop('checked', true);
            }

            if(container.eletrificado == 1){
                $('#eletrificado').prop('checked', true);
            }
            
            if(container.chuveiro == 1){
                $('#chuveiro').prop('checked', true);
            }

            $('#forrado').prop('disabled', true);
            $('#eletrificado').prop('disabled', true);
            $('#chuveiro').prop('disabled', true);
    

            return true;
        }
    }

}

function showsNextNumber(idCategory){ //mostra o próximo número de série relacionado à categoria
    $.ajax({
        type: "POST",
        url: `/products_esp/showsNextNumber/${idCategory}`,
        contentType: false,
        processData: false,
        
        success: function (response) {
    
            //console.log('próximo número de série: ' + response)
            $('#numSerie').val(response)						
                                
        },
        error: function (response) {

            console.log(`Erro! Mensagem: ${response}`);		
        }
    });	
}


function loadTableProducts(){ //carrega a tabela de Produtos

	myTable.destroy(); //desfaz as paginações

	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true,
		"ajax": {
			"url": `/products_esp/list_datatables/${idProduct_gen}`, //para chamar o método ajax_list_products
			"type": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});
}


//detalhes do Produto
function loadProductEsp(idProduto_esp) { //carrega todos os campos do modal referente ao produto escolhido
	clearFieldsValues();
	clearErrors();

	$('#modalTitle').html('Detalhes do Produto')
	$('#btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
	$('#btnSaveProduct').hide();
	$('#btnUpdate').show();


	$.getJSON(`/products_esp/json/${idProduto_esp}`, function (data) { //ajax
		console.log(data)

		produto = data[0]
		container = data[1]

		$("#idProduto_esp").val(produto.idProduto_esp);
		//console.log('load View Produto idProduto_esp: ' + $("#idProduto_esp").val())

			
		if((produto.idCategoria == 3) || (produto.idCategoria == 4)){ //se for Andaime ou Escora
			
			$("#formProduct #numSerie").parent().hide()	

		}else{
			$("#formProduct #numSerie").val(produto.numSerie).prop('disabled', true);
		}

		$("#formProduct #codigoEsp").val(produto.codigoEsp).prop('disabled', true);
		$("#formProduct #descricao").val(produto.descricao);
		$("#formProduct #valorCompra").val(produto.valorCompra).prop('disabled', true);
		$("#formProduct #status").val(produto.status).prop('disabled', true);
		$("#formProduct #dtFabricacao").val(produto.dtFabricacao).prop('disabled', true);
		$("#formProduct #anotacoes").val(produto.anotacoes).prop('disabled', true);
		

		//carrega categorias, seleciona uma e em seguida carrega valores nos campos de Container
		update = true
		loadCategories([produto.idCategoria, produto.codCategoria], loadContainer, container, update);

		fornecedor = [produto.idFornecedor, produto.codFornecedor]
		loadSuppliers(fornecedor);

		/* Atualizar Produto ------------------------------------------------------------------ */
		$('#btnUpdate').click(function(){ //se eu quiser atualizar o Produto atual

			$('#modalTitle').html('Editar Produto');
			$('#btnClose').html('Cancelar').removeClass('btn-primary').addClass('btn-danger');
			$('#btnSaveProduct').val('Atualizar').show();
			$('#btnUpdate').hide();
			
			$("#formProduct #numSerie").prop('disabled', false);
			$("#formProduct #valorCompra").prop('disabled', false);
			$("#formProduct #status").prop('disabled', false);
			$("#formProduct #dtFabricacao").prop('disabled', false);
			$("#formProduct #anotacoes").prop('disabled', false);
			$("#formProduct #fornecedor").prop('disabled', false);

			//campos de container
			$('#tipoPorta').prop('disabled', false);		
			$('#janelasLat').prop('disabled', false);
			$('#janelasCirc').prop('disabled', false);			
			$('#sanitarios').prop('disabled', false);
			$('#entradasAC').prop('disabled', false);
			$('#tomadas').prop('disabled', false);
			$('#lampadas').prop('disabled', false);
			$('#forrado').prop('disabled', false);
			$('#eletrificado').prop('disabled', false);
			$('#chuveiro').prop('disabled', false);
			
				
		}); /* Fim Atualizar Produto ---------------------------------------------------------- */
			

	}).then(() => { 

		$("#productModal").modal();
	}).fail(function () {
		console.log("Rota não encontrada! (/products_esp/json/:idProduto_esp)");
	});

}

function deleteProductEsp(idProduto_esp){

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
				url: `/products_esp/${idProduto_esp}/delete`,
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

						loadTableProducts();						
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

	//$("#formProduct #codigoEsp").prop('disabled', true)
	$('#modalTitle').html('Cadastrar Produto Específico');
	$('#btnClose').html('Fechar').removeClass('btn-danger').addClass('btn-secondary');
	$('#btnSaveProduct').val('Cadastrar').show();
	$('#btnUpdate').hide();

	$('#dtCadastro').parent().hide(); //aparece a data de cadastro (só para visualizar)
	//$('#desImagePath').parent().show();


	//$("#formProduct #codigoEsp").prop('disabled', true);
	$("#formProduct #numSerie").prop('disabled', false);
	$("#formProduct #valorCompra").prop('disabled', false);
	$("#formProduct #status").prop('disabled', false);
	$("#formProduct #dtFabricacao").prop('disabled', false);
	$("#formProduct #anotacoes").prop('disabled', false);
	$("#formProduct #fornecedor").prop('disabled', false);
	$("#formProduct #categoria").prop('disabled', true);

	$('#tipoPorta').prop('disabled', false);
	$('#janelasLat').prop('disabled', false);
	$('#janelasCirc').prop('disabled', false);
	$('#sanitarios').prop('disabled', false);
	$('#tomadas').prop('disabled', false);
	$('#lampadas').prop('disabled', false);
	//$('#eletrificado').attr("checked", false);
	$('#entradasAC').prop('disabled', false);
	$('#forrado').prop('disabled', false);
	$('#eletrificado').prop('disabled', false);
	$('#chuveiro').prop('disabled', false);

	

	//$('#image-preview').attr('src', "/res/img/products_espp/product-default.jpg");
	$('#dtCadastro').parent().hide();

	$('#codigoEsp').val('');
	$('#numSerie').val('');
	$('#descricao').val('');
	$('#valorCompra').val('');
	$('#status').val('1');
	$('#dtFabricacao').val('');

	$('#anotacoes').val('');
	$('#fornecedor').val('0');
	$('#idContainer').val('0');

	//campos de container
	$('#tipoPorta').val('');
	$('#janelasLat').val('');
	$('#janelasCirc').val('');
	$('#sanitarios').val('');
	$('#tomadas').val('');
	$('#lampadas').val('');
	//$('#eletrificado').attr("checked", false);
	$('#entradasAC').val('');
	$('#forrado').prop('checked', false);
	$('#eletrificado').prop('checked', false);
	$('#chuveiro').prop('checked', false);
	

	$('#tipoPorta').parent().hide();
	$('#forrado').parent().hide();
	$('#janelasLat').parent().hide();
	$('#janelasCirc').parent().hide();
	$('#sanitarios').parent().hide();
	$('#eletrificado').parent().hide();
	$('#entradasAC').parent().hide();
	$('#tomadas').parent().hide();
	$('#lampadas').parent().hide();
	$('#chuveiro').parent().hide();
	

	//fimr campos container
	
	$('#categoria').val('0');
	$('#dtCadastro').val('');
	
	$('#idProduto_esp').val('0');
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

