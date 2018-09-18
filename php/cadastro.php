<?php
	session_start();
	// Inclui o codigo para conexao com a database "php_tutorial" do servidor "localhost"
	//include('conexao_BD.php');

	$_SESSION = array();
	 
	// Variaveis que vieram para este arquivo atraves de HTTP POST implementado em index.php
	$usuario = filter_input(INPUT_POST,'usuario',FILTER_DEFAULT);
	$nome = filter_input(INPUT_POST,'nome',FILTER_DEFAULT);
	$email = filter_input(INPUT_POST,'email',FILTER_DEFAULT);
	$cargo = filter_input(INPUT_POST,'cargo',FILTER_DEFAULT);
	$senha = filter_input(INPUT_POST,'pass',FILTER_DEFAULT);
	$confSenha = filter_input(INPUT_POST,'confpass',FILTER_DEFAULT);
	
    $hash_senha = password_hash($senha, PASSWORD_DEFAULT);

		// Testa se o campo apelido está vazio
		if (!empty($usuario)) {

			// Testa se o campo nome está vazio
			if (!empty($email)) {

			    // Testa se as senhas batem
			    if($senha == $confSenha) {

			    	// Teste se as senhas tem no mínimo 06 caracteres
			    	if (strlen($senha) >= 6) {
			        
				        // Importa arquivo de conexao com o banco
				        include('../conexao_BD.php');
				        
				        // Query para verificar se existe algum email igual ao que se esta tentando inserir
				        $query_testUserDup = "SELECT email FROM usuarios WHERE email = '$email'";
				        if($result_testUserDup = mysqli_query($con_rdqBD,$query_testUserDup)) {
				            
				            // Retorna a quantidade de usuarios com o mesmo email na tabela USR
				            $num_userDup = mysqli_num_rows($result_testUserDup);
				            mysqli_free_result($result_testUserDup);
				            
				            if($num_userDup == 0) {
				                   	                                                
                                // Data de execucao do arquivo
                                // Depois trocar para definir timezone pelo IP
                                date_default_timezone_set('America/Sao_Paulo');
                                $dia = date("Y-m-d H:i:s");

                                    
                                $hash_senha = password_hash($senha, PASSWORD_DEFAULT);

                                // Insere Usuario na tabela USR
                                $query_insereUser = "INSERT INTO usuarios(nome, usuario, email, cargo, senha,data_cadastro) VALUES('$nome','$usuario','$email','$cargo','$hash_senha','$dia')";
                                if(mysqli_query($con_rdqBD,$query_insereUser)) {
                                    header('Location: ../index.php?sucesso=1');
                                }
                                else {
                                    // Erro para inserir usuário
                                    header('Location: ../criar_conta.php?erro=1');
                                }

				            }
				            else {
				            	header('Location: ../criar_conta.php?erro=2');
				                //$output = "Usuario ja existe";
				            }
				        }
				        else {
                            echo "query: " . $query_testUserDup;
				        	header('Location: ../criar_conta.php?erro=1');
				            //$output = "Query de selecionar email na USR falhou";
				        }
				        
				        // Fecha a conexao com o banco da prosa
				        mysqli_close($con_rdqBD);
					}
				    else {
				    	header('Location: ../criar_conta.php?erro=4');
				    	//$output = "Senha deve ter no mínimo 6 caracteres";				    
					}
			    }
			    else {
			    	header('Location: ../criar_conta.php?erro=5');
			        //$output = "Senhas nao conferem";
			    }
			}
			else {
				header('Location: ../criar_conta.php?erro=6');
				//$output = "Você esqueceu de informar seu email";
			}
		}
		else {
			header('Location: ../criar_conta.php?erro=7');
			//$output = "Você esqueceu de escolher um apelido"
		}


	print(json_encode($output));
    
