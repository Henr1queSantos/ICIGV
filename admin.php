<!DOCTYPE html>
<html>
<head>
    <title>ICIGV</title>
    <!-- Estilos do Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

       <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .upload-button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        .upload-button:hover {
            background-color: #0069d9;
        }

        .uploaded-image {
            display: inline-block;
            margin-left: 10px;
        }

        .result-container {
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7);
        }

        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 600px;
            max-height: 80%;
        }

        .close {
            color: #fff;
            float: right;
            font-size: 28px;
            font-weight: bold;
            margin-right: 20px;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .container {
                margin: 20px auto;
                padding: 10px;
            }
            .form-group {
                margin-bottom: 10px;
            }
        }
		
		.hidden {
            display: none;
        }
		#formbuscanome{
			margin: 10px;
		}
    </style>
</head>
<body>
    <?php
	
	// Iniciar sessão
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['username'])) {
    // Redirecionar para a página de login
    header('Location: login.php');
    exit();
}
    // Conecta ao banco de dados MySQL
    $servername = "gracaevidaportugal.com";
    $dbname = "u335378251_icigv";
    $dbusername = "u335378251_admin";
    $dbpassword = "Icigv@2023";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // Verificando a conexão
    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

   // Processar formulário de cadastro de membro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar se foi enviada uma foto
    if ($_FILES["foto"]["name"] != "") {
        // Verificar o tamanho da foto
        if ($_FILES["foto"]["size"] > 2 * 1024 * 1024) { // Tamanho máximo de 2MB em bytes
            echo "<script>alert('O tamanho máximo da foto é de 2MB.');</script>";
        } else {
            // Obter dados do formulário
            $nome = $_POST["nome"];
            $endereco = $_POST["endereco"];
            $data_nascimento = $_POST["data_nascimento"];
            $telefone = $_POST["telefone"];
            $email = $_POST["email"];
            $data_recepcao = $_POST["data_recepcao"];
            $igreja = $_POST["igreja"];
            $foto = base64_encode(file_get_contents($_FILES["foto"]["tmp_name"]));

            // Inserir membro no banco de dados
            $sql = "INSERT INTO membros (nome, endereco, data_nascimento, telefone, email, data_recepcao, igreja, foto) VALUES ('$nome', '$endereco', '$data_nascimento', '$telefone', '$email', '$data_recepcao', '$igreja', '$foto')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Membro cadastrado com sucesso!');</script>";
            } else {
                echo "<script>alert('Erro ao cadastrar o membro: " . $conn->error . "');</script>";
            }
        }
    } else {
        // Se a foto não foi enviada, tratar como um caso válido (opcional)
        // Obter dados do formulário
        $nome = $_POST["nome"];
        $endereco = $_POST["endereco"];
        $data_nascimento = $_POST["data_nascimento"];
        $telefone = $_POST["telefone"];
        $email = $_POST["email"];
        $data_recepcao = $_POST["data_recepcao"];
        $igreja = $_POST["igreja"];

        // Inserir membro no banco de dados (sem a foto)
        $sql = "INSERT INTO membros (nome, endereco, data_nascimento, telefone, email, data_recepcao, igreja) VALUES ('$nome', '$endereco', '$data_nascimento', '$telefone', '$email', '$data_recepcao', '$igreja')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Membro cadastrado com sucesso!');</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar o membro: " . $conn->error . "');</script>";
        }
    }
}



    ?>

    <div class="container">
        <h1>Página de Administração ICIGV</h1>
		<button class="btn btn-primary" id="show-form-btn">Formulário de Cadastro</button>
		<button class="btn btn-primary" id="listar-membros-btn">Listar todos membros</button>
		<button class="btn btn-primary" id="listar-aniversariantes-btn">Listar Aniversariantes do Mês</button>
		<button class="btn btn-primary" id="alterar-carrusel-btn">Alterar Carrusel</button>
		<form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-inline" id='formbuscanome'>
			<div class="form-group">
				<label for="search">Buscar por nome:</label>
				<input type="text" name="search" id="search" class="form-control">
			</div>
			<button type="submit" class="btn btn-primary">Buscar</button>
		</form>


        <!-- Formulário de cadastro de membro -->
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" id="cadastro-form" class="hidden">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" class="form-control" >
            </div>

            <div class="form-group">
                <label for="endereco">Endereço:</label>
                <input type="text" name="endereco" id="endereco" class="form-control" >
            </div>

            <div class="form-group">
                <label for="data_nascimento">Data de Nascimento:</label>
                <input type="date" name="data_nascimento" id="data_nascimento" class="form-control" >
            </div>

            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text" name="telefone" id="telefone" class="form-control" >
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" >
            </div>

            <div class="form-group">
                <label for="data_recepcao">Data de Recepção:</label>
                <input type="date" name="data_recepcao" id="data_recepcao" class="form-control" >
            </div>

            <div class="form-group">
                <label for="igreja">Igreja:</label>
                <select name="igreja" id="igreja" class="form-control" >
                    <option value="Viseu">Viseu</option>
                    <option value="Mangualde">Mangualde</option>
                    <option value="Sátão">Sátão</option>
                    <option value="São Pedro do Sul">São Pedro do Sul</option>
					<option value="França">França</option>
					<option value="Luxemburgo">Luxemburgo</option>
					<option value="Nelas">Nelas</option>
					<option value="Sintra">Sintra</option>
					<option value="Figueira da Foz">Figueira da Foz</option>
					<option value="Outras">Outras</option>
                </select>
            </div>

            <div class="form-group">
                <label for="foto">Foto:</label>
                <input type="file" name="foto" id="foto" class="form-control-file">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Cadastrar Membro</button>
            </div>
        </form>
		

		

        <!-- Exibir animação de loading ao enviar o formulário -->
        <div id="loading" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p>Aguarde, processando...</p>
        </div>

        <!-- Listar membros -->
        <div class="result-container hidden" id="todosmembros">
            <h2>Listagem de Membros</h2>
            <?php
            // Listar todos os membros da tabela
            $sql = "SELECT * FROM membros";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr>";
                echo "<th>Nome</th>";
                echo "<th>Endereço</th>";
                echo "<th>Data de Nascimento</th>";
                echo "<th>Telefone</th>";
                echo "<th>Email</th>";
                echo "<th>Data de Recepção</th>";
                echo "<th>Igreja</th>";
                echo "<th>Foto</th>";
                echo "</tr>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["nome"] . "</td>";
                    echo "<td>" . $row["endereco"] . "</td>";
                    echo "<td>" . $row["data_nascimento"] . "</td>";
                    echo "<td>" . $row["telefone"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td>" . $row["data_recepcao"] . "</td>";
                    echo "<td>" . $row["igreja"] . "</td>";
					

                    // Exibir miniatura da foto com link para a imagem em tamanho real
                    if ($row["foto"] != "") {
                        echo "<td><img src='data:image/jpeg;base64," . $row["foto"] . "' width='50' height='50' onclick='showFullImage(\"data:image/jpeg;base64," . $row["foto"] . "\")'></td>";
                    } else {
                        echo "<td></td>";
                    }
					
					echo "<td><button class='btn btn-danger' onclick='confirmDelete(" . $row['id'] . ")'>Excluir</button></td>";
					echo "<td><button class='btn btn-primary' onclick='editMember(" . $row['id'] . ")'>Editar</button></td>";

                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "Nenhum membro cadastrado.";
            }

            $conn->close();
			

            ?>
        </div>
		
		<div id="aniversariantes-container" class="result-container hidden">
			<h2>Aniversariantes do Mês</h2>
			<!-- Aqui será exibida a lista de aniversariantes -->
		</div>

        <!-- Modal para exibir imagem em tamanho real -->
        <div id="modal" class="modal">
            <span class="close">&times;</span>
            <img class="modal-content" id="modal-image">
        </div>
