<?php

    session_start();

    if(!isset($_SESSION['email'])) {
        header('location: index.php');
    }
    
    $sessionEmail = $_SESSION['email'];

    $sucesso = isset($_GET['sucesso']) ? $_GET['sucesso'] : 0;
    $erro = isset($_GET['erro']) ? $_GET['erro'] : 0;
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$nome = $email = $cargo = $perfil = "";
$nome_err = $email_err = $cargo_err = $perfil_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_nome = trim($_POST["nome"]);
    if(empty($input_nome)){
        $nome_err = "Por favor informe um nome.";
    } elseif(!filter_var($input_nome, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $nome_err = "Por favor insira um nome válido.";
    } else{
        $nome = $input_nome;
    }
    
    // Validate email
    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = "Por favor informe um email.";     
    } else{
        $email = $input_email;
    }
    
    // Validate cargo
    $input_cargo = trim($_POST["cargo"]);
    if(empty($input_cargo)){
        $cargo_err = "Por favor informe um cargo";     
    } else{
        $cargo = $input_cargo;
    }
    
    // Validate perfil
    $input_perfil = trim($_POST["perfil"]);
    if(empty($input_perfil)){
        $perfil_err = "Por favor informe um perfil";     
    } else{
        $perfil = $input_perfil;
    }
    
    // Check input errors before inserting in database
    if(empty($nome_err) && empty($email_err) && empty($cargo_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO usuarios (nome, email, cargo, perfil, data_cadastro, ultima_atualizacao) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_nome, $param_email, $param_cargo, $param_perfil, $data_cadastro, $ultima_atualizacao);
            
            // Set parameters
            $param_nome = $nome;
            $param_email = $email;
            $param_cargo = $cargo;
            $param_perfil = $perfil;
            
            // Data de execucao do arquivo
            // Depois trocar para definir timezone pelo IP
            date_default_timezone_set('America/Sao_Paulo');
            $data_cadastro = date("Y-m-d H:i:s");
            $ultima_atualizacao = date("Y-m-d H:i:s");
                        
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: dashboard.php");
                exit();
            } else{
                echo "Algo deu errado, tente novamente mais tarde.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Cadastrar Usuário</h2>
                    </div>
                    <p>Preencha o formulário abaixo para cadastrar um novo usuário.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($nome_err)) ? 'has-error' : ''; ?>">
                            <label>Nome</label>
                            <input type="text" name="nome" class="form-control" value="<?php echo $nome; ?>">
                            <span class="help-block"><?php echo $nome_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                            <label>Email</label>
                            <textarea name="email" class="form-control"><?php echo $email; ?></textarea>
                            <span class="help-block"><?php echo $email_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($cargo_err)) ? 'has-error' : ''; ?>">
                            <label>Cargo</label>
                            <input type="text" name="cargo" class="form-control" value="<?php echo $cargo; ?>">
                            <span class="help-block"><?php echo $cargo_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($perfil_err)) ? 'has-error' : ''; ?>">
                            <label>Perfil</label>
                            <input type="text" name="perfil" class="form-control" value="<?php echo $perfil; ?>">
                            <span class="help-block"><?php echo $perfil_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="dashboard.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>