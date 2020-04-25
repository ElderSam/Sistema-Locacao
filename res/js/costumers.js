
$(function() { //quando a página carrega

	//carrega a tabela de Clientes
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR, //tradução
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true, 
		"ajax": {
			"url": "/costumers/list_datatables", //chama a rota para carregar os dados 
			"type": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]

	});

	//Função para o botão que cria novos Clientes
	$('#btnAddCostumer').click(function(){
		limparCampos();

		clearErrors();
	});

	$('#tipoCliente').click(function(){

		exibir_ocultar();
	});
	 
	let numResp = 0;

	$('#btnResp').click(function(){

		$('#camposResp').append(`<input type="text" id='resp_${numResp}'>`)
	})
	 
	/* Cadastrar ou Editar Clientes --------------------------------------------------------------*/	
	$("#btnSaveCostumer").click(function(e) { //quando enviar o formulário de Clientes

		e.preventDefault();
		
		let form = $('#formCostumer');

		//A interface FormData fornece uma maneira fácil de construir um conjunto de pares chave/valor representando campos
	 // de um elemento form
		let formData = new FormData(form[0]);

		idCostumer = $('#codigo').val()

		if((idCostumer == 0) || (idCostumer == undefined)){ //Se for para cadastrar --------------------------------------------------


			$.ajax({
				type: "POST",
				url: '/costumer/create',
				data: formData,
				contentType: false,
				processData: false,

				//Conforme indica a documentação, a opção beforeSend deve ser usada para executar efeitos (ou mudar as
				// opções da operação), antes da requisição ser efetuada.
				beforeSend: function() {
					clearErrors();
					$("#btnSaveCostumer").parent().siblings(".help-block").html(loadingImg("Verificando..."));
				
				},
				success: function (response) {
					clearErrors();
	
					if (JSON.parse(response).error) {
						console.log('erro ao cadastrar novo Cliente!')
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
						$('#costumerModal').modal('hide');
						
						//console.log(response)
						Swal.fire(
							'Sucesso!',
							'Cliente cadastrado!',
							'success'
							)
	
						loadTableCostumers();
						$('#formCostumer').trigger("reset");
						
					}
					
				},
				error: function (response) {
					$('#formCostumer').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
	
				}
			});

		}else{ /* se for para Editar -------------------------------------------------- */

			//console.log('você quer editar o cliente: ' + idCostumer)
			
			$.ajax({
				type: "POST",
				url: `/costumer/${idCostumer}`, //rota para editar
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function() {
					clearErrors();
					$("#btnSaveCostumer").parent().siblings(".help-block").html(loadingImg("Verificando..."));
				
				},
				success: function (response) {
					clearErrors();

					if (JSON.parse(response).error) {
						console.log('erro ao editar cliente!')

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
						$('#costumerModal').modal('hide');

						Swal.fire(
							'Sucesso!',
							'Cliente atualizado!',
							'success'
						);

						loadTableCostumers();
						$('#formCostumer').trigger("reset");
					}
	
				},
				error: function (response) {
	
					//$('#CostumerModal').modal('hide');
					$('#formCostumer').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
	
				}
			});
		}	

		return false;
	});

});


function loadTableCostumers(){ //carrega a tabela de Clientes depois de umas alteração

	myTable.destroy(); //desfaz as paginações
	
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true,
		"ajax": {
			"url": "/costumers/list_datatables", //para chamar o método ajax_list_user
			"type": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});
}


