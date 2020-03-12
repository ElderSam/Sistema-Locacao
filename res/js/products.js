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
		//alert('você escolheu ' + $('#categoria').val())
		codCategoria = $('#categoria').val().substring(2, 5);
		if(codCategoria != '001'){ //se não for um container
			
			$('#tipoPorta').parent().hide();
			$('#forrado').parent().hide();
			$('#janelasLat').parent().hide();
			$('#janelasCirc').parent().hide();
			$('#eletrificado').parent().hide();
			$('#entrada').parent().hide();
			$('#tomadas').parent().hide();
			$('#lampadas').parent().hide();
			$('#chuveiro').parent().hide();

		}else{

			$('#tipoPorta').parent().show();
			$('#forrado').parent().show();
			$('#janelasLat').parent().show();
			$('#janelasCirc').parent().show();
			$('#eletrificado').parent().show();
			$('#entrada').parent().show();
			$('#tomadas').parent().show();
			$('#lampadas').parent().show();
			$('#chuveiro').parent().show();
		}
		
		tam = $('#categoria').val().length //pega o tamanho da string (ex: 34-001) (id-categoria)
		idCategoria = $('#categoria').val().substring(0,(tam - 4)); //tira os quatro últimos caracteres e pega o id
		
		loadTypes(idCategoria);
		showsNextNumber(idCategoria); //mostra próximo número de série da Categoria

	});

	//restrição - o container 3M só pode ser do tipo Almoxarifado ou Especial
	$('#tipo1').change(function(){

		tam = $('#categoria').val().length //pega o tamanho da string (ex: 34-001) (id-categoria)
		codCategoria = $('#categoria').val().substring((tam-3),tam); //tira os quatro últimos caracteres e pega o id
		//console.log(codCategoria)
		
		tam = $('#tipo1').val().length
		codtipo1 = $('#tipo1').val().substring((tam-2),tam); 

		if(codCategoria == '001' && codtipo1 == '01'){ //se for container com metragem 3M

			//mostra apenas duas opções
			$('#tipo2').html(tipo2para3M) 
		
		}else{
			$(`#tipo2`).html(types[2])

		}
	});

	//restrição - o container Sanitário obrigatoriamente tem Lavabo
	$('#tipo2').change(function(){

		tam = $('#categoria').val().length //pega o tamanho da string (ex: 34-001) (id-categoria)
		codCategoria = $('#categoria').val().substring((tam-3),tam); //tira os quatro últimos caracteres e pega o id
		
		tam = $('#tipo2').val().length
		codtipo2 = $('#tipo2').val().substring((tam-2),tam); 

		if(codCategoria == '001' && codtipo2 == '03'){ //se for container Sanitário

			//mostra apenas duas opções
			$('#tipo3').val()
			$('#tipo3').parent().hide()
		
		}else{
			$('#tipo3').parent().show()

		}
	});

	$('#btnAddProduct').click(function(){ //quando escolho a opção para criar novo, abrindo um modal
		
		clearFieldsValues();

		clearErrors();

		loadCategories();
		loadSuppliers(); //Carrega fornecedores
		
	});

	 
	 
	/* Cadastrar ou Editar Produto --------------------------------------------------------------*/	
	$("#btnSaveProduct").click(function(e) { //quando enviar o formulário de Produto
		e.preventDefault();
		
		let form = $('#formProduct');
		let formData = new FormData(form[0]);

		idProduto = $('#idProduto').val()
		//console.log("idProduto:" + idProduto)

		if(idProduto == 0){ //se for para cadastrar --------------------------------------------------

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
						
					}
					
				},
				error: function (response) {
					//$('#productModal').modal('hide');
					$('#formProduct').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
	
				}
			});

		}else{ /* se for para Editar -------------------------------------------------- */

			//console.log('você quer editar o produto: ' + idProduto)
			
			$.ajax({
				type: "POST",
				url: `/products/${idProduto}`, //rota para editar
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
						console.log('erro ao editar Produto!')

						response = JSON.parse(response)

						Swal.fire(
							'Erro!',
							'Ocorreu algum erro ao Editar',
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
							'Product atualizado!',
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
	function loadCategories(){
		$.getJSON(`/products/categories/json`, function (data) { //ajax
			
			//console.log(data)
			
			let categories = `<option value="">(escolha)</option>`
			
			data.forEach(function(item){
				//console.log(item)
				categories += `<option value="${item.idCategoria}-${item.codCategoria}">${item.codCategoria} - ${item.descCategoria}</option>`
			});

			$('#categoria').html(categories)
						

		}).then(() => { 

			//$("#productModal").modal();
		}).fail(function () {
			console.log("Rota não encontrada!");
		});
	
	}

	let tipo2para3M
	//carrega as opções de tipo 1 de produto
	function loadTypes(idCategory){
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
				types[item.ordem_tipo] += `<option value="${item.id}-${item.codigo}">${item.codigo} - ${item.descricao}</option>`

				//Guarda opção única para quando eu escolher o tipo 1 - 3M
				if(item.ordem_tipo == 2){
					if((item.codigo == '01') || (item.codigo == '07')){ //aceita almoxarifado (01) e especial (07)
						tipo2para3M +=  `<option value="${item.id}-${item.codigo}">${item.codigo} - ${item.descricao}</option>`
					}

				}
			});		



			//Carrega os valores nos selects
			for(i=1; i<=4; i++){
				$(`#tipo${i}`).html(types[i])
			}
				
			if(idCategory == '1'){
				//console.log('você escolheu Container')
				$('#labelType1').html('Metragem')
				$('#labelType2').html('Modelo')
				$('#labelType3').html('Lavabo')
				$('#labelType4').html('Altura')

				for(i=1; i<=4; i++){
					$(`#tipo${i}`).parent().show()
				}

			}else if(idCategory == '2'){
				//console.log('você escolheu Betoneira')
				$('#labelType1').html('Marca')
				$('#labelType2').html('Modelo')
				$('#labelType3').html('Elét/Comb')
				$('#labelType4').html('Volt')

				
				for(i=1; i<=4; i++){
					$(`#tipo${i}`).parent().show()
				}

			}else if(idCategory == '3'){
				//console.log('você escolheu Andaime')
				$('#labelType1').html('Tipo')
				$('#labelType2').html('Peça')
				$('#labelType3').html('Metragem')
				$('#labelType4').html('N/A')

				for(i=1; i<=3; i++){
					$(`#tipo${i}`).parent().show()
				}

				$(`#tipo4`).parent().hide()

			}else if(idCategory == '4'){
				//console.log('você escolheu Escora')
				$('#labelType1').html('Metragem')
				/*$('#labelType2').html('N/A')
				$('#labelType3').html('N/A')
				$('#labelType4').html('N/A')*/

				$(`#tipo1`).parent().show()

				for(i=2; i<=4; i++){
					$(`#tipo${i}`).parent().hide()
				}
			}

		}).then(() => { 

		}).fail(function () {
			console.log("Rota não encontrada!");
		});
	
	}

	//carrega as opções de Fornecedor de um produto de categoria específico
	function loadSuppliers(){
		$.getJSON(`/suppliers/json`, function (data) { //ajax
			
			//console.log(data)
			
			let suppliers = `<option value="">(escolha)</option>`			

			data.forEach(function(item){
				//console.log(item)
				suppliers += `<option value="${item.idFornecedor}-${item.codFornecedor}">${item.codFornecedor} - ${item.nome}</option>`
			});

			$('#fornecedor').html(suppliers)
						

		}).then(() => { 

			//$("#productModal").modal();
		}).fail(function () {
			console.log("Rota não encontrada!");
		});
	
	}

	function showsNextNumber(idCategory){ //mostra o próximo número de série relacionado à categoria
		$.ajax({
			type: "POST",
			url: `/products/showsNextNumber/${idCategory}`,
			contentType: false,
			processData: false,
			
			success: function (response) {
		
				console.log('próximo número de série: ' + response)
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
function loadProduct(idProduto) { //carrega todos os campos do modal referente ao produto escolhido
	clearFieldsValues();
	clearErrors();
	loadCategories();

	$('#modalTitle').html('Detalhes do Produto')
	$('#btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
	$('#btnSaveProduct').hide();
	$('#btnUpdate').show();
	
	$('#dtCadastro').parent().show(); //aparece a data de cadastro (só para visualizar)
	//$('#desImagePath').parent().hide();


	$.getJSON(`/products/json/${idProduto}`, function (data) { //ajax
		console.log(data)

		$("#formProduct #codigo").val(data.codigo).prop('disabled', true);
		$("#formProduct #descricao").val(data.descricao).prop('disabled', true);
		$("#formProduct #valorCompra").val(data.valorCompra).prop('disabled', true);
		$("#formProduct #status").val(data.status).prop('disabled', true);
		$("#formProduct #dtFabricacao").val(data.dtFabricacao).prop('disabled', true);
		$("#formProduct #tipo").val(data.tipo).prop('disabled', true);
		$("#formProduct #anotacoes").val(data.anotacoes).prop('disabled', true);
		$("#formProduct #idFornecedor").val(data.idFornecedor).prop('disabled', true);
		$("#formProduct #idCategoria").val(data.idCategoria).prop('disabled', true);
		$("#formProduct #dtCadastro").val(data.dtCadastro).prop('disabled', true);
		$("#idProduto").val(data.idProduto);
		//console.log('load View Produto idProduto: ' + $("#idProduto").val())

		dtCadastro = formatDate(data.dtCadastro)
		//console.log('data: ' + dtCadastro)
		$("#formProduct #dtCadastro").val(dtCadastro);
			
		//$("#formProduct #image-preview").attr("src", data.foto); //mostra a imagem atual
		//$("#desmagePath").val(data.foto);

		/* Atualizar Produto ------------------------------------------------------------------ */
		$('#btnUpdate').click(function(){ //se eu quiser atualizar o Produto atual

			$('#modalTitle').html('Editar Produto');
			$('#btnClose').html('Cancelar').removeClass('btn-primary').addClass('btn-danger');
			$('#btnSaveProduct').val('Atualizar').show();
			$('#btnUpdate').hide();
		
			//$('#desImagePath').parent().show();

			//$("#formProduct #codigo").prop('disabled', true);
			$("#formProduct #descricao").prop('disabled', false);
			$("#formProduct #valorCompra").prop('disabled', false);
			$("#formProduct #status").prop('disabled', false);
			$("#formProduct #dtFabricacao").prop('disabled', false);
			$("#formProduct #tipo").prop('disabled', false);
			$("#formProduct #anotacoes").prop('disabled', false);
			$("#formProduct #idFornecedor").prop('disabled', false);
			$("#formProduct #idCategoria").prop('disabled', false);
				
		}); /* Fim Atualizar Produto ---------------------------------------------------------- */
			

	}).then(() => { 

		$("#productModal").modal();
	}).fail(function () {
		console.log("Rota não encontrada!");
	});

}

function deleteProduct(idProduto){

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
				url: `/products/${idProduto}/delete`,
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
							'Não foi possível deletar',
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

					console.log(`Erro! Mensagem: ${response}`);		
				}
			});		

		}
	})

	$('.swal2-cancel').html('Cancelar');
}


