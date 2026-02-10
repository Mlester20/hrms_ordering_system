<?php
require '../includes/config.php'; // your DB connection

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$action = $_GET['action'] ?? '';

try {
    // ADD MENU
    if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $menu_name   = $_POST['menu_name'];
        $category    = $_POST['category'];
        $price       = $_POST['price'];  // can be string for DECIMAL
        $description = $_POST['description'];
        $status      = $_POST['status'];

        $stmt = $con->prepare("INSERT INTO menus (menu_name, category, price, description, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $menu_name, $category, $price, $description, $status);
        $stmt->execute();

        echo "Menu added successfully!";

    }
    // EDIT MENU
    elseif ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $menu_id     = $_POST['menu_id'];
        $menu_name   = $_POST['menu_name'];
        $category    = $_POST['category'];
        $price       = $_POST['price'];
        $description = $_POST['description'];
        $status      = $_POST['status'];

        $stmt = $con->prepare("UPDATE menus SET menu_name=?, category=?, price=?, description=?, status=? WHERE menu_id=?");
        $stmt->bind_param("sssssi", $menu_name, $category, $price, $description, $status, $menu_id);
        $stmt->execute();

        echo "Menu updated successfully!";
    }
    // DELETE MENU
    elseif ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $menu_id = $_POST['menu_id'];

        $stmt = $con->prepare("DELETE FROM menus WHERE menu_id=?");
        $stmt->bind_param("i", $menu_id);
        $stmt->execute();

        echo "Menu deleted successfully!";
    }
    // GET MENU (for edit modal)
    elseif ($action === 'get' && isset($_GET['menu_id'])) {
        $menu_id = $_GET['menu_id'];

        $stmt = $con->prepare("SELECT * FROM menus WHERE menu_id=?");
        $stmt->bind_param("i", $menu_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $menu = $result->fetch_assoc();

        header('Content-Type: application/json');
        echo json_encode($menu);
    }
    else {
        echo "Invalid action!";
    }
} catch (mysqli_sql_exception $e) {
    echo "Database error: " . $e->getMessage();
} finally {
    if (isset($stmt)) $stmt->close();
    $db->closeConnection();
}
