<?php
session_start();

require_once '../includes/config.php'; 
require_once '../models/menusModel.php';
require_once '../includes/flashMessages.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$menu = new menusModel();
$menus = $menu->getMenus($con);

// Function to handle image upload
function handleImageUpload($file, $oldImage = null) {
    $uploadDir = "../uploads/";
    
    // Create uploads directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Check if file was uploaded
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return $oldImage; // Return old image if no new file uploaded
    }
    
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Upload failed with error code " . $file['error']);
    }
    
    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $fileType = $file['type'];
    
    if (!in_array($fileType, $allowedTypes)) {
        throw new Exception("Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed.");
    }
    
    // Validate file size (5MB max)
    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $maxSize) {
        throw new Exception("File size exceeds 5MB limit.");
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'menu_' . uniqid() . '_' . time() . '.' . $extension;
    $targetPath = $uploadDir . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        // Delete old image if exists
        if ($oldImage && file_exists($uploadDir . $oldImage)) {
            unlink($uploadDir . $oldImage);
        }
        return $filename;
    } else {
        throw new Exception("Failed to move uploaded file.");
    }
}

// Check if there's an action parameter
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Handle GET request for fetching single menu (for edit modal)
    if ($action === 'get' && isset($_GET['menu_id'])) {
        header('Content-Type: application/json');
        $menu_id = $_GET['menu_id'];
        $menuData = $menu->getMenuById($con, $menu_id);
        echo json_encode($menuData);
        exit();
    }

    // Handle POST requests
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        if ($action === 'create') {
            try {
                $menu_name = $_POST['menu_name'];
                $category = $_POST['category'];
                $price = $_POST['price'];
                $description = $_POST['description'];
                $status = $_POST['status'];
                
                // Handle image upload
                $product_image = '';
                if (isset($_FILES['product_image'])) {
                    $product_image = handleImageUpload($_FILES['product_image']);
                }

                if ($menu->addMenus($con, $menu_name, $category, $price, $product_image, $description, $status)) {
                    setFlash("success", "Menu Added Successfully!");
                } else {
                    setFlash("error", "Error Adding Menu");
                }
            } catch (Exception $e) {
                setFlash("error", "Error: " . $e->getMessage());
            }
            header("Location: ../admin/menus.php");
            exit();
        }

        if ($action === 'edit') {
            try {
                $menu_id = $_POST['menu_id'];
                $menu_name = $_POST['menu_name'];
                $category = $_POST['category'];
                $price = $_POST['price'];
                $description = $_POST['description'];
                $status = $_POST['status'];
                
                // Get current menu data
                $currentMenu = $menu->getMenuById($con, $menu_id);
                $product_image = $currentMenu['product_image'];
                
                // Handle image upload
                if (isset($_FILES['product_image'])) {
                    $product_image = handleImageUpload($_FILES['product_image'], $currentMenu['product_image']);
                }

                if ($menu->editMenus($con, $menu_id, $menu_name, $category, $price, $product_image, $description, $status)) {
                    setFlash("success", "Menu Updated Successfully!");
                } else {
                    setFlash("error", "Error Updating Menu");
                }
            } catch (Exception $e) {
                setFlash("error", "Error: " . $e->getMessage());
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