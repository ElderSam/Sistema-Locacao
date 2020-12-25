$(function() { //quando a página carrega


    var parameters = window.location.href.split('/');


    idCostumer = parameters[parameters.length -1];
    setCostumerName(idCostumer)

   //Carrega a tabela de Obras
   myTable = $("#dataTable").DataTable({ 
       "oLanguage": DATATABLE_PTBR, //tradução
       "autoWidth": false, //largura
       "processing": true, //mensagem 'processando'
       "serverSide": true, 
       "ajax": {
           "url": `/construction/list_datatables/${idCostumer}`, //chama a rota para carregar os dados 
           "type": "POST",
       },
       "columnDefs": [
           { targets: "no-sort", orderable: false }, //para não ordenar
           { targets: "text-center", className: "text-center" },
       ]
   });

   //Função para o botão que cria nova obra
   $('#btnAddConstruction').click(function(){
       showsNextNumber();
       limparCampos();
       clearErrors();
   });


   /* Cadastrar ou Editar Obras --------------------------------------------------------------*/	
   $("#btnSaveConstruction").click(function(e) { //quando enviar o formulário de Responsaveis

       e.preventDefault();

       $("#formConstruction #codObra").attr('disabled', false);
       
       let form = $('#formConstruction');

       //A interface FormData fornece uma maneira fácil de construir um conjunto de pares chave/valor representando campos
    // de um elemento form
       let formData = new FormData(form[0]);

       idConstruction = $('#IdObra').val();

       if((idConstruction == 0) || (idConstruction == undefined)){ //Se for para cadastrar --------------------------------------------------
           
           var parameters = window.location.href.split('/');

            idCostumer = parameters[parameters.length -1];
            
           $.ajax({
               type: "POST",
               url: `/construction/create/${idCostumer}`,
               data: formData,
               contentType: false,
               processData: false,

               //Conforme indica a documentação, a opção beforeSend deve ser usada para executar efeitos (ou mudar as
               // opções da operação), antes da requisição ser efetuada.
               beforeSend: function() {
                   clearErrors();
                   $("#btnSaveConstruction").parent().siblings(".help-block").html(loadingImg("Verificando..."));
                    console.log("id respObra: " + $("#respObra").val())
               },
               success: function (response) {
                   
                   clearErrors();
   
                   if (JSON.parse(response).error) {
                       console.log('erro ao cadastrar nova Obra!')
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
                       $('#constructionModal').modal('hide');
                       
                       //console.log(response)
                       Swal.fire(
                           'Sucesso!',
                           'Obra Cadastrada!',
                           'success'
                           )
   
                       loadTableConstruction();
                       $('#formConstruction').trigger("reset");
                       
                   }
                   
               },
               error: function (response) {
                   $('#formConstruction').trigger("reset");
                   console.log(`Erro! Mensagem: ${response}`);
   
               }
           });

       }else{ /* se for para Editar -------------------------------------------------- */
           
           $.ajax({
               type: "POST",
               url: `/construction/${idConstruction}`, //rota para editar
               data: formData,
               contentType: false,
               processData: false,
               beforeSend: function() {
                   clearErrors();
                   $("#btnSaveConstruction").parent().siblings(".help-block").html(loadingImg("Verificando..."));
               
               },
               success: function (response) {
                   clearErrors();

                  
                   if (JSON.parse(response).error) {
                       console.log('erro ao editar Obra!')

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
                    
                       $('#constructionModal').modal('hide');

                       Swal.fire(
                           'Sucesso!',
                           'Obra Atualizada!',
                           'success'
                       );

                       loadTableConstruction();
                       $('#formConstruction').trigger("reset");
                   }
   
               },
               error: function (response) {
   
                   //$('#userModal').modal('hide');
                   $('#formConstruction').trigger("reset");
                   console.log(`Erro! Mensagem: ${response}`);
   
               }
           });
       }	

       return false;
   });

});

function setCostumerName(idCostumer) {
	$.getJSON(`/costumers/json/${idCostumer}`, function (data) {
		$("#costumer-name-header").html(` - cliente: ${data.nome}`)
	});
}

function loadTableConstruction(){ //carrega a tabela de Chesfes de Obra depois de umas alteração

	var parameters = window.location.href.split('/');


	idCostumer = parameters[parameters.length -1];

	myTable.destroy(); //desfaz as paginações
	
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true,
		"ajax": {
			"url": `/construction/list_datatables/${idCostumer}`, //para chamar o método ajax_list_reposibleWorks
			"type": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});
}


