<!DOCTYPE html>
<html>
<head>
    <title>Página de Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-size: cover;
            background-position: center;
        }

        .container {
            max-width: 400px;
            margin-top: 150px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .error-message {
            color: #ff0000;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Página de Login</h1>

        <?php
        // Verifica se o formulário de login foi enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verifica as credenciais de login
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Conecta ao banco de dados (substitua os valores pelos seus próprios)
            $servername = "gracaevidaportugal.com";
            $database = "u335378251_icigv";
            $username_db = "u335378251_admin";
            $password_db = "Icigv@2023";
			

            $conn = mysqli_connect($servername, $username_db, $password_db, $database);

            // Verifica se a conexão foi estabelecida com sucesso
            if (!$conn) {
                die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
            }

            // Consulta o banco de dados para verificar as credenciais
            $query = "SELECT * FROM usuarios WHERE user='$username' AND senha='$password'";
            $result = mysqli_query($conn, $query);

            // Verifica se a consulta retornou resultados
            if (mysqli_num_rows($result) === 1) {
                // Iniciar sessão
                session_start();

                // Armazenar informações do usuário na sessão
                $_SESSION['username'] = $username;

                // Redirecionar para a página admin.php
                header('Location: admin.php');
                exit();
            } else {
                $error = "Credenciais inválidas. Tente novamente.";
            }

            // Fecha a conexão com o banco de dados
            mysqli_close($conn);
        }
        ?>

        <form method="POST" action="login.php">
            <?php if (isset($error)) { ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php } ?>

            <div class="form-group">
                <label for="username">Usuário:</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password">Senha:</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
        </form>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
