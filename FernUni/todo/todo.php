<?php
    // Exercise 2
    try {
        require(__DIR__ . '/inc/db.php');
    } catch (Throwable $e) {
        echo "This was caught: " . $e->getMessage();
    }

    $db_host = "localhost";
    $db_name = "fern_uni";
    $db_user = "admin";
    $db_pass = "geheim";

    // Exercise 3
    session_start();
    if (!isset($_SESSION['Rechte']))
      header('Location: login.php');
    if ($_SESSION['Rechte'] != 1)
      header('Location: login.php');

    // Exervise 4
    $todos = [];

    try {
      $con = new PDO ('mysql:host='.$db_host.';dbname='.$db_name,$db_user,$db_pass);

      $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Todo's abfragen
      $sql = "SELECT * FROM todo_table WHERE UserId = :userId";
      $stmt = $con->prepare($sql);
      $stmt->bindParam(':userId', $_SESSION['BenutzerId']);
      $stmt->execute();
      $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(Throwable $e) {
      echo "Connection failed: " . $e->getMessage();
    }



    // Exercise 5
    // Todo hinzufügen
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['todo_hinzufügen'])) {
        $neues_todo = $_POST['frmToDo'];
        // Datumschema
        $currentDate = date("Y-m-d");

        // Todo in Datenbank speichern
        $sql = "INSERT INTO todo_table (UserId, Datum, todo) VALUES (:user_id, :date, :text)";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':user_id', $_SESSION['BenutzerId']);
        $stmt->bindParam(':date', $currentDate);
        $stmt->bindParam(':text', $neues_todo);
        $stmt->execute();

        // Todo's abfragen
        $sql = "SELECT * FROM todo_table WHERE UserId = :userId";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':userId', $_SESSION['BenutzerId']);
        $stmt->execute();
        $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Todo löschen
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['todo_löschen'])) {
      $todoIdToDelete = $_POST['todo_löschen'];

      // Todo aus Datenbank löschen
      $sql = "DELETE FROM todo_table WHERE id = :id";
      $stmt = $con->prepare($sql);
      $stmt->bindParam(':id', $todoIdToDelete);
      $stmt->execute();

      // Todo's abfragen
      $sql = "SELECT * FROM todo_table WHERE UserId = :userId";
      $stmt = $con->prepare($sql);
      $stmt->bindParam(':userId', $_SESSION['BenutzerId']);
      $stmt->execute();
      $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Exercise 6
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['todos_löschen'])) {
      // SQL-Abfrage zum Löschen aller TODOs des aktuellen Benutzers
      $stmt = $con->prepare("DELETE FROM todo_table WHERE UserId = :user_id");
      $stmt->bindParam(':user_id', $_SESSION['BenutzerId']);
      $stmt->execute();

      // Todo's abfragen
      $sql = "SELECT * FROM todo_table WHERE UserId = :userId";
      $stmt = $con->prepare($sql);
      $stmt->bindParam(':userId', $_SESSION['BenutzerId']);
      $stmt->execute();
      $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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
?>

<!doctype html>
<html>
   <head>
      <title>TO-DO WebApp</title>
   </head>
   <body>
      <h2>Hallo <?php echo $_SESSION['Benutzer'];?>, Willkommen!</h2>
      <!-- <?php echo $_SESSION['BenutzerId'];?> -->

      <!-- Exercise 4 -->
      <!-- Todo anlegen -->
      <form action="todo.php" method="post">
        <label for="frmToDo">Neues To-Do: </label>
        <input id="frmToDo" name="frmToDo" type="text"/>

        <input type="submit" name="todo_hinzufügen" value="Hinzufügen"/>
      </form>

      <br/>

      <!-- Exercise 5 -->
      <!-- Todo's anzeigen -->
      <?php if (count($todos) > 0): ?>
        <h3>ToDo's:</h3>

        <?php foreach ($todos as $todo): ?>
        <div class="todo-item">
            <form method="post" style="display:inline;">
                <input type="submit" value="✗">
                <input type="hidden" name="todo_löschen" value="<?php echo $todo['id']; ?>">
            </form>
            <?php echo $todo['todo']; ?> (Erstelldatum: <?php echo $todo['Datum']; ?>)
        </div>
        <?php endforeach; ?>

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