<div>

				<!-- Busca de membro por nome -->

<?php
// Verificar se foi realizada uma busca por nome
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["search"])) {
    $search = $_GET["search"];
    $servername = "gracaevidaportugal.com";
    $dbname = "u335378251_icigv";
    $dbusername = "u335378251_admin";
    $dbpassword = "Icigv@2023";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    // Realizar a busca no banco de dados
    $sql = "SELECT * FROM membros WHERE nome LIKE '%$search%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<div class='result-container' id='buscapornome'>";
        echo "<h2>Resultados da busca por '$search'</h2>";
        echo "<table>";
        echo "<tr>";
        echo "<th>Nome</th>";
        echo "<th>Endereço</th>";
        echo "<th>Data de Nascimento</th>";
        echo "<th>Telefone</th>";
        echo "<th>Email</th>";
        echo "<th>Data de Recepção</th>";
        echo "<th>Igreja</th>";
        echo "<th>Foto</th>";
        echo "</tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["nome"] . "</td>";
            echo "<td>" . $row["endereco"] . "</td>";
            echo "<td>" . $row["data_nascimento"] . "</td>";
            echo "<td>" . $row["telefone"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["data_recepcao"] . "</td>";
            echo "<td>" . $row["igreja"] . "</td>";

            // Exibir miniatura da foto com link para a imagem em tamanho real
            if ($row["foto"] != "") {
                echo "<td><img src='data:image/jpeg;base64," . $row["foto"] . "' width='50' height='50' onclick='showFullImage(\"data:image/jpeg;base64," . $row["foto"] . "\")'></td>";
            } else {
                echo "<td></td>";
            }

            echo "</tr>";
        }

        echo "</table>";
        echo "</div>";
    } else {
        echo "<div class='result-container'>";
        echo "<p>Nenhum membro encontrado com o nome '$search'.</p>";
        echo "</div>";
    }
}

