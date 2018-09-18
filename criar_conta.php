<?php

    $sucesso = isset($_GET['sucesso']) ? $_GET['sucesso'] : 0;
    $erro = isset($_GET['erro']) ? $_GET['erro'] : 0;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<title>Banco Horas</title>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="images/img-01.png" alt="IMG">
				</div>

				<form class="login100-form validate-form" enctype="multipart/form-data" action="php/cadastro.php" accept-charset="UTF-8" novalidate="" method="post" id="form1">
					<span class="login100-form-title">
						Criar conta
					</span>
                    


                        <div class="wrap-input100 validate-input" data-validate = "Usuario valido necessario">
                            <input class="input100" type="text" name="usuario" placeholder="Usuario">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="fa fa-user" aria-hidden="true"></i>
                            </span>
                        </div>
                    
                    

                        <div class="wrap-input100 validate-input" data-validate = "Nome valido necessario">
                            <input class="input100" type="text" name="nome" placeholder="Nome">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="fa fa-user" aria-hidden="true"></i>
                            </span>
                        </div>

                        <div class="wrap-input100 validate-input form-group" data-validate = "Email valido necessario">
                            <input class="input100" type="text" name="email" placeholder="Email">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                            </span>
                        </div>
                        
                        <div class="wrap-input100 validate-input" data-validate = "Cargo">
                            <input class="input100" type="text" name="cargo" placeholder="Cargo">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="fa fa-gavel" aria-hidden="true"></i>
                            </span>
                        </div>

                        <div class="wrap-input100 validate-input" data-validate = "Password necessario">
                            <input class="input100" type="password" name="pass" placeholder="Senha">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="fa fa-lock" aria-hidden="true"></i>
                            </span>
                        </div>
                        
                        <div class="wrap-input100 validate-input" data-validate = "Password necessario">
                            <input class="input100" type="password" name="confpass" placeholder="Confirmar senha">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="fa fa-lock" aria-hidden="true"></i>
                            </span>
                        </div>
                    </form>     
                    			
                    <div class="container-login100-form-btn">
                        <button type="submit" form="form1" value="Submit" class="login100-form-btn" name="submit">
                            Criar conta
                        </button>
                    </div>

					<div class="text-center p-t-12" style="visibility: hidden">
						<span class="txt1">
							Esqueceu
						</span>
						<a class="txt2" href="#">
							Usuário / Senha?
						</a>
					</div>

					<div class="text-center p-t-136" style="visibility: hidden">
						<a class="txt2" href="#">
							Criar conta
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
                
                <?php
                
                if($erro == 1){
                      echo '<h5 style="color:#ffffff; margin:auto; text-align:center;">Tivemos um erro ao tentar realizar o seu cadastro, tente novamente.</h5>';
                    } else if($erro == 2){
                      echo '<h5 style="color:#ffffff; margin:auto; text-align:center;">Usuário já existe.</h5>';
                    } else if($erro == 4){
                      echo '<h5 style="color:#ffffff; margin:auto; text-align:center;">Senha deve ter no mínimo 6 caracteres.<br></h5>';
                    } else if($erro == 5){
                      echo '<h4 style="color:#ffffff; margin:auto; text-align:center;">Senhas não conferem</h4>';
                    } else if($erro == 6){
                      echo '<h4 style="color:#ffffff; margin:auto; text-align:center;">Você esqueceu de informar seu e-mail.</h4>';
                    } else if($erro == 7){
                      echo '<h4 style="color:#ffffff; margin:auto; text-align:center;">Você esqueceu de escolher seu nome de usuário.</h4>';
                    } 
                  ?>
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>