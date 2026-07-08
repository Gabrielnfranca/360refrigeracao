<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(403);
    echo "Requisicao invalida.";
    exit;
}

$recipient = "roberto@360refrigeracao.com.br";
$formType  = isset($_POST["form_type"]) ? trim($_POST["form_type"]) : "contact";

/* ===================== NEWSLETTER ===================== */
if ($formType === "newsletter") {
    $newsletterEmail = isset($_POST["newsletter_email"]) ? filter_var(trim($_POST["newsletter_email"]), FILTER_SANITIZE_EMAIL) : "";

    if (!filter_var($newsletterEmail, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Informe um e-mail valido.";
        exit;
    }

    $subject = "Nova inscricao na Newsletter - 360 Refrigeracao";
    $emailContent = "Novo e-mail cadastrado na newsletter:\n\n";
    $emailContent .= "E-mail: " . $newsletterEmail . "\n";

    $emailHeaders  = "MIME-Version: 1.0\r\n";
    $emailHeaders .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $emailHeaders .= "From: 360 Refrigeracao <" . $recipient . ">\r\n";
    $emailHeaders .= "Reply-To: " . $newsletterEmail . "\r\n";

    if (mail($recipient, $subject, $emailContent, $emailHeaders)) {
        http_response_code(200);
        echo "Inscricao enviada com sucesso.";
    } else {
        http_response_code(500);
        echo "Nao foi possivel enviar. Tente novamente.";
    }

    exit;
}

$name    = isset($_POST["name"])    ? strip_tags(trim($_POST["name"]))    : "";
$name    = str_replace(array("\r", "\n"), array(" ", " "), $name);
$email   = isset($_POST["email"])   ? filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL) : "";
$message = isset($_POST["message"]) ? trim($_POST["message"]) : "";

if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo "Preencha todos os campos corretamente.";
    exit;
}

$subject      = "Novo contato do site - " . $name;
$emailContent = "Nome: " . $name . "\n";
$emailContent .= "E-mail: " . $email . "\n\n";
$emailContent .= "Mensagem:\n" . $message . "\n";

$emailHeaders  = "MIME-Version: 1.0\r\n";
$emailHeaders .= "Content-Type: text/plain; charset=UTF-8\r\n";
$emailHeaders .= "From: " . $name . " <" . $email . ">\r\n";
$emailHeaders .= "Reply-To: " . $email . "\r\n";

if (mail($recipient, $subject, $emailContent, $emailHeaders)) {
    http_response_code(200);
    echo "Mensagem enviada com sucesso.";
} else {
    http_response_code(500);
    echo "Erro ao enviar mensagem.";
}

?>
