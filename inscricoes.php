<?php
session_start();
if (!isset($_SESSION['username'])) {
    // Salva a página atual para redirecionar após login
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit();
}

$servername = "gracaevidaportugal.com";
$dbname = "u335378251_icigv";
$dbusername = "u335378251_admin";
$dbpassword = "Icigv@2023";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

$sql = "SELECT * FROM inscricoes_evento ORDER BY data_envio DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Inscrições - Fé e Inteligência Emocional</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<style>
body { background-color: #f8f9fa; }
.container {
    margin-top: 40px;
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
</style>
</head>
<body>
<div class="container">
    <h2>Inscrições Recebidas</h2>
    <a href="admin.php" class="btn btn-secondary mb-3">⬅ Voltar ao Painel</a>

    <?php
    if ($result->num_rows > 0) {
        echo "<table class='table table-bordered table-striped'>";
        echo "<thead class='thead-dark'>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Código</th>
                    <th>Número</th>
                    <th>Anexo</th>
                    <th>Data de Envio</th>
                </tr>
              </thead>
              <tbody>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['nome']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['codigo']}</td>
                    <td>{$row['numero']}</td>";

            if (!empty($row['anexo'])) {
    $fileData = base64_encode($row['anexo']);
    $mime = !empty($row['tipo_anexo']) ? $row['tipo_anexo'] : 'application/octet-stream';
    
    // Detecta a extensão automaticamente com base no tipo MIME
    $ext = '';
    if (strpos($mime, 'pdf') !== false) {
        $ext = 'pdf';
    } elseif (strpos($mime, 'jpeg') !== false || strpos($mime, 'jpg') !== false) {
        $ext = 'jpg';
    } elseif (strpos($mime, 'png') !== false) {
        $ext = 'png';
    }

    echo "<td><a download='anexo_{$row['id']}.{$ext}' href='data:{$mime};base64,{$fileData}'>📎 Baixar</a></td>";
} else {
    echo "<td>—</td>";
}


            echo "<td>{$row['data_envio']}</td>
                </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p>Nenhuma inscrição encontrada.</p>";
    }

    $conn->close();
    ?>
</div>
</body>
</html>
