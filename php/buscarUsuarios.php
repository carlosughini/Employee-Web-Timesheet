<?php

    /*
    * Retornos:
    * 1 - Erro geral
    */

    session_start();

    $data = json_decode(file_get_contents('php://input'));

    // Variaveis para enviar de volta para o js
    $nome = array();
    $email = array();
    $cargo = array();
    $usuario = array();
    $perfil = array();
    $data_cadastro = array();

    buscarUsuarios();

    /* ===== Funções ===== */

function buscarUsuarios() {
    // Importa arquivo de conexao com o banco
	include('../conexao_BD.php');
    global $nome;
    global $email;
    global $cargo;
    global $usuario;
    global $perfil;
    global $data_cadastro;
    
    $query = "SELECT nome, email, cargo, usuario, perfil, data_cadastro from usuarios";
    $resultado = mysqli_query($con_rdqBD,$query);
    if ($resultado) {
        while ($record = mysqli_fetch_assoc($resultado)) {
            $datas = explode(" ",$record["data_cadastro"]);
            $dia = $datas[0];
            array_push($data_cadastro,$dia);
            array_push($nome,$record["nome"]);
            array_push($email,$record["email"]);
            array_push($cargo,$record["cargo"]);
            array_push($usuario,$record["usuario"]);
            array_push($perfil,$record["perfil"]);
        }
        criarJsonObject();
    } else {
        exit("1");
    }    
}

function criarJsonObject() {
    global $nome;
    global $email;
    global $cargo;
    global $usuario;
    global $perfil;
    global $data_cadastro;
    $objeto = new \stdClass();
    $objeto->nome = $nome;
    $objeto->email = $email;
    $objeto->cargo = $cargo;
    $objeto->perfil = $perfil;
    $objeto->data_cadastro = $data_cadastro;
    exit(json_encode($objeto));
}