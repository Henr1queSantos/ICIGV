<!DOCTYPE html>
<html>
<head>
    <title>Carrossel</title>
    <!-- Estilos do Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
	<style>
    #carouselExampleControls img {
        max-height: 800;
        object-fit: contain;
    }
</style>
</head>
<body>
    <div class="container">
        <h1>Carrossel de Imagens</h1>
		
		<button onclick="window.location.href = 'editar_carrossel.php';" class="btn btn-primary">Alterar Carrossel</button>
		<button onclick="window.location.href = 'admin.php';" class="btn btn-primary">Voltar para Admin</button>


        <?php
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
            // Consulta SQL para buscar as imagens na tabela "carrusel"
            $sql = "SELECT * FROM carrusel";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Exibir o carrossel de imagens
                echo '<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">';
                echo '<div class="carousel-inner">';
                $first = true;

                while ($row = $result->fetch_assoc()) {
                    // Primeira imagem no carrossel deve ter a classe "active"
                    $activeClass = $first ? 'active' : '';
                    echo '<div class="carousel-item ' . $activeClass . ' align-items-center">';
                    echo '<img src="data:image/jpeg;base64,' . $row["imagem"] . '" class="d-block img-fluid" alt="Imagem">';
                    echo '</div>';

                    $first = false;
                }

                echo '</div>';
                echo '<a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">';
                echo '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
                echo '<span class="sr-only">Anterior</span>';
                echo '</a>';
                echo '<a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">';
                echo '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
                echo '<span class="sr-only">Próximo</span>';
                echo '</a>';
                echo '</div>';
            } else {
                echo 'Nenhuma imagem encontrada no carrossel.';
            }

            $conn->close();
        ?>
    </div>

    <!-- Scripts do Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
