<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Europe/Lisbon');

// Dados do formulário
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$codigo = $_POST['codigo'] ?? '';
$numero = $_POST['numero'] ?? '';
$data_envio = date('Y-m-d H:i:s');

// Configurações do banco
$servername = "gracaevidaportugal.com";
$dbname = "u335378251_icigv";
$dbusername = "u335378251_admin";
$dbpassword = "Icigv@2023";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Tratamento do anexo
$anexo_nome = $_FILES['anexo']['name'] ?? '';
$anexo_tipo = $_FILES['anexo']['type'] ?? '';
$anexo_temp = $_FILES['anexo']['tmp_name'] ?? '';
$anexo_dados = !empty($anexo_temp) ? file_get_contents($anexo_temp) : null;

// Salva no banco
$stmt = $conn->prepare("INSERT INTO inscricoes_evento (nome, email, codigo, numero, anexo, tipo_anexo, data_envio) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $nome, $email, $codigo, $numero, $anexo_dados, $anexo_tipo, $data_envio);
$stmt->send_long_data(4, $anexo_dados);
$stmt->execute();
$stmt->close();
$conn->close();

// Função para configurar PHPMailer
function configurarMailer() {
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'contato@gracaevidaportugal.com';
    $mail->Password = 'Icigv@2023';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    return $mail;
}

try {
    /* --- Envio para o ORGANIZADOR --- */
    $mail = configurarMailer();
    $mail->setFrom('contato@gracaevidaportugal.com', 'Formulário do Site');
    $mail->addAddress('contato@gracaevidaportugal.com'); 
    $mail->addReplyTo($email, $nome);

    if (is_uploaded_file($anexo_temp)) {
        $mail->addAttachment($anexo_temp, $anexo_nome);
    }

    $assuntoOrg = "Nova inscrição de {$nome} no evento Fé e Inteligência Emocional na Prática";

    $corpoOrg = "
    <html><body style='font-family: Arial, sans-serif;'>
        <h3>Nova inscrição recebida</h3>
        <p><strong>Nome:</strong> {$nome}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Contacto:</strong> +{$codigo} {$numero}</p>
        <hr>
        <p><strong>Dados para pagamento:</strong></p>
        <p>💳 <strong>IBAN:</strong> PT50 0033 0000 4541 4890 0720 5<br>
        📱 <strong>MBWAY:</strong> 967 620 688</p>
        <br>
        <p>Este email foi enviado automaticamente pelo formulário do site.</p>
    </body></html>";

    $mail->isHTML(true);
    $mail->Subject = $assuntoOrg;
    $mail->Body = $corpoOrg;
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = 'html';
    $mail->send();

    /* --- Envio para o PARTICIPANTE --- */
    $mail_participante = configurarMailer();
    $mail_participante->setFrom('contato@gracaevidaportugal.com', 'Graça e Vida Portugal');
    $mail_participante->addAddress($email, $nome);

    if (!empty($anexo_temp) && file_exists($anexo_temp)) {
    $mail->addAttachment($anexo_temp, $anexo_nome);
    }

    $assuntoPart = "Confirmação da sua inscrição no evento Fé e Inteligência Emocional na Prática";

    $corpoPart = "
    <html><body style='font-family: Arial, sans-serif; color:#333;'>
        <h2>Olá, {$nome}!</h2>
        <p>Recebemos a sua inscrição no evento <strong>Fé e Inteligência Emocional na Prática</strong>.</p>
        <p>Muito obrigado pela sua inscrição!<br>
        <strong>Graça e Vida Portugal</strong></p>
    </body></html>";

    $mail_participante->isHTML(true);
    $mail_participante->Subject = $assuntoPart;
    $mail_participante->Body = $corpoPart;
    $mail_participante->send();

    echo "<script>alert('Inscrição enviada com sucesso! Um email de confirmação foi enviado.'); window.location.href='index.html';</script>";

} catch (Exception $e) {
    echo "<script>alert('Erro ao enviar a inscrição. Por favor, tente novamente.'); window.history.back();</script>";
}
?>
