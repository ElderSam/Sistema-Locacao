let idContract;

$(function() { 
    txtContract = 'contract';
    idContract = getIdContactByURLParams(txtContract);
    loadDataNewInvoice(idContract);

});

function getIdContactByURLParams(param) {
    paramns = location.pathname

    arrayParam = paramns.split('/').slice(1) //pega o pathname e coloca em um array
    console.log('url', arrayParam)
    
    if(arrayParam[1] == param){
        idContract = parseInt(arrayParam[2]);
        console.log(`${param}: ${idContract}`)
        return idContract;

    }else{
        return false;
    }
}

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

    if(dataNewInvoice) { //carrega campos para cadastrar nova fatura


    }else{ //carrega campos de uma fatura existente
        
    }
}
