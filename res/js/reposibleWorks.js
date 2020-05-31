
$(function() { //quando a página carrega


	 var parameters = window.location.href.split('/');


	 idCostumer = parameters[parameters.length -1];
	

	//carrega a tabela de Chefe de Obras
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR, //tradução
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true, 
		"ajax": {
			"url": `/reposibleWorks/list_datatables/${idCostumer}`, //chama a rota para carregar os dados 
			"type": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});

	//Função para o botão que cria novos chefes de obra
	$('#btnAddReposibleWorks').click(function(){
		limparCampos();
		clearErrors();
	});


	/* Cadastrar ou Editar Responsaveis --------------------------------------------------------------*/	
	$("#btnSaveReposibleWorks").click(function(e) { //quando enviar o formulário de Responsaveis

		e.preventDefault();
		
		let form = $('#formReposibleWorks');

		//A interface FormData fornece uma maneira fácil de construir um conjunto de pares chave/valor representando campos
	 // de um elemento form
		let formData = new FormData(form[0]);

		idReposible = $('#id').val()

		if((idReposible == 0) || (idReposible == undefined)){ //Se for para cadastrar --------------------------------------------------
	
			var parameters = window.location.href.split('/');


			 idCostumer = parameters[parameters.length -1];
			 
			$.ajax({
				type: "POST",
				url: `/reposibleWorks/create/${idCostumer}`,
				data: formData,
				contentType: false,
				processData: false,

				//Conforme indica a documentação, a opção beforeSend deve ser usada para executar efeitos (ou mudar as
				// opções da operação), antes da requisição ser efetuada.
				beforeSend: function() {
					clearErrors();
					$("#btnSaveReposibleWorks").parent().siblings(".help-block").html(loadingImg("Verificando..."));
				
				},
				success: function (response) {
					clearErrors();
	
					if (JSON.parse(response).error) {
						console.log('erro ao cadastrar novo Responsável!')
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
						$('#reposibleWorksModal').modal('hide');
						
						//console.log(response)
						Swal.fire(
							'Sucesso!',
							'Responsável Cadastrado!',
							'success'
							)
	
						loadTableReposibleWorks();
						$('#formReposibleWorks').trigger("reset");
						
					}
					
				},
				error: function (response) {
					$('#formReposibleWorks').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
	
				}
			});

		}else{ /* se for para Editar -------------------------------------------------- */

			//console.log('você quer editar o cliente: ' + idCostumer)
			
			$.ajax({
				type: "POST",
				url: `/reposibleWorks/${idReposible}`, //rota para editar
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function() {
					clearErrors();
					$("#btnSaveReposibleWorks").parent().siblings(".help-block").html(loadingImg("Verificando..."));
				
				},
				success: function (response) {
					clearErrors();


					if (JSON.parse(response).error) {
						console.log('erro ao editar Responsável!')

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
						$('#reposibleWorksModal').modal('hide');

						Swal.fire(
							'Sucesso!',
							'Responsável Atualizado!',
							'success'
						);

						loadTableReposibleWorks();
						$('#formReposibleWorks').trigger("reset");
					}
	
				},
				error: function (response) {
	
					//$('#userModal').modal('hide');
					$('#formReposibleWorks').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
	
				}
			});
		}	

		return false;
	});

});


function loadTableReposibleWorks(){ //carrega a tabela de Chesfes de Obra depois de umas alteração

	var parameters = window.location.href.split('/');


	idCostumer = parameters[parameters.length -1];

	myTable.destroy(); //desfaz as paginações
	
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true,
		"ajax": {
			"url": `/reposibleWorks/list_datatables/${idCostumer}`, //para chamar o método ajax_list_reposibleWorks
			"type": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});
}


