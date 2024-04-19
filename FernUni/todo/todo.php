<?php
    // Exercise 2
    try {
        require(__DIR__ . '/inc/db.php');
    } catch (Throwable $e) {
        echo "This was caught: " . $e->getMessage();
    }

    // Exercise 3
    session_start();
    if (!isset($_SESSION['Rechte']))
      header('Location: login.php');
    if ($_SESSION['Rechte'] != 1)
      header('Location: login.php');


    // Exercise 4
    $todos = [];

    try {
      $db_host = "localhost";
      $db_name = "fern_uni";
      $db_user = "admin";
      $db_pass = "geheim";

      $con = new PDO ('mysql:host='.$db_host.';dbname='.$db_name,$db_user,$db_pass);

      $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Datenbankabfrage vorbereiten
      $sql = "SELECT * FROM todo_table WHERE userId = :userId";
      $stmt = $con->prepare($sql);
      $stmt->bindParam(':userId', $_SESSION['BenutzerId']);
      $stmt->execute();
      $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(Throwable $e) {
      echo "Connection failed: " . $e->getMessage();
    }

    // Benutzereingabe auslesen nach POST event
    $frmToDo=$_POST['frmToDo'];

    echo $frmToDo;

    // Exercise 6
    function logout() {
      // Session-Variablen löschen
      session_unset();

      // Session zerstören
      session_destroy();

      // Weiterleitung zur Login-Seite
      header("Location: login.php");
    }

    if (isset($_POST['logout'])) {
      logout();
    }

    if (isset($_POST['todos_löschen'])) {
      echo "ToDo: Alle TO-DOs löschen";
    }
?>

<!doctype html>
<html>
  <head><title>TO-DO WebApp</title></head>
   <body>
      <h2>Hallo <?php echo $_SESSION['Benutzer'];?>, Willkommen!</h2>
      <!-- <?php echo $_SESSION['BenutzerId'];?> -->

      <!-- Exercise 4 -->
      <!-- Todo anlegen -->
      <form action="todo.php" method="post">
        <label for="frmToDo">Neues To-Do: </label>
        <input id="frmToDo" name="frmToDo" type="text"/>

        <input type="submit" name="speichern" value="Speichern"/>
      </form>

      <br/>

      <!-- Todo's anlegen -->
      <?php if (count($todos) > 0): ?>
        <h3>Deine TODOs:</h3>
        <ul>
          <?php foreach ($todos as $todo): ?>
            <li>
              <?php echo $todo['todo']; ?> (Erstellt am: <?php echo $todo['datum']; ?>)
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p>Keine Einträge</p>
      <?php endif; ?>

      <br/>

      <!-- Exercise 6 -->
      <form action="todo.php" method="post">
        <input type="submit" name="todos_löschen" value="Alle TO-DOs löschen"/>
        <input type="submit" name="logout" value="Logout"/>
      </form>

   </body>
</html>
