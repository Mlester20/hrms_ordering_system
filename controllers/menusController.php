<?php
session_start();

require_once '../includes/config.php'; 
require_once '../models/menusModel.php';
require_once '../includes/flashMessages.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$menu = new menusModel();
$menus = $menu->getMenus($con);

// Check if there's an action parameter
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Handle GET request for fetching single menu (for edit modal)
    if ($action === 'get' && isset($_GET['menu_id'])) {
        header('Content-Type: application/json');
        $menu_id = $_GET['menu_id'];
        // You'll need to add a getMenuById method in your model
        $menuData = $menu->getMenuById($con, $menu_id);
        echo json_encode($menuData);
        exit();
    }

    // Handle POST requests
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        if ($action === 'create') {
            $menu_name = $_POST['menu_name'];
            $category = $_POST['category'];
            $price = $_POST['price'];
            $description = $_POST['description'];
            $status = $_POST['status'];

            if ($menu->addMenus($con, $menu_name, $category, $price, $description, $status)) {
                setFlash("success", "Menu Added Successfully!");
            } else {
                setFlash("error", "Error Adding Menu");
            }
            header("Location: ../admin/menus.php");
            exit();
        }

        if ($action === 'edit') {
            $menu_id = $_POST['menu_id'];
            $menu_name = $_POST['menu_name'];
            $category = $_POST['category'];
            $price = $_POST['price'];
            $description = $_POST['description'];
            $status = $_POST['status'];

            if ($menu->editMenus($con, $menu_id, $menu_name, $category, $price, $description, $status)) {
                setFlash("success", "Menu Updated Successfully!");
            } else {
                setFlash("error", "Error Updating Menu");
            }
            header("Location: ../admin/menus.php");
            exit();
        }

        if ($action === 'delete') {
            $menu_id = $_POST['menu_id'];
            
            if ($menu->deleteMenus($con, $menu_id)) {
                setFlash("success", "Menu Deleted Successfully!");
            } else {
                setFlash("error", "Error Deleting Menu");
            }
            header("Location: ../admin/menus.php");
            exit();
        }
    }
}
?>