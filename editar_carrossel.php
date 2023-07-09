<!DOCTYPE html>
<html>
<head>
    <title>Editar Carrossel</title>
    <!-- Estilos do Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Editar Carrossel</h1>
		<button onclick="window.location.href = 'carrossel.php';" class="btn btn-primary">Voltar para Carrossel</button>
		<button onclick="window.location.href = 'admin.php';" class="btn btn-primary">Voltar para Admin</button>
		
		<form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="imagem">Imagem:</label>
                <input type="file" name="imagem" id="imagem" required>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar Imagem</button>
        </form>

        <?php
        // Conecta ao banco de dados MySQL
        $servername = "gracaevidaportugal.com";
        $dbname = "u335378251_icigv";
        $dbusername = "u335378251_admin";
        $dbpassword = "Icigv@2023";

        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

        // Processar formulário de upload de imagem
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["imagem"])) {
            $imagem = $_FILES["imagem"];
            $imagem_temp = $_FILES["imagem"]["tmp_name"];
            $imagem_name = $_FILES["imagem"]["name"];

            // Verifica se um arquivo de imagem foi selecionado
            if (!empty($imagem_temp)) {
                // Lê o conteúdo do arquivo de imagem
                $imagem_data = base64_encode(file_get_contents($imagem_temp));
                // Escapa caracteres especiais para evitar injeção de SQL
                $imagem_data = $conn->real_escape_string($imagem_data);

                // Insere a imagem na tabela "carrusel"
                $sql = "INSERT INTO carrusel (imagem) VALUES ('$imagem_data')";

                if ($conn->query($sql) === TRUE) {
                    echo "<p class='text-success'>Imagem adicionada com sucesso ao carrossel.</p>";
                } else {
                    echo "<p class='text-danger'>Erro ao adicionar a imagem ao carrossel: " . $conn->error . "</p>";
                }
            }
        }

        // Consulta SQL para buscar as imagens na tabela "carrusel"
        $sql = "SELECT * FROM carrusel";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<h2>Imagens no Carrossel:</h2>";
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li>";
                echo '<img src="data:image/jpeg;base64,' . $row["imagem"] . '" width="200" height="150">';
                echo '<form method="POST" style="display: inline-block;">';
                echo '<input type="hidden" name="imagem_id" value="' . $row["id"] . '">';
                echo '<button type="submit" name="remover_imagem" class="btn btn-danger">Remover</button>';
                echo '</form>';
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo 'Nenhuma imagem encontrada no carrossel.';
        }
        ?>
		
		<?php
    // Processar formulário de remover imagem
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remover_imagem"])) {
        $imagem_id = $_POST["imagem_id"];

        // Remover imagem da tabela "carrusel"
        $sql = "DELETE FROM carrusel WHERE id = '$imagem_id'";

        if ($conn->query($sql) === TRUE) {
            echo "<p class='text-success'>Imagem removida com sucesso do carrossel.</p>";
        } else {
            echo "<p class='text-danger'>Erro ao remover a imagem do carrossel: " . $conn->error . "</p>";
        }
    }
?>


    </div>

    <!-- Scripts do Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
