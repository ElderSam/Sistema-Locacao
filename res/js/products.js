$(function() { //quando a página carrega

	//carrega a tabela de Products
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR, //tradução
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true, 
		"ajax": {
			"url": "/products/list_datatables", //chama a rota para carregar os dados 
			"type": "POST",

		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});

	$('#categoria').change(function(){
		//loadContainer();
						
		tam = $('#categoria').val().length //pega o tamanho da string (ex: 34-001) (id-categoria)
		idCategoria = $('#categoria').val().substring(0,(tam - 4)); //tira os quatro últimos caracteres e pega o id
		
		loadTypes(idCategoria);
		//console.log('idCategoria: ' + idCategoria)
		if(idCategoria == 3 || idCategoria == 4){ //se for Andamie ou Escora
			$('#numSerie').val('xxxx').parent().hide()
		}else{
			//showsNextNumber(idCategoria); //mostra próximo número de série da Categoria

		}

	});

	//restrição - o container 3M só pode ser do tipo Almoxarifado ou Especial
	$('#tipo1').change(function(){

		tam = $('#categoria').val().length //pega o tamanho da string (ex: 34-001) (id-categoria)
		codCategoria = $('#categoria').val().substring((tam-3),tam); //tira os quatro últimos caracteres e pega o id
		//console.log(codCategoria)
		
		tam = $('#tipo1').val().length
		codtipo1 = $('#tipo1').val().substring((tam-2),tam); 

		if((codCategoria == '001' && codtipo1 == '01')){ //se for container com metragem 3M

			//mostra apenas duas opções
			$('#tipo2').html(tipo2para3M) 
		
		}else if((codCategoria == '003' && (codtipo1 == '02' || codtipo1 == '03')) || (codCategoria == '004')){ //se for (Andaime Fachadeiro ou Multidirecional) ou for Escora
					//mostra apenas uma opção (tipo1)
					$('#tipo2').val('')
					$('#tipo2').parent().hide()
					$('#tipo3').val('')
					$('#tipo3').parent().hide()

		}else{
			$(`#tipo2`).html(types[2])
			//console.log('help: ' + types[2])

			$('#tipo2').parent().show()
			$('#tipo3').parent().show()

		}
	});

	//restrição - o container Sanitário obrigatoriamente tem Lavabo
	$('#tipo2').change(function(){

		tam = $('#categoria').val().length //pega o tamanho da string (ex: 34-001) (id-categoria)
		codCategoria = $('#categoria').val().substring((tam-3),tam); //tira os quatro últimos caracteres e pega o id
		
		tam = $('#tipo2').val().length
		codtipo2 = $('#tipo2').val().substring((tam-2),tam); 

		if((codCategoria == '001' && codtipo2 == '03') || (codCategoria == '003' && (codtipo2 >= '08' && codtipo2 <= '10'))){ //se for container Sanitário ou Andaime Sapata ou Rodízio

			//mostra apenas duas opções
			$('#tipo3').val('')
			$('#tipo3').parent().hide()
		
		}else{
			$('#tipo3').parent().show()

		}
	});

	//restrição - a Betoneira com tipo 2 Combustão não tem tipo4
	$('#tipo3').change(function(){
		if($('#tipo3').val() == "19-02"){ //se escolheu Categoria Betoneira, e o tipo 3 for Combustão (id 19)
			$('#tipo4').parent().hide();
		}
	});

	$('#btnAddProduct').click(function(){ //quando escolho a opção para criar novo, abrindo um modal
		
		clearFieldsValues();

		clearErrors();

		loadCategories();
		
	});

	 
	 
	/* Cadastrar ou Editar Produto --------------------------------------------------------------*/	
	$("#btnSaveProduct").click(function(e) { //quando enviar o formulário de Produto
		e.preventDefault();
		
		let form = $('#formProduct');
		let formData = new FormData(form[0]);

		idProduto_gen = $('#idProduto_gen').val()
		//console.log("idProduto_gen:" + idProduto_gen)

		if(idProduto_gen == 0){ //se for para cadastrar --------------------------------------------------

			//console.log("você quer cadastrar")

			$.ajax({
				type: "POST",
				url: '/products/create',
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
							response.msg,
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
						
					}
					
				},
				error: function (response) {
					//$('#productModal').modal('hide');
					$('#formProduct').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
	
				}
			});

		}else{ /* se for para Editar -------------------------------------------------- */

			//console.log('você quer editar o produto: ' + idProduto_gen)
			
			$.ajax({
				type: "POST",
				url: `/products/${idProduto_gen}`, //rota para editar (update)
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
							response.msg,
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

		return false;
	});

});


	//carrega as opções de Categoria de produto
	async function loadCategories(category = false){
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
				
				return $("#formProduct #categoria").val()
			}
		
			//$("#productModal").modal();
		}).fail(function () {
			console.log("Rota não encontrada! (/products/categories/json)");
			return false
		});
	
	}

	let tipo2para3M
	//carrega as opções de tipo 1 de produto
	function loadTypes(idCategory, tipos = false){
		//para os tipos de produto do formulário
		types = []

		for(i=1; i<=4; i++){
			types[i] = '';
			types[i] = `<option value="">(escolha)</option>`
		}
		/*$('#tipo1').html('');
		$('#tipo2').html('');
		$('#tipo3').html('');
		$('#tipo4').html('');*/
		
		//console.log('loading types for category: ' + idCategory)
		
		tipo2para3M = '<option value="">(escolha)</option>'

		$.getJSON(`/products/types/json/${idCategory}`, function (data) { //ajax
						
			//console.log(data)

			data.forEach(function(item){
				//console.log(item)
				types[item.ordem_tipo] += `<option value="${item.id}-${item.codTipo}">${item.codTipo} - ${item.descTipo}</option>`

				//Guarda opção única para quando eu escolher o tipo 1 - 3M
				if(item.ordem_tipo == 2){
					if((item.codTipo == '01') || (item.codTipo == '07')){ //aceita almoxarifado (01) e especial (07)
						tipo2para3M +=  `<option value="${item.id}-${item.codTipo}">${item.codTipo} - ${item.descTipo}</option>`
					}

				}
			});		



			//Carrega os valores nos selects
			for(i=1; i<=4; i++){
				$(`#tipo${i}`).html(types[i])
			}
				
			if(idCategory == '1'){
				//console.log('você escolheu Container')
				$('#labelType1').html('Metragem (tipo1)')
				$('#labelType2').html('Modelo (tipo2)')
				$('#labelType3').html('Lavabo (tipo3)')
				$('#labelType4').html('Altura (tipo4)')

				for(i=1; i<=4; i++){
					$(`#tipo${i}`).parent().show()
				}

			}else if(idCategory == '2'){
				//console.log('você escolheu Betoneira')
				$('#labelType1').html('Marca (tipo1)')
				$('#labelType2').html('Modelo (tipo2)')
				$('#labelType3').html('Elét/Comb (tipo3)')
				$('#labelType4').html('Volt (tipo4)')

				
				for(i=1; i<=4; i++){
					$(`#tipo${i}`).parent().show()
				}

			}else if(idCategory == '3'){
				//console.log('você escolheu Andaime')
				$('#labelType1').html('Tipo (tipo1)')
				$('#labelType2').html('Peça (tipo2)')
				$('#labelType3').html('Metragem (tipo3)')
				$('#labelType4').html('N/A (tipo4)')

				for(i=1; i<=3; i++){
					$(`#tipo${i}`).parent().show()
				}

				$(`#tipo4`).parent().hide()

			}else if(idCategory == '4'){
				//console.log('você escolheu Escora')
				$('#labelType1').html('Metragem (tipo1)')
				/*$('#labelType2').html('N/A')
				$('#labelType3').html('N/A')
				$('#labelType4').html('N/A')*/

				$(`#tipo1`).parent().show()
				$(`#tipo2`).parent().hide()
				$(`#tipo3`).parent().hide()

				for(i=2; i<=4; i++){
					$(`#tipo${i}`).parent().hide()
				}
			}

		}).then(() => { 
			//console.log('carregou todos os tipos')

			if(tipos){
				//console.log('setting values in types')
				

				for(i=1; i<=4; i++){
					campo = i

					if(tipos[i-1] != undefined){
						if(tipos[i-1]['ordem_tipo'] != campo){ //se não tiver esse campo setado
							$(`#formProduct #tipo${campo}`).val('').parent().hide()
							campo++
						}
	
						tipo = tipos[i-1]['id']+'-'+tipos[i-1]['codTipo']
						//console.log(`tipo${campo}: ${tipo}`)
						$(`#formProduct #tipo${campo}`).val(tipo).prop('disabled', true);
	
						if(campo == 4){
							break;
						}

					}else{
						$(`#formProduct #tipo${campo}`).val('').parent().hide()
					}				
			
				}				
			}

			
		}).fail(function () {
			console.log("Rota não encontrada! (/products/types/json/:idCategory)");
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
			"url": "/products/list_datatables", //para chamar o método ajax_list_products
			"type": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});
}


