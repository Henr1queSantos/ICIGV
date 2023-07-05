<?php
// Verifique se o formulário de edição foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Obtenha os dados do formulário de edição
    $id = $_POST["id"];
    $nome = $_POST["nome"];
    $endereco = $_POST["endereco"];
    $data_nascimento = $_POST["data_nascimento"];
    $telefone = $_POST["telefone"];
    $email = $_POST["email"];
    $data_recepcao = $_POST["data_recepcao"];
    $igreja = $_POST["igreja"];
    $foto = $_POST["foto"];

    // Verificar se foi enviada uma nova foto
    if ($_FILES["foto"]["name"] != "") {
        // Verificar o tamanho da foto em bytes
        $tamanhoFoto = $_FILES["foto"]["size"];
        $tamanhoMaximo = 2 * 1024 * 1024; // 2 MB

        if ($tamanhoFoto > $tamanhoMaximo) {
            echo "<script>alert('A foto deve ter no máximo 2 MB.');</script>";
            echo "<script>window.location.href = 'editar_membro.php?id=$id';</script>";
            exit();
        }

        $foto = base64_encode(file_get_contents($_FILES["foto"]["tmp_name"]));
    } else {
        $foto = null;
    }

    // Atualizar os detalhes do membro no banco de dados
    $sql = "UPDATE membros SET nome = '$nome', endereco = '$endereco', data_nascimento = '$data_nascimento', telefone = '$telefone', email = '$email', data_recepcao = '$data_recepcao', igreja = '$igreja', foto = '$foto' WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Membro atualizado com sucesso!');</script>";
        header("Location: admin.php"); // Redirecionar para admin.php
        exit();
    } else {
        echo "<script>alert('Erro ao atualizar o membro: " . $conn->error . "');</script>";
    }

    $conn->close();
}
?>




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