?>		

</div>

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
			
			function confirmDelete(id) {
				if (confirm('Tem certeza que deseja excluir este membro?')) {
				// Redirecionar para a página de exclusão com o ID do membro
				window.location.href = 'excluir_membro.php?id=' + id;
				}
			}
			
			function editMember(id) {
			// Redirecionar para a página de edição com o ID do membro
			window.location.href = 'editar_membro.php?id=' + id;
			}
			
			// Obtém referências aos elementos do DOM
            var showFormBtn = document.getElementById("show-form-btn");
            var cadastroForm = document.getElementById("cadastro-form");

            // Função para mostrar o formulário quando o botão for clicado
            function showForm() {
                cadastroForm.classList.remove("hidden");
            }

            // Associa a função ao evento de clique do botão
            showFormBtn.addEventListener("click", showForm);
			
			// Função para exibir a lista de membros ao clicar no botão "Listar todos membros"
			document.getElementById("listar-membros-btn").addEventListener("click", function() {
				// Remover a classe "hidden" da div "result-container" para exibi-la
				document.querySelector(".result-container").classList.remove("hidden");
			});

			  // Função para carregar os aniversariantes do mês
			function carregarAniversariantesDoMes() {
				document.getElementById("cadastro-form").classList.add("hidden");
				document.getElementById("todosmembros").classList.add("hidden");

				// Criar uma requisição AJAX
				var xhr = new XMLHttpRequest();
				xhr.open("GET", "listar_aniversariantes.php", true);
			
				// Definir a função a ser chamada quando a resposta for recebida
				xhr.onreadystatechange = function() {
				if (xhr.readyState === 4 && xhr.status === 200) {
					// A resposta foi recebida com sucesso
					// Adicionar o resultado à div "aniversariantes-container"
					var aniversariantesContainer = document.getElementById("aniversariantes-container");
					aniversariantesContainer.innerHTML = xhr.responseText;
					aniversariantesContainer.classList.remove("hidden");
				}
				};
			
				// Enviar a requisição
				xhr.send();
			}
			
			// Evento de clique no botão "Listar Aniversariantes do Mês"
			var listarAniversariantesBtn = document.getElementById("listar-aniversariantes-btn");
			listarAniversariantesBtn.addEventListener("click", carregarAniversariantesDoMes);
			
			function showAllMembers() {
				document.getElementById("todosmembros").classList.remove("hidden");
				document.getElementById("aniversariantes-container").classList.add("hidden");
				document.getElementById("cadastro-form").classList.add("hidden");
				document.getElementById("buscapornome").classList.add("hidden");
			}
			
			function showform() {
				document.getElementById("todosmembros").classList.add("hidden");
				document.getElementById("aniversariantes-container").classList.add("hidden");
				document.getElementById("buscapornome").classList.add("hidden");
			}

			// Event listener para o botão "Listar todos membros"
			document.getElementById("listar-membros-btn").addEventListener("click", showAllMembers);
			
			// Event listener para o botão "Formulario"
			document.getElementById("show-form-btn").addEventListener("click", showform);
			
			// Evento de clique no botão "Alterar Carrusel"
			var alterarCarruselBtn = document.getElementById("alterar-carrusel-btn");
			alterarCarruselBtn.addEventListener("click", function() {
				// Abrir a página do carrossel
				window.location.href = "carrossel.php";
			});
        </script>
    </div>
</body>
</html>
