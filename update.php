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
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_nome = trim($_POST["nome"]);
    if(empty($input_nome)){
        $nome_err = "Por favor informe um nome.";
    } else{
        $nome = $input_nome;
    }
    
    // Validate address address
    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = "Por favor insira um email.";     
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
    if(empty($nome_err) && empty($email_err) && empty($cargo_err) && empty($perfil_err)){
        // Prepare an update statement
        $sql = "UPDATE usuarios SET nome=?, email=?, cargo=?, perfil=?, ultima_atualizacao=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssi", $param_nome, $param_email, $param_cargo, $param_perfil, $ultima_atualizacao, $param_id);
            
            // Set parameters
            $param_nome = $nome;
            $param_email = $email;
            $param_cargo = $cargo;
            $param_perfil = $perfil;
            $param_id = $id;
            
            // Data de execucao do arquivo
            // Depois trocar para definir timezone pelo IP
            date_default_timezone_set('America/Sao_Paulo');
            $ultima_atualizacao = date("Y-m-d H:i:s");
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
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
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $nome = $row["nome"];
                    $email = $row["email"];
                    $cargo = $row["cargo"];
                    $perfil = $row["perfil"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editar</title>
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
                        <h2>Atualizar Usuário</h2>
                    </div>
                    <p>Por favor edite as informações e clique em enviar para atualizar.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($nome_err)) ? 'has-error' : ''; ?>">
                            <label>Nome</label>
                            <input type="text" name="nome" class="form-control" value="<?php echo $nome; ?>">
                            <span class="help-block"><?php echo $nome_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                            <label>Email</label>
                            <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
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
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="dashboard.php" class="btn btn-default">Cancelar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>