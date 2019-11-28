<?php
require_once "config.php";

if (!empty($_POST["email"])) {
  $email = addslashes($_POST["email"]);

  $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
  $stmt->bindValue(":email", $email);
  $stmt->execute();

  if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch();
    $id = $row["id"];
    $token = md5(time() . rand(0, 9999) . rand(0, 9999));

    $stmt = $pdo->prepare(
      "INSERT INTO usuarios_token (id_usuario, hash, expira_em) 
       VALUES (:id_usuario, :hash, :expira_em)"
    );

    $stmt->bindValue(":id_usuario", $id);
    $stmt->bindValue(":hash", $token);
    $stmt->bindValue(":expira_em", date("Y-m-d H:i", strtotime("+2 months")));
    $stmt->execute();

    $link = "http://localhost/projeto_esqueci_senha/redefinir.php?token=" . $token;
    $assunto = "Redefinir senha";
    $msg = "Clique no link para redefinir sua senha<br>" . $link;
    $cabecalho = "From: seusite@seuservidor.com\r\n" . 
                 "X-Mailer: PHP/" . phpversion();
    
    // mail($email, $assunto, $msg, $cabecalho);
    echo $msg;
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Esqueceu sua senha</title>
</head>

<body>
  <form method="POST">
    Email: <br>
    <input type="email" name="email"><br><br>
    <input type="submit" value="Enviar">
  </form>
</body>

</html>