//limpar campos do modal para Cadastrar
function clearFieldsValues(){

	//$("#formProduct #codigo").prop('disabled', true)
	$('#modalTitle').html('Cadastrar Produto');
	$('#btnClose').html('Fechar').removeClass('btn-danger').addClass('btn-secondary');
	$('#btnSaveProduct').val('Cadastrar').show();
	$('#btnUpdate').hide();

	$('#dtCadastro').parent().hide(); //aparece a data de cadastro (só para visualizar)
	//$('#desImagePath').parent().show();


	//$("#formProduct #codigo").prop('disabled', true);
	$("#formProduct #descricao").prop('disabled', false);
	$("#formProduct #valorCompra").prop('disabled', false);
	$("#formProduct #status").prop('disabled', false);
	$("#formProduct #dtFabricacao").prop('disabled', false);
	$("#formProduct #tipo").prop('disabled', false);
	$("#formProduct #anotacoes").prop('disabled', false);
	$("#formProduct #idFornecedor").prop('disabled', false);
	$("#formProduct #idCategoria").prop('disabled', false);
	

	//$('#image-preview').attr('src', "/res/img/productsp/product-default.jpg");
	$('#dtCadastro').parent().hide();

	$('#codigo').val('');
	$('#descricao').val('');
	$('#valorCompra').val('');
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
	$('#idFornecedor').val('0');
	$('#idContainer').val('0');

	//campos de container
	$('#tipoPorta').val('');
	$('#forrado').val('');
	$('#janelasLat').val('');
	$('#janelasCirc').val('');
	$('#eletrificado').val('');
	$('#entrada').val('');

	$('#tipoPorta').parent().hide();
	$('#forrado').parent().hide();
	$('#janelasLat').parent().hide();
	$('#janelasCirc').parent().hide();
	$('#eletrificado').parent().hide();
	$('#entrada').parent().hide();
	$('#tomadas').parent().hide();
	$('#lampadas').parent().hide();
	$('#chuveiro').parent().hide();
	//fimr campos container
	
	$('#categoria').val('0');
	$('#dtCadastro').val('');
	
	$('#idProduto').val('0');
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

