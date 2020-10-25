loadFaturasParaFazer()

function loadFaturasParaFazer() {
    $.getJSON(`/invoices`, function (data){     
        data = data.sort(compare); //ordena por data de emissão
        console.log(data)

    });
  
}

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