//detalhes do cliente
function loadCostumer(idCliente) { //carrega todos os campos do modal referente ao cliente escolhido
	
	clearErrors();

	$('#modalTitle').html('Detalhes do Cliente')
	$('#btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
	$('#btnSaveCostumer').hide();
	$('#btnUpdate').show();
	
	$('#dtCadastro').parent().show(); //aparece a data de cadastro (só para visualizar)


	$.getJSON(`/costumers/json/${idCliente}`, function (data) {
		console.log(data);

		$("#formCostumer #codigo").val(data.idCliente);
		$("#formCostumer #nome").val(data.nome).prop('disabled', true);
		$("#formCostumer #status").val(data.status).prop('disabled', true);
		$("#formCostumer #tipoCliente").val(data.tipoCliente).prop('disabled', true);
		$("#formCostumer #telefone1").val(data.telefone1).prop('disabled', true);
		$("#formCostumer #telefone2").val(data.telefone2).prop('disabled', true);
		$("#formCostumer #email1").val(data.email1).prop('disabled', true);
		$("#formCostumer #email2").val(data.email2).prop('disabled', true);
		$("#formCostumer #uf").val(data.uf).prop('disabled', true);
		$("#formCostumer #cep").val(data.cep).prop('disabled', true);
		$("#formCostumer #cpf").val(data.cpf).prop('disabled', true);
		$("#formCostumer #rg").val(data.rg).prop('disabled', true);
		$("#formCostumer #ie").val(data.ie).prop('disabled', true);
		$("#formCostumer #cnpj").val(data.cnpj).prop('disabled', true);
		$("#formCostumer #cidade").val(data.cidade).prop('disabled', true);
		$("#formCostumer #bairro").val(data.bairro).prop('disabled', true);
		$("#formCostumer #endereco").val(data.endereco).prop('disabled', true);
		$("#formCostumer #numero").val(data.numero).prop('disabled', true);
		$("#formCostumer #complemento").val(data.complemento).prop('disabled', true);
	
		/*
		if(data.tipoCliente == "J"){
			$("#formCostumer #ie").val(data.ie).prop('disabled', true);
			$("#formCostumer #cnpj").val(data.cnpj).prop('disabled', true);

			$("#formCostumer #rg").parent().hide();
			$("#formCostumer #cpf").parent().hide();
		}else{
			$("#formCostumer #rg").val(data.rg).prop('disabled', true);
			$("#formCostumer #cpf").val(data.cpf).prop('disabled', true);

			$("#formCostumer #ie").parent().hide();
			$("#formCostumer #cnpj").parent().hide();
		}
		*/
		dtCadastro = formatDate(data.dtCadastro)
		//console.log('data: ' + dtCadastro)
		$("#formCostumer #dtCadastro").val(dtCadastro);
		$('#formCostumer #divCodigo').hide();

		/* Atualizar Cliente ------------------------------------------------------------------ */
		$('#btnUpdate').click(function(){ //se eu quiser atualizar o Cliente atual

			$('#formCostumer #divCodigo').hide();
			$('#modalTitle').html('Editar Cliente');
			$('#btnClose').html('Cancelar').removeClass('btn-primary').addClass('btn-danger');
			$('#btnSaveCostumer').val('Atualizar').show();
			$('#btnUpdate').hide();

	
		$("#formCostumer #nome").prop('disabled', false);
		$("#formCostumer #status").prop('disabled', false);
		$("#formCostumer #tipoCliente").prop('disabled', false);
		$("#formCostumer #rg").prop('disabled', false);
		$("#formCostumer #cpf").prop('disabled', false);
		$("#formCostumer #ie").prop('disabled', false);
		$("#formCostumer #cnpj").prop('disabled', false);
		$("#formCostumer #telefone1").prop('disabled', false);
		$("#formCostumer #telefone2").prop('disabled', false);
		$("#formCostumer #email1").prop('disabled', false);
		$("#formCostumer #email2").prop('disabled', false);
		$("#formCostumer #uf").prop('disabled', false);
		$("#formCostumer #cep").prop('disabled', false);
		$("#formCostumer #cidade").prop('disabled', false);
		$("#formCostumer #bairro").prop('disabled', false);
		$("#formCostumer #endereco").prop('disabled', false);
		$("#formCostumer #numero").prop('disabled', false);
		$("#formCostumer #complemento").prop('disabled', false);
		}); /* Fim Atualizar Usuário ---------------------------------------------------------- */
			

	}).then(() => { 

		$("#costumerModal").modal();
		
	}).fail(function () {
		console.log("Rota não encontrada!");
	});

}

function deleteCostumer(idCliente){

	Swal.fire({
		title: 'Você tem certeza?',
		text: "Você não será capaz de reverter isso!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor:'#d33',
		cancelButtonColor: '#3085d6',
		confirmButtonText: 'Sim, apagar!'

	}).then((result) => {

		if (result.value) {

			$.ajax({
				type: "POST",
				url: `/costumers/${idCliente}/delete`,
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

						loadTableCostumers();					
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
function limparCampos(){
	$('#formCostumer #divCodigo').hide();
	$('#modalTitle').html('Cadastrar Cliente');
	$('#btnClose').html('Fechar').removeClass('btn-danger').addClass('btn-secondary');
	$('#btnSaveCostumer').val('Cadastrar').show();
	$('#btnUpdate').hide();



	$("#formCostumer #nome").prop('disabled', false);
	$("#formCostumer #status").prop('disabled', false);
	$("#formCostumer #tipoCliente").prop('disabled', false);
	$("#formCostumer #rg").prop('disabled', false);
	$("#formCostumer #cpf").prop('disabled', false);
	$("#formCostumer #ie").prop('disabled', false);
	$("#formCostumer #cnpj").prop('disabled', false);
	$("#formCostumer #telefone1").prop('disabled', false);
	$("#formCostumer #telefone2").prop('disabled', false);
	$("#formCostumer #email1").prop('disabled', false);
	$("#formCostumer #email2").prop('disabled', false);
	$("#formCostumer #dtCadastro").parent().hide();
	$("#formCostumer #uf").prop('disabled', false);
	$("#formCostumer #cep").prop('disabled', false);
	$("#formCostumer #cidade").prop('disabled', false);s
	$("#formCostumer #bairro").prop('disabled', false);
	$("#formCostumer #endereco").prop('disabled', false);
	$("#formCostumer #numero").prop('disabled', false);
	$("#formCostumer #complemento").prop('disabled', false);


	$('#codigo').val('');
	$('#nome').val('');
	$('#status').val('0');
	$('#tipoCliente').val('J');
	$('#rg').val('');
	$('#cpf').val('');
	$('#ie').val('0');
	$('#cnpj').val('');
	$('#telefone1').val('');
	$('#telefone2').val('');
	$('#email1').val('');
	$('#email2').val('');
	$('#dtCadastro').val('');
	$('#uf').val('');
	$('#cep').val('');
	$('#cidade').val('');
	$('#bairro').val('');
	$('#endereco').val('');
	$('#numero').val(0);
	$('#complemento').val('');
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

$(document).on("keydown", "#complemento", function () {
    var caracteresRestantes = 150;
    var caracteresDigitados = parseInt($(this).val().length);
    var caracteresRestantes = caracteresRestantes - caracteresDigitados;

    $(".caracteres").text(caracteresRestantes);
});


function exibir_ocultar(){
    var valor = $("#tipoCliente").val();

    if(valor == 'F'){
         $("#labelCnpj").hide();
		 $("#cnpj").hide();
		 $("#labelIe").hide();
		 $("#ie").hide();
		 $("#labelCpf").show();
		 $("#cpf").show();
		 $("#labelRg").show();
         $("#rg").show();
     } else if(valor == 'J'){
		 $("#labelCnpj").show();
		 $("#cnpj").show();
		 $("#labelIe").show();
		 $("#ie").show();
		 $("#labelCpf").hide();
		 $("#cpf").hide();
		 $("#labelRg").hide();
         $("#rg").hide();
	 }else{
		$("#labelCnpj").hide();
		$("#cnpj").hide();
		$("#labelIe").hide();
		$("#ie").hide();
		$("#labelCpf").hide();
		$("#cpf").hide();
		$("#labelRg").hide();
		$("#rg").hide();
	 }
	 
};


$(function(){
	$("#rg").mask("00.000.000-A");
	$("#cpf").mask("999.999.999-99",{placeholder:"000.000.000-00"});
	$("#telefone1, #telefone2").mask("(00) 0000-00009", {placeholder:"(00)0000-0000"});
	$("#cnpj").mask("99.999.999/9999-99",{placeholder:"00.000.00/0000-00"});
	$("#cep").mask("99999-999", {placeholder:"00000-000"}); 
});

