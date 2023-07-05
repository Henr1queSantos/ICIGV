<?php
// Obter o mês atual

    // Conecta ao banco de dados MySQL
    $servername = "gracaevidaportugal.com";
    $dbname = "u335378251_icigv";
    $dbusername = "u335378251_admin";
    $dbpassword = "Icigv@2023";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
$mesAtual = date('m');

// Consultar o banco de dados para obter os aniversariantes do mês
$sql = "SELECT * FROM membros WHERE MONTH(data_nascimento) = '$mesAtual'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr>";
    echo "<th>Nome</th>";
    echo "<th>Data de Nascimento</th>";
	echo "<th>Igreja</th>";
	echo "<th>Foto</th>";
    echo "</tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["nome"] . "</td>";
        echo "<td>" . $row["data_nascimento"] . "</td>";
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
} else {
    echo "Nenhum aniversariante neste mês.";
}

$conn->close();
?>
