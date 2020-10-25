var myTable = null;

$(function() {
    loadTableFaturasParaFazer()
});

function loadTableFaturasParaFazer() {

    if(myTable != null){
		myTable.destroy(); //desfaz as paginações
    }
    
    myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR, //tradução
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
        "serverSide": false,
        "ajax": {
            'url': '/invoices/json/pending',
            'type': 'GET',
            dataSrc: function (data) {
                console.log(data)
                rows = [];
                cont = 0;

                data.forEach(item => {
                    console.log(item)
                    row = []

                    row['codContrato'] = `<a href='/contracts/${item.idContrato}' target='_blank'>${item.codContrato}</a>`
                    row['dtEmissao'] = `<b>${formatDateToShow(item.dtEmissao)}</b>`
                    row['dtVenc'] = formatDateToShow(item.dtVenc)
                    row['dtInicio'] = formatDateToShow(item.dtInicio)
                    row['dtFim'] = formatDateToShow(item.dtFim)
                    
                    row['opcoes'] = `<button type='button' title='ver detalhes' class='btn btn-success'
					onclick='loadFreight(${item.idContrato});'>
                        <i class="fas fa-file-invoice-dollar fa-lg"></i>
					</button>`

                    rows.push(row)
                });

                return rows;
            },
        },
        "columns": [
            { "data": 'codContrato' },
            { "data": 'dtEmissao' },
            { "data": 'dtVenc' },
            { "data": 'dtInicio' },
            { "data": 'dtFim' },
            { "data": 'opcoes' },
        ],
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
    });  
}

/*
$.getJSON(`/invoices`, function (data){     
    data = data.sort(compare); //ordena por data de emissão
    console.log(data)

    table = document.querySelector('table tbody')
    
    row = document.createTextNode(`
        <tr>
            <td>Tiger Nixon</td>
            <td>System Architect</td>
            <td>Edinburgh</td>
            <td>61</td>
            <td>2011/04/25</td>
            <td>$320,800</td>
        </tr>
    `);

    table.appendChild(row)

});*/


function compare(a, b) { //condições para ordenar
    if (a.dtEmissao < b.dtEmissao) { //a is less than b by some ordering criterion
      return -1;
    }
    if (a.dtEmissao > b.dtEmissao) { //a is greater than b by the ordering criterion
      return 1;
    }
    // a must be equal to b
    return 0;
}