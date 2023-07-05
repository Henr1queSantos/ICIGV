<?php
// Verifique se o ID do membro foi fornecido
if (isset($_GET['id'])) {
    // Obtenha o ID do membro
    $id = $_GET['id'];

    // Conecte ao banco de dados
    $servername = "gracaevidaportugal.com";
    $dbname = "u335378251_icigv";
    $dbusername = "u335378251_admin";
    $dbpassword = "Icigv@2023";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // Verificar a conexão
    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Excluir o membro do banco de dados usando o ID
    $sql = "DELETE FROM membros WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Membro excluído com sucesso!');</script>";
		header("Location: admin.php"); // Redirecionar para admin.php
    } else {
        echo "<script>alert('Erro ao excluir o membro: " . $conn->error . "');</script>";
    }

    // Feche a conexão
    $conn->close();
}

// Redirecionar de volta à página de administração
header("Location: admin.php");
?>
