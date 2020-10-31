/* Formulário de Fatura */

let idContract;
let fatura;
let fatura_itens;

$(function() { 
    idContract = $('#idContrato').val();
    loadDataNewInvoice(idContract);
});

function loadDataNewInvoice(idContract=false){ //carrega dados no formulário para a nova fatura
    
    if(idContract){
        $.getJSON(`/invoices/contract/${idContract}/create`, function(data) {
            console.log(data)

            loadFormInvoice(false, data);
        });

    }else{
        console.log(`idContract=${idContract}`)
    }
}

function loadFormInvoice(idInvoice, dataNewInvoice=false) {
    fatura = dataNewInvoice.fatura;
    fatura_itens = dataNewInvoice.fatura_itens;

    if(dataNewInvoice) { //carrega campos para cadastrar nova fatura

        $('#dtInicio').val(fatura.dtInicio);
        $('#dtFim').val(fatura.dtFim);
        $('#dtEmissao').val(fatura.dtEmissao);
        $('#dtVencimento').val(fatura.dtVenc);

    }else{ //carrega campos de uma fatura existente
        
    }
}