<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>test</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>
<body>
    <?php
    if (isset($_SESSION['flash_message'])) {
        echo '<div class="alert alert-success">' . $_SESSION['flash_message'] . '</div>';
        unset($_SESSION['flash_message']); // Hapus pesan flash setelah ditampilkan
    }
    
    ?>
    <!-- ... -->
</body>
</html>
