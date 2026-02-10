<?php

require_once '../controllers/ordersController.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | <?php require '../includes/title.php'; ?> </title>
    <link rel="stylesheet" href="../dist/output.css">
    <link rel="stylesheet" href="../css/app.css">
</head>
<body>

    <!-- header -->
    <?php require '../components/user_header.php';?>
    

    <!-- orders component card -->
    <?php require '../components/ordersCard.php';?>


    <script src="../js/orderCardModal.js"></script>
</body>
</html>