function loadConstruction(idResp) { //carrega todos os campos do modal referente a Obra escolhida
	
	clearErrors();
    loadReposibleWorks(); 

    $('#formConstruction #divId').hide();
    $('#formConstruction #divRespObra').hide();
    $('#formConstruction #divCliente').hide();
    $('#formConstruction #divNomeCliente').show();
//	$('#formReposibleWorks #divCliente').hide();
	$('#modalTitle').html('Detalhes de Obra');
	$('#btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary');
	$('#btnSaveConstruction').hide();
	$('#btnUpdate').show();
	
	$('#dtCadastro').parent().show(); //aparece a data de cadastro (só para visualizar)


	$.getJSON(`/construction/json/${idResp}`, function (data) {
                
        $("#formConstruction #IdObra").val(data.idObra);
        $("#formConstruction #codObra").val(data.codObra).prop('disabled', true);
		$("#formConstruction #respObra").val(data.id_fk_respObra).prop('disabled', true);
		$("#formConstruction #complemento").val(data.complemento).prop('disabled', true);
		$("#formConstruction #cidade").val(data.cidade).prop('disabled', true);
        $("#formConstruction #bairro").val(data.bairro).prop('disabled', true);
        $("#formConstruction #numero").val(data.numero).prop('disabled', true);
		$("#formConstruction #uf").val(data.uf).prop('disabled', true);
		$("#formConstruction #cep").val(data.cep).prop('disabled', true);
		$("#formConstruction #endereco").val(data.endereco).prop('disabled', true);
		$("#formConstruction #cliente").val(data.nome).prop('disabled', true);
		dtCadastro = formatDate(data.dtCadastro)
		//console.log('data: ' + dtCadastro)
		$("#formConstruction #dtCadastro").val(dtCadastro);
        $("#formConstruction #id_fk_cliente").val(data.id_fk_cliente);
	
		$('#btnUpdate').click(function(){ //se eu quiser atualizar o Cliente atual

			$('#formConstruction #divId').hide();
			$('#modalTitle').html('Editar Obra');
			$('#btnClose').html('Cancelar').removeClass('btn-primary').addClass('btn-danger');
			$('#btnSaveConstruction').val('Atualizar').show();
            $('#btnUpdate').hide();
                   

        $("#formConstruction #codObra").prop('disabled', true);  
		$("#formConstruction #respObra").prop('disabled', false);
		$("#formConstruction #complemento").prop('disabled', false);
        $("#formConstruction #cidade").prop('disabled', false);
        $("#formConstruction #bairro").prop('disabled', false);
		$("#formConstruction #numero").prop('disabled', false);
		$("#formConstruction #uf").prop('disabled', false);
        $("#formConstruction #cep").prop('disabled', false);
        $("#formConstruction #endereco").prop('disabled', false);
        $("#formConstruction #cliente").prop('disabled', true);
        $("#formConstruction #dtCadastro").prop('disabled', true);	
		}); 
			
		
	}).then(() => { 

		$("#constructionModal").modal();
	}).fail(function () {
		console.log("Rota não encontrada!");
	});

}

function showsNextNumber(){
    console.log('showsNextNumber')
    var parameters = window.location.href.split('/');
    idCostumer = parameters[parameters.length -1];

    $.getJSON(`/loadCodObra/${idCostumer}`, function (data) {
       console.log(data)
        $("#formConstruction #codObra").val(data);      

    });
}

function loadReposibleWorks(){

    var parameters = window.location.href.split('/');
    idCostumer = parameters[parameters.length -1];

    $.getJSON(`/loadReposibleWorks/${idCostumer}`, function (data) {


        var optionsObra = "<option value=''>Escolher...</option>";

        data.forEach(function(item){
        
            optionsObra += `<option value =${item.idResp}>${item.respObra}</option>`;
        });
       
        $("#respObra").html(optionsObra);

    })
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

function limparCampos(){

    loadReposibleWorks(); 
    
    $('#IdObra').val(0);
    //$('#codObra').val();
    $('#respObra').val('');
    console.log('respObra', $('#respObra').val())
	$('#complemento').val('');
	$('#cidade').val('');
	$('#bairro').val('');
	$('#numero').val('');
	$('#uf').val('');
	$('#cep').val('');
	$('#endereco').val('');
    $('#dtCadastro').val('');
    $('#id_fk_cliente').val(0);
    

	$('#divId').hide();
	$('#formConstruction #divCodigo').hide();
    $('#formConstruction #divCliente').hide();
    $('#formConstruction #divNomeCliente').hide();
    $('#formConstruction #divRespObra').hide();
	$('#modalTitle').html('Cadastrar Nova Obra');
	$('#btnClose').html('Fechar').removeClass('btn-danger').addClass('btn-secondary');
	$('#btnSaveConstruction').val('Cadastrar').show();
	$('#btnUpdate').hide();

    $("#formConstruction #respObra").prop('disabled', false);
	$("#formConstruction #complemento").prop('disabled', false);
	$("#formConstruction #cidade").prop('disabled', false);
	$("#formConstruction #bairro").prop('disabled', false);
	$("#formConstruction #numero").prop('disabled', false);
	$("#formConstruction #uf").prop('disabled', false);
	$("#formConstruction #cep").prop('disabled', false);
	$("#formConstruction #endereco").prop('disabled', false);
	$("#formConstruction #dtCadastro").parent().hide();


	//...	
}


function deleteReposible(idObra){

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
				url: `/construction/${idObra}/delete`,
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

						loadTableConstruction();					
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
