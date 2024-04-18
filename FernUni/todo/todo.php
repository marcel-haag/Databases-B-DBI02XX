<?php
    try {
        require(__DIR__ . '/inc/db.php');
    } catch (Throwable $e) {
        echo "This was caught: " . $e->getMessage();
    }
?>
