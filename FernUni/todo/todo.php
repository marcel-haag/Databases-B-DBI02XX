<?php
    // Exercise 2
    try {
        require(__DIR__ . '/inc/db.php');
    } catch (Throwable $e) {
        echo "This was caught: " . $e->getMessage();
    }

    session_start();
    if (!isset($_SESSION['Rechte']))
      header('Location: login.php');
    if ($_SESSION['Rechte'] != 1)
      header('Location: login.php');
?>

<!doctype html>
<html>
  <head><title>TO-DO WebApp</title></head>
   <body>
     <h2>Hallo <?php echo $_SESSION['Benutzer'];?>, Willkommen!</h2>
   </body>
</html>
