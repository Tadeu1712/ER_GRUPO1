function verificacao(){

    // vai buscar valores ao formulario da area
    var areaInInput = document.forms["form1"]["area"].value;

    if(areaInInput==0)
    {
        alert("Escolha a area que deseja realizar o pedido");
        return false;
    }

    // vai buscar valores ao formulario da morada
    var moradaInInput = document.forms["form1"]["morada"].value;

    if(!moradaInInput)
    {
        alert("Escolha a morada onde pretende o serviço");
        return false;
    }

    
    // vai buscar valores ao formulario da data de Inicio da obra
    var dataInInput = document.forms["form1"]["dataLimiteInput"].value;
    var validacaoDataIn = new Date(dataInInput);
    
    // variavel que contem a data atual!!
    var dataAtual = new Date();
    var dataAtualM=new Date();
    //reset ao relogio da data atual!!
    dataAtual.setHours(0,0,0,0);


    // verifica se a data é valida ou se é antes da data atual 
    if ( isNaN(validacaoDataIn.getTime()) || validacaoDataIn<=dataAtual)
    {
        // avisa que a data inserida nao é valida!
        alert("Insira uma data valida, tendo em atenção que hoje é "+dataAtualM);
        return false;
    }

    // vai buscar valores ao formulario da morada
    var descricaoInInput = document.forms["form1"]["descricaoInput"].value;

    if(!descricaoInInput)
    {
        alert("Faça uma breve descrição do serviço pretendido");
        return false;
    }



}

function verificacaoP(){

    // vai buscar valores ao formulario da morada
    var moradaInInput = document.forms["form2"]["morada"].value;

    console.log(moradaInInput);
    if(!moradaInInput)
    {
        alert("Escolha a morada onde pretende o serviço");
        return false;
    }

    
    // vai buscar valores ao formulario da data de Inicio da obra
    var dataInInput = document.forms["form2"]["dataLimiteInput"].value;
    var validacaoDataIn = new Date(dataInInput);
   
    // variavel que contem a data atual!!
    var dataAtual = new Date();
    var dataAtualM=new Date();
    //reset ao relogio da data atual!!
    dataAtual.setHours(0,0,0,0);


    // verifica se a data é valida ou se é antes da data atual 
    if ( isNaN(validacaoDataIn.getTime()) || validacaoDataIn<=dataAtual)
    {
        // avisa que a data inserida nao é valida!
        alert("Insira uma data valida, tendo em atenção que hoje é "+dataAtualM);
        return false;
    }

    // vai buscar valores ao formulario da morada
    var descricaoInInput = document.forms["form2"]["descricaoInput"].value;

    if(!descricaoInInput)
    {
        alert("Faça uma breve descrição do serviço pretendido");
        return false;
    }



}

function verificacaoProp(){


    var custoProp = document.forms["formProp"]["custo"].value;

    if(!custoProp){
        alert("adicione um custo à proposta");
        return false;
    }

    // vai buscar valores ao formulario da data de Inicio da obra
    var dataInInput = document.forms["formProp"]["dataInicoInput"].value;
    var validacaoDataIn = new Date(dataInInput);
    
    // variavel que contem a data atual!!
    var dataAtual = new Date();
    var dataAtualM=new Date();
    //reset ao relogio da data atual!!
    dataAtual.setHours(0,0,0,0);


    // verifica se a data é valida ou se é antes da data atual 
    if ( isNaN(validacaoDataIn.getTime()) || validacaoDataIn<=dataAtual)
    {
        // avisa que a data inserida nao é valida!
        alert("ERRO DATA INICIO OBRA: Insira uma data valida, tendo em atenção que hoje é "+dataAtualM);
        return false;
    }

    // vai buscar valores ao formulario da data de FIM da obra
    var dataLimInput = document.forms["formProp"]["dataFIMInput"].value;
    var validacaoDataLim = new Date(dataLimInput);

    // verifica se a data e valida ou se a data limite é após a do inicio da obra ou se a data limite é antes da data atual
    if(isNaN(validacaoDataLim.getTime()) || validacaoDataLim<validacaoDataIn || validacaoDataLim<=dataAtual){
        
        // avisa que ocorre um erro na relacao das datas inseridas!
        alert("ERRO DATA FINALIZAÇÃO OBRA: Tenha em atenção que a data de fim não pode ser inferior à de inicio nem antes do dia atual");
        return false;
    }
}
