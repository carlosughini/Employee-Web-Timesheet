<?php
// Instanciamento das variaveis de conexao com o servidor
	$host = '127.0.0.1';
	$uname = 'root';
	$pwd = '';
	$dbname = 'banco_horas';
	//$port = "3306";
    
	$con_rdqBD = mysqli_connect($host, $uname, $pwd, $dbname);
	
	// Checa a conexao com servidor. Caso exista erro, printa um informativo no navegador.
    if(mysqli_connect_errno()) {
        echo 'Erro na conexao com a database: ' . mysqli_connect_error();
        exit();
	}
?>