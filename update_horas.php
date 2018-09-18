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
$nome = $data = $hora_entrada = $hora_saida = "";
$nome_err = $data_err = $hora_entrada_err = $hora_saida_err = "";
 
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
    
    // Validate email
    $input_data = trim($_POST["data"]);
    if(empty($input_data)){
        $data_err = "Por favor informe uma data.";     
    } else{
        $data = $input_data;
    }
    
    // Validate hora_entrada
    $input_hora_entrada = trim($_POST["hora_entrada"]);
    if(empty($input_hora_entrada)){
        $hora_entrada_err = "Por favor informe uma hora de entrada";     
    } else{
        $hora_entrada = $input_hora_entrada;
    }
    
    // Validate hora_saida
    $input_hora_saida = trim($_POST["hora_saida"]);
    if(empty($input_hora_saida)){
        $hora_saida_err = "Por favor informe uma hora de saída";     
    } else{
        $hora_saida = $input_hora_saida;
    }
    
    // Check input errors before inserting in database
    if(empty($nome_err) && empty($data_err) && empty($hora_entrada_err) && empty($hora_saida_err)){
        // Prepare an update statement
        $sql = "UPDATE horas SET nome=?, data=?, hora_entrada=?, hora_saida=?, total_horas=?, ultima_atualizacao=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssi", $param_nome, $param_data, $param_hora_entrada, $param_hora_saida, $total_horas, $ultima_atualizacao, $param_id);
            
            // Set parameters
            $param_nome = $nome;
            $param_data = $data;
            $param_hora_entrada = $hora_entrada;
            $param_hora_saida = $hora_saida;
            $param_id = $id;
            
            $a = new DateTime($hora_entrada);
            $b = new DateTime($hora_saida);
            $interval = $a->diff($b);
                        
            $diferenca = $interval->format('%H:%I');
            
            $total_horas = $diferenca;
            
            // Data de execucao do arquivo
            // Depois trocar para definir timezone pelo IP
            date_default_timezone_set('America/Sao_Paulo');
            $ultima_atualizacao = date("Y-m-d H:i:s");
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: horas.php");
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
        $sql = "SELECT * FROM horas WHERE id = ?";
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
                    $email = $row["data"];
                    $cargo = $row["hora_entrada"];
                    $perfil = $row["hora_saida"];
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="carlos ughini">
    <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
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
                        <div class="form-group <?php echo (!empty($data_err)) ? 'has-error' : ''; ?>">
                            <label>Data</label>
                            <input id="data" type="date" name="data" class="form-control" value="<?php echo $hora_entrada; ?>">
                            <span class="help-block"><?php echo $data_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($hora_entrada_err)) ? 'has-error' : ''; ?>">
                            <label>Hora Entrada</label>
                            <input id="hora_entrada" type="time" name="hora_entrada" class="form-control" value="<?php echo $hora_entrada; ?>">
                            <span class="help-block"><?php echo $hora_entrada_err;?></span>
                        </div>
                        
                        
                        <div class="form-group <?php echo (!empty($hora_saida_err)) ? 'has-error' : ''; ?>">
                            <label>Hora Saída</label>
                            <input id="hora_saida" type="time" name="hora_saida" class="form-control form_datetime" value="<?php echo $hora_saida; ?>">
                            <span class="help-block"><?php echo $hora_saida_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="horas.php" class="btn btn-default">Cancelar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>