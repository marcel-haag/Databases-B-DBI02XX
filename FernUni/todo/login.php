<?php
    // Exercise 3
    session_start();
    $_SESSION['Benutzer']="";
    $_SESSION['Rechte']=0;
    $_SESSION['BenutzerId']="";

    // Benutzereingabe auslesen nach POST event
    $frmBenutzer=$_POST['frmBenutzer'];
    $frmKennwort=$_POST['frmKennwort'];

    try {
      $db_host = "localhost";
      $db_name = "fern_uni";
      $db_user = "admin";
      $db_pass = "geheim";

      $con = new PDO ('mysql:host='.$db_host.';dbname='.$db_name,$db_user,$db_pass);

      $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // SQL-Abfrage vorbereiten
      $sql = "SELECT * FROM user_table WHERE name = :name";
      $stmt = $con->prepare($sql);
      $stmt->execute([':name' => $frmBenutzer]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(Throwable $e) {
      echo "Connection failed: " . $e->getMessage();
    }

    // echo "username: " . $user['name'];
    // echo "userpass: " . $user['password'];

    // Überprüfen, ob ein Benutzer mit dem eingegebenen Benutzernamen gefunden wurde
    if ($user) {
      // Passwort überprüfen
      if ($frmKennwort === $user['password']) {
          // Benutzer erfolgreich authentifiziert
          // Session mit Benutzernamen erstellen
          $_SESSION['Benutzer']=$frmBenutzer;
          $_SESSION['Rechte']=1;
          $_SESSION['BenutzerId']=$user['id'];
          // Weiterleiten zu einer anderen Seite oder Erfolgsmeldung anzeigen
          header('Location: todo.php');
      } else {
          // Falsches Passwort
          // echo "Falsches Passwort";
      }
    } else {
      // Benutzer nicht gefunden
      // echo "Benutzer nicht gefunden";
    }
?>

<!doctype html>
<html>
<head><title>TO-DO WebApp Login</title></head>
<body>
    <form action="login.php" method="post">
        <label for="frmBenutzer">Benutzername:</label>
        <input id="frmBenutzer" name="frmBenutzer" type="text"/>

        <label for="frmKennwort">Password:</label>
        <input id="frmKennwort" name="frmKennwort" type="password"/>

        <input type="submit" value="Login" />
    </form>

     <p style="text-align:center;">
       Sie haben die Session-ID <?php echo session_id();?>
       vom Server erhalten.
     </p>
   </body>
</html>
