<?php
session_start();

require_once '../controllers/dashboardData.php';
require_once '../middleware/authMiddleware.php';
requireAdmin();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Dashboard | <?php require '../includes/title.php';?></title>
    <link rel="stylesheet" href="../dist/output.css">
    <link rel="stylesheet" href="../css/app.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <?php require '../components/admin_header.php';?>
    
    <!-- dashboard components -->
    <?php require_once '../components/dashboardCard.php';?>


    <script>
        window.dashboardData = {
            revenueDates: <?php echo json_encode($revenueDates); ?>,
            revenueAmounts: <?php echo json_encode($revenueAmounts); ?>,
            statusLabels: <?php echo json_encode($statusLabels); ?>,
            statusCounts: <?php echo json_encode($statusCounts); ?>
        };
    </script>
    <script src="../js/dashboard.js"></script>
</body>
</html>