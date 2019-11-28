<?php
require_once "config.php";

if (!empty($_GET["token"])) {
  $token = $_GET["token"];

  $stmt = $pdo->prepare(
    "SELECT * FROM usuarios_token WHERE hash = :hash AND usado = 0 AND expira_em > NOW()"
  );

  $stmt->bindValue(":hash", $token);
  $stmt->execute();

  if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch();
    $id = $row["id_usuario"];

    if (!empty($_POST["senha"])) {
      $senha = addslashes($_POST["senha"]);

      $stmt = $pdo->prepare("UPDATE usuarios SET senha = :senha WHERE id = :id");
      $stmt->bindValue(":senha", md5($senha));
      $stmt->bindValue(":id", $id);
      $stmt->execute();

      $stmt = $pdo->prepare("UPDATE usuarios_token SET usado = 1 WHERE hash = :hash");
      $stmt->bindValue(":hash", $token);
      $stmt->execute();

      echo "Senha alterada com sucesso";

      exit;
    }
    ?>

    <form method="POST">
      Digite a nova senha: <br>
      <input type="password" name="senha"><br><br>
      <input type="submit" value="Enviar">
    </form>

<?php
  } else {
    echo "Link inválido";
  }
}
