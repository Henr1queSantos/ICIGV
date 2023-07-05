<?php
// Verifique se o ID do membro foi fornecido na URL
if (isset($_GET["id"])) {
    // Obtenha o ID do membro da URL
    $id = $_GET["id"];

    // Conecte-se ao banco de dados
    $servername = "gracaevidaportugal.com";
    $dbname = "u335378251_icigv";
    $dbusername = "u335378251_admin";
    $dbpassword = "Icigv@2023";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // Verificar a conexão
    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Consulta SQL para obter os detalhes do membro com base no ID
    $sql = "SELECT * FROM membros WHERE id = $id";
    $result = $conn->query($sql);

    // Verifique se o membro existe no banco de dados
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Extrair os detalhes do membro do resultado da consulta
        $nome = $row["nome"];
        $endereco = $row["endereco"];
        $data_nascimento = $row["data_nascimento"];
        $telefone = $row["telefone"];
        $email = $row["email"];
        $data_recepcao = $row["data_recepcao"];
        $igreja = $row["igreja"];
        $foto = $row["foto"];
        ?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Membro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Membro</h1>

        <form action="atualizar_membro.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" class="form-control" name="nome" value="<?php echo $nome; ?>">
            </div>

            <div class="form-group">
                <label for="endereco">Endereço:</label>
                <input type="text" class="form-control" name="endereco" value="<?php echo $endereco; ?>">
            </div>

            <div class="form-group">
                <label for="data_nascimento">Data de Nascimento:</label>
                <input type="date" class="form-control" name="data_nascimento" value="<?php echo $data_nascimento; ?>">
            </div>

            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text" class="form-control" name="telefone" value="<?php echo $telefone; ?>">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" value="<?php echo $email; ?>">
            </div>

            <div class="form-group">
                <label for="data_recepcao">Data de Recepção:</label>
                <input type="date" class="form-control" name="data_recepcao" value="<?php echo $data_recepcao; ?>">
            </div>

            <div class="form-group">
                <label for="igreja">Igreja:</label>
                <input type="text" class="form-control" name="igreja" value="<?php echo $igreja; ?>">
            </div>

            <div class="form-group">
                <label for="foto">Foto:</label>
                <input type="file" name="foto" id="foto" class="form-control-file" value="<?php echo $foto; ?>">
            </div>

            <button type="submit" class="btn btn-primary">Atualizar</button>
        </form>
		
		        <!-- Scripts do Bootstrap -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

        <script>
            // Exibir nome do arquivo selecionado para upload
            var fileInput = document.getElementById("foto");
            var uploadedImage = document.getElementById("uploaded-image");

            fileInput.addEventListener("change", function(event) {
                var file = event.target.files[0];
                var reader = new FileReader();

                reader.onload = function(e) {
                    var image = new Image();
                    image.src = e.target.result;
                    image.onload = function() {
                        var canvas = document.createElement("canvas");
                        var context = canvas.getContext("2d");

                        // Redimensionar a imagem para 100x100 pixels
                        var width = 100;
                        var height = 100;
                        context.drawImage(image, 0, 0, width, height);

                        // Obter a imagem em base64
                        var resizedImage = canvas.toDataURL("image/jpeg");

                        // Exibir a miniatura da imagem
                        uploadedImage.innerHTML = "<img src='" + resizedImage + "' width='50' height='50'>";
                    };
                };

                reader.readAsDataURL(file);
            });

            // Exibir imagem em tamanho real ao clicar na miniatura
            function showFullImage(imageUrl) {
                var modal = document.getElementById("modal");
                var modalImage = document.getElementById("modal-image");

                modal.style.display = "block";
                modalImage.src = imageUrl;
            }

            // Fechar o modal ao clicar no botão de fechar
            var closeButton = document.getElementsByClassName("close")[0];

            closeButton.onclick = function() {
                var modal = document.getElementById("modal");
                modal.style.display = "none";
            }

            // Exibir animação de loading ao enviar o formulário
            var form = document.getElementById("cadastro-form");
            var loading = document.getElementById("loading");

            form.addEventListener("submit", function() {
                loading.style.display = "block";
            });
        </script>
    </div>
</body>
</html>

        <?php
    } else {
        echo "O membro com o ID fornecido não foi encontrado.";
    }

    // Feche a conexão com o banco de dados
    $conn->close();
} else {
    echo "ID do membro não fornecido na URL.";
}
?>