//detalhes do Produto
function loadProduct(idProduto_gen) { //carrega todos os campos do modal referente ao produto escolhido
	clearFieldsValues();
	clearErrors();

	$('#modalTitle').html('Detalhes do Produto')
	$('#btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
	$('#btnSaveProduct').hide();
	$('#btnUpdate').show();

	$.getJSON(`/products/json/${idProduto_gen}`, function (data) { //ajax
		console.log(data)

		produto = data[0]
		tipos = data[1]

		$("#idProduto_gen").val(produto.idProduto_gen);
		//console.log('load View Produto idProduto_gen: ' + $("#idProduto_gen").val())

		$("#formProduct #codigoGen").val(produto.codigoGen).prop('disabled', true);
		$("#formProduct #numSerie").val(produto.numSerie).prop('disabled', true);
		$("#formProduct #descricao").val(produto.descricao) /*.prop('disabled', true);*/
		$("#formProduct #vlBaseAluguel").val(produto.vlBaseAluguel).prop('disabled', true);
		$("#formProduct #status").val(produto.status).prop('disabled', true);
		$("#formProduct #dtFabricacao").val(produto.dtFabricacao).prop('disabled', true);
		$("#formProduct #anotacoes").val(produto.anotacoes).prop('disabled', true);
		

		//carrega categorias, seleciona uma e em seguida carrega valores nos campos de Container
		loadCategories([produto.idCategoria, produto.codCategoria]);



		loadTypes(produto.idCategoria, tipos)

		/* Atualizar Produto ------------------------------------------------------------------ */
		$('#btnUpdate').click(function(){ //se eu quiser atualizar o Produto atual

			$('#modalTitle').html('Editar Produto');
			$('#btnClose').html('Cancelar').removeClass('btn-primary').addClass('btn-danger');
			$('#btnSaveProduct').val('Atualizar').show();
			$('#btnUpdate').hide();
		
			//$('#desImagePath').parent().show();

			//$("#formProduct #codigoGen").prop('disabled', true);
			
			$("#formProduct #numSerie").prop('disabled', false);
			//$("#formProduct #descricao").prop('disabled', false);
			$("#formProduct #vlBaseAluguel").prop('disabled', false);
			$("#formProduct #status").prop('disabled', false);
			$("#formProduct #dtFabricacao").prop('disabled', false);
			$("#formProduct #tipo1").prop('disabled', false);
			$("#formProduct #tipo2").prop('disabled', false);
			$("#formProduct #tipo3").prop('disabled', false);
			$("#formProduct #tipo4").prop('disabled', false);
			$("#formProduct #anotacoes").prop('disabled', false);
			$("#formProduct #fornecedor").prop('disabled', false);
			$("#formProduct #categoria").prop('disabled', false);

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
		console.log("Rota não encontrada! (/products/json/:idProduto_gen)");
	});

}

function deleteProduct(idProduto_gen){

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
				url: `/products/${idProduto_gen}/delete`,
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
							'Atenção!',
							response.msg,
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

	//$("#formProduct #codigoGen").prop('disabled', true)
	$('#modalTitle').html('Cadastrar Produto');
	$('#btnClose').html('Fechar').removeClass('btn-danger').addClass('btn-secondary');
	$('#btnSaveProduct').val('Cadastrar').show();
	$('#btnUpdate').hide();

	$('#dtCadastro').parent().hide(); //aparece a data de cadastro (só para visualizar)
	//$('#desImagePath').parent().show();


	//$("#formProduct #codigoGen").prop('disabled', true);
	$("#formProduct #numSerie").prop('disabled', false);
	//$("#formProduct #descricao").prop('disabled', false);
	$("#formProduct #vlBaseAluguel").prop('disabled', false);
	$("#formProduct #status").prop('disabled', false);
	$("#formProduct #dtFabricacao").prop('disabled', false);
	$("#formProduct #tipo1").prop('disabled', false);
	$("#formProduct #tipo2").prop('disabled', false);
	$("#formProduct #tipo3").prop('disabled', false);
	$("#formProduct #tipo4").prop('disabled', false);
	$("#formProduct #anotacoes").prop('disabled', false);
	$("#formProduct #fornecedor").prop('disabled', false);
	$("#formProduct #categoria").prop('disabled', false);

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

	

	//$('#image-preview').attr('src', "/res/img/productsp/product-default.jpg");
	$('#dtCadastro').parent().hide();

	$('#codigoGen').val('');
	$('#numSerie').val('');
	$('#descricao').val('');
	$('#vlBaseAluguel').val('');
	$('#status').val('1');
	$('#dtFabricacao').val('');

	$('#tipo1').html('');
	$('#tipo2').html('');
	$('#tipo3').html('');
	$('#tipo4').html('');

	for(i=1; i<=4; i++){
		$(`#tipo${i}`).parent().hide()
	}

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
	
	$('#idProduto_gen').val('0');
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

