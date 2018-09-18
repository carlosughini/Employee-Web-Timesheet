<?php

	session_start();

	$email = filter_input(INPUT_POST,'email',FILTER_DEFAULT);
	$senha = filter_input(INPUT_POST,'pass',FILTER_DEFAULT);

	include('conexao_BD.php');

	// Seleciona dados do usuario
	$query_selectUser = "SELECT * FROM usuarios WHERE email = '$email'";
	if($result_selectUser = mysqli_query($con_rdqBD,$query_selectUser)) {
	    $obj_selectUser = mysqli_fetch_object($result_selectUser);
	    $email_sel = $obj_selectUser->email;
	    $senha_sel = $obj_selectUser->senha;
	    mysqli_free_result($result_selectUser);
        
	    if($email_sel != NULL) {
	        $test_senha = password_verify($senha,$senha_sel);
	        if($test_senha == TRUE) {              
                
                $_SESSION['email'] = $email;
                // Login com sucesso, redireciona para a tela principal
                header('Location: dashboard.php');

	        }
	        else {
	        	header('Location: index.php?erro=1');
	            //$output = "Senha incorreta";
	        }	
	    }
	    else {
	    	header('Location: index.php?erro=2');
	        //$output = "Email nao cadastrado";
	    }
	}
	else {
		header('Location: index.php?erro=4');
	    //$output = "Query de selecionar usuario falhou";
	}

	mysqli_close($con_rdqBD);

	exit();
	?>
