// Funções usadas na página dashboard.php

buscarUsuarios();



function teste(id) {
    console.log("teste " + id);
    var id = document.getElementById(id);
    
    

}

function buscarUsuarios() {
    var url = "php/buscarUsuarios.php";
    var serverRequest = new XMLHttpRequest();
    serverRequest.open("POST", url, true);
    //serverRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    serverRequest.setRequestHeader("Content-type", "application/json;charset=UTF-8");
    serverRequest.send();
    serverRequest.onreadystatechange = function() {
        if(serverRequest.readyState == 4 && serverRequest.status == 200) {
            
            // Parte do script que determina o que fazer com a resposta do servidor.
            var retorno = serverRequest.responseText;
            if (retorno == "") {
                
            } else {
                
                criarTabela();
                var retorno = JSON.parse(serverRequest.responseText);
                
                var tamanho = retorno.data_cadastro.length;
                
                for (var i = 0; i < tamanho; i++) {
                    criarElementos(i);
                }
                preencher(retorno.nome,retorno.email,retorno.cargo,retorno.perfil,retorno.data_cadastro);
                
                
            }
        }
    };    
}

function criarTabela() {
    var divcol = document.getElementById('divcol');
    var divTable = document.getElementById('divtable');
    
    var table = document.createElement('table');
    table.setAttribute('class','table table-bordred table-striped');
    table.setAttribute('id','table');
    
    var thead = document.createElement('thead');
    thead.setAttribute('id','textohead');
    var thId = document.createElement('th');
    var thNome = document.createElement('th');
    var thEmail = document.createElement('th');
    var thCargo = document.createElement('th');
    var thPerfil = document.createElement('th');
    var thDataCadastro = document.createElement('th');
    var thEditar = document.createElement('th');
    var thExcluir = document.createElement('th');
    thId.innertHTML = "Id";
    thNome.innerHTML = "Nome";
    thEmail.innerHTML = "Email";
    thCargo.innerHTML = "Cargo";
    thPerfil.innerHTML = "Perfil";
    thDataCadastro.innerHTML = "Data Cadastro";
    thEditar.innerHTML = "Editar";
    thExcluir.innerHTML = "Excluir";
    thead.appendChild(thId);
    thead.appendChild(thNome);
    thead.appendChild(thEmail);
    thead.appendChild(thCargo);
    thead.appendChild(thPerfil);
    thead.appendChild(thDataCadastro);
    thead.appendChild(thEditar);
    thead.appendChild(thExcluir);
    table.appendChild(thead);
    var tbody = document.createElement('tbody');
    tbody.setAttribute('id','tablebody');
    table.appendChild(tbody);
    
    divTable.appendChild(table);
    divcol.appendChild(divTable);
    
}

function criarElementos(indice) {
    var tbody = document.getElementById('tablebody');
    
    var trow = document.createElement('tr');
    trow.setAttribute('class','tablerow');
    trow.setAttribute('id',indice);
    var tdId = document.createElement('td');
    tdId.setAttribute('class','dataid');
    var tdNome = document.createElement('td');
    tdNome.setAttribute('class','datanome');
    var tdEmail = document.createElement('td');
    tdEmail.setAttribute('class','dataemail');
    var tdCargo = document.createElement('td');
    tdCargo.setAttribute('class','datacargo');
    var tdPerfil = document.createElement('td');
    tdPerfil.setAttribute('class','dataperfil');
    var tdDataCadastro = document.createElement('td');
    tdDataCadastro.setAttribute('class','dataCadastro');
    
    var tdEditar = document.createElement('td');
    var editP = document.createElement('p');
    editP.setAttribute('data-placement','top');
    editP.setAttribute('data-toggle','tooltip');
    editP.setAttribute('title','Editar');
    var editBtn = document.createElement('button');
    editBtn.setAttribute('class','btn btn-primary btn-xs');
    editBtn.setAttribute('data-title','Editar');
    editBtn.setAttribute('data-toggle','modal');
    editBtn.setAttribute('data-target','#edit');
    var editSpan = document.createElement('span');
    editSpan.setAttribute('class','glyphicon glyphicon-pencil');
    
    editBtn.appendChild(editSpan);
    editP.appendChild(editBtn);
    tdEditar.appendChild(editP);
    
    var tdExcluir = document.createElement('td');
    var excP = document.createElement('p');
    excP.setAttribute('data-placement','top');
    excP.setAttribute('data-toggle','tooltip');
    excP.setAttribute('title','Editar');
    var excBtn = document.createElement('button');
    excBtn.setAttribute('id','delete' + indice);
    excBtn.setAttribute('class','btn btn-danger btn-xs');
    excBtn.setAttribute('data-title','Deletar');
    excBtn.setAttribute('data-toggle','modal');
    excBtn.setAttribute('data-target','#delete');
    excBtn.setAttribute('onclick','teste(this.id)');
    var excSpan = document.createElement('span');
    excSpan.setAttribute('class','glyphicon glyphicon-trash');
    
    excBtn.appendChild(excSpan);
    excP.appendChild(excBtn);
    tdExcluir.appendChild(excP);
    
    trow.appendChild(tdId);
    trow.appendChild(tdNome);
    trow.appendChild(tdEmail);
    trow.appendChild(tdCargo);
    trow.appendChild(tdPerfil);
    trow.appendChild(tdDataCadastro);
    trow.appendChild(tdEditar);
    trow.appendChild(tdExcluir);
    
    tbody.appendChild(trow);
}

// Insere os valores do objeto retornado do php nos campos 
function preencher(nome,email,cargo,perfil,data_cadastro) {
    var quantidadeInfos = document.getElementsByTagName('tr').length;
    quantidadeInfos = quantidadeInfos;
    var dataIds = document.getElementsByClassName('dataid');
    var dataNome = document.getElementsByClassName('datanome');
    var dataEmail = document.getElementsByClassName('dataemail');
    var dataCargo = document.getElementsByClassName('datacargo');
    var dataPerfil = document.getElementsByClassName('dataperfil');
    var dataCadastro = document.getElementsByClassName('dataCadastro');
    for (var i = 0; i < quantidadeInfos; i++) {
        var date = new Date(data_cadastro[i]);
        date = (date.getDate() + 1) + '/' + (date.getMonth() + 1) + '/' + date.getFullYear();
        dataIds[i].innerHTML = i + 1;
        dataNome[i].innerHTML = nome[i];
        dataEmail[i].innerHTML = email[i];
        dataCargo[i].innerHTML = cargo[i];
        dataPerfil[i].innerHTML = perfil[i];
        dataCadastro[i].innerHTML = date;
    
    }        
}