//Detalhes de Chefe de Obras 
function loadReposibleWorks(idResp) { //carrega todos os campos do modal referente ao chefe escolhido
	
	//clearErrors();

	$('#formReposibleWorks #divId').hide();
	$('#formReposibleWorks #divCliente').hide();
	$('#modalTitle').html('Detalhes de Responsáveis de Obras')
	$('#btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
	$('#btnSaveReposibleWorks').hide();
	$('#btnUpdate').show();
	
	$('#dtCadastro').parent().show(); //aparece a data de cadastro (só para visualizar)


	$.getJSON(`/reposibleWorks2/json/${idResp}`, function (data) {
		//console.log(data.id_fk_cliente);

		$("#formReposibleWorks #id").val(data.idResp);
		$("#formReposibleWorks #id_fk_cliente").val(data.id_fk_cliente);
		$("#formReposibleWorks #respObra").val(data.respObra).prop('disabled', true);
		$("#formReposibleWorks #telefone1").val(data.telefone1).prop('disabled', true);
        $("#formReposibleWorks #telefone2").val(data.telefone2).prop('disabled', true);
        $("#formReposibleWorks #telefone3").val(data.telefone3).prop('disabled', true);
		$("#formReposibleWorks #email1").val(data.email1).prop('disabled', true);
		$("#formReposibleWorks #email2").val(data.email2).prop('disabled', true);
		$("#formReposibleWorks #anotacoes").val(data.anotacoes).prop('disabled', true);
		$("#formReposibleWorks #cliente").val(data.nome).prop('disabled', true);
		
		dtCadastro = formatDate(data.dtCadastro)
		//console.log('data: ' + dtCadastro)
		$("#formReposibleWorks #dtCadastro").val(dtCadastro);

	
		$('#btnUpdate').click(function(){ //se eu quiser atualizar o Cliente atual

			$('#formReposibleWorks #divId').hide();
			$('#modalTitle').html('Editar Responsável de Obra');
			$('#btnClose').html('Cancelar').removeClass('btn-primary').addClass('btn-danger');
			$('#btnSaveReposibleWorks').val('Atualizar').show();
			$('#btnUpdate').hide();

		$("#formReposibleWorks #respObra").prop('disabled', false);
		$("#formReposibleWorks #telefone1").prop('disabled', false);
        $("#formReposibleWorks #telefone2").prop('disabled', false);
        $("#formReposibleWorks #telefone3").prop('disabled', false);
		$("#formReposibleWorks #email1").prop('disabled', false);
		$("#formReposibleWorks #email2").prop('disabled', false);
		$("#formReposibleWorks #anotacoes").prop('disabled', false);	
		}); 
			
		
	}).then(() => { 

		$("#reposibleWorksModal").modal();
	}).fail(function () {
		console.log("Rota não encontrada!");
	});

}

function deleteReposible(idResp){

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
				url: `/reposibleWorks/${idResp}/delete`,
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

						loadTableReposibleWorks();					
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

	$('#divId').hide();
	$('#formReposibleWorks #divCliente').hide();
	$('#formReposibleWorks #cliente').prop('disabled', true)
	$('#modalTitle').html('Cadastrar Responsável de Obra');
	$('#btnClose').html('Fechar').removeClass('btn-danger').addClass('btn-secondary');
	$('#btnSaveReposibleWorks').val('Cadastrar').show();
	$('#btnUpdate').hide();


	$("#formReposibleWorks #respObra").prop('disabled', false);
//	$("#formReposibleWorks #id_fk_cliente").prop('disabled', false);
	$("#formReposibleWorks #telefone1").prop('disabled', false);
	$("#formReposibleWorks #telefone2").prop('disabled', false);
	$("#formReposibleWorks #telefone3").prop('disabled', false);
	$("#formReposibleWorks #email1").prop('disabled', false);
	$("#formReposibleWorks #email2").prop('disabled', false);
	$("#formReposibleWorks #dtCadastro").parent().hide();
	$("#formReposibleWorks #anotacoes").prop('disabled', false);

	$('#id').val(0);
//	$('#id_fk_cliente').val('');
	$('#respObra').val('');
	$('#telefone1').val('');
	$('#telefone2').val('');
	$('#telefone3').val('');
	$('#email1').val('');
	$('#email2').val('');
	$('#dtCadastro').val('');
	$('#anotacoes').val('');
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

$(document).on("keydown", "#anotacoes", function () {
    var caracteresRestantes = 150;
    var caracteresDigitados = parseInt($(this).val().length);
    var caracteresRestantes = caracteresRestantes - caracteresDigitados;

    $(".caracteres").text(caracteresRestantes);
});


$(function(){
	$("#telefone1, #telefone2, #telefone3").mask("(00) 0000-00009", {placeholder:"(00)0000-00009"});
});



