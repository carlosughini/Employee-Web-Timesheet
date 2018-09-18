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
    if(empty($nome_err) && empty($data_err) && empty($hora_entrada_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO horas (nome, data, hora_entrada, hora_saida, total_horas, ultima_atualizacao) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_nome, $param_data, $param_hora_entrada, $param_hora_saida, $total_horas, $ultima_atualizacao);
            
            // Set parameters
            $param_nome = $nome;
            $param_data = $data;
            $param_hora_entrada = $hora_entrada;
            $param_hora_saida = $hora_saida;
            
            $a = new DateTime($hora_entrada);
            $b = new DateTime($hora_saida);
            $interval = $a->diff($b);
                        
            $diferenca = $interval->format('%H:%I');
            
            $total_horas = $diferenca;
            
            // Data de execucao do arquivo
            // Depois trocar para definir timezone pelo IP
            date_default_timezone_set('America/Sao_Paulo');
            $data_cadastro = date("Y-m-d H:i:s");
            $ultima_atualizacao = date("Y-m-d H:i:s");
                        
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
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
    <title>Horários</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
    <!-- Jquery Mask -->
        <script src="/js/jquery-3.3.1.min.js"></script>
        <script src="/js/jquery.mask.min.js"></script>
    <!-- Javascript -->
    <script type="text/javascript">
        $(document).ready(function(){
            $('#data').mask('00/00/0000');
            $('#hora_entrada').mask('00:00:00');
            $('#hora_saida').mask('00:00:00');
        });
    </script> 
    <!-- HTML5 shiv and Respond.js IE8 support of HTML5 elements and media queries-->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->  
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Cadastrar Usuário</h2>
                    </div>
                    <p>Preencha o formulário abaixo para cadastrar um novo usuário e seu horário de trabalho.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                    

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="horas.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>