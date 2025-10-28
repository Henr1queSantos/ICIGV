<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';

$servername = "gracaevidaportugal.com";
$dbname = "u335378251_icigv";
$dbusername = "u335378251_admin";
$dbpassword = "Icigv@2023";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão com o banco: " . $conn->connect_error);
}

// Dados do formulário
$nome = $_POST['nome'];
$email = $_POST['email'];
$codigo = $_POST['codigo'];
$numero = $_POST['numero'];
$data_envio = date('Y-m-d H:i:s');

// Processar o anexo
$anexo = null;
$tipo_anexo = null;

if (isset($_FILES['anexo']) && $_FILES['anexo']['error'] === UPLOAD_ERR_OK) {
    $anexo = file_get_contents($_FILES['anexo']['tmp_name']);
    $tipo_anexo = mime_content_type($_FILES['anexo']['tmp_name']);
}

// Inserir no banco
$stmt = $conn->prepare("INSERT INTO inscricoes_evento (nome, email, codigo, numero, anexo, tipo_anexo, data_envio) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssbss", $nome, $email, $codigo, $numero, $anexo, $tipo_anexo, $data_envio);

// Corrige binding binário
$stmt->send_long_data(4, $anexo);
$stmt->execute();
$stmt->close();

// Envio de email
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'contato@gracaevidaportugal.com';
    $mail->Password = 'Icigv@2023'; 
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    $mail->setFrom('contato@gracaevidaportugal.com', 'Formulário do Site');
    $mail->addAddress('samiapvhro@hotmail.com'); // destino

    $mail->isHTML(true);
    $mail->Subject = "Nova Inscrição de $nome no evento Fé e Inteligência Emocional na Prática";
    $mail->Body = "
        <h3>Nova inscrição recebida</h3>
        <p><strong>Nome:</strong> {$nome}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Contacto:</strong> +{$codigo} {$numero}</p>
    ";

    if ($anexo) {
        $mail->addStringAttachment($anexo, $_FILES['anexo']['name'], 'base64', $tipo_anexo);
    }

    $mail->send();
    echo "<script>alert('Formulário enviado com sucesso!'); window.location.href='Evento.html';</script>";
} catch (Exception $e) {
    echo "Erro ao enviar o email: {$mail->ErrorInfo}";
}

$conn->close();
?>
