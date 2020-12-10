let myTable = null;

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