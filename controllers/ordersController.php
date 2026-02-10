<?php
// Error handling for JSON responses
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors to browser

session_start();

require_once '../includes/config.php';
require_once '../models/ordersModel.php';

$ordersModel = new ordersModel();

// Handle AJAX requests for updating order status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    // Start output buffering to catch any errors
    ob_start();
    
    try {
        header('Content-Type: application/json');
        
        if ($_POST['action'] === 'updateStatus') {
            $order_id = intval($_POST['order_id']);
            $new_status = $_POST['status'];
            
            // Validate status
            $valid_statuses = ['pending', 'preparing', 'ready', 'delivered', 'cancelled'];
            if (!in_array($new_status, $valid_statuses)) {
                throw new Exception('Invalid status');
            }
            
            $result = $ordersModel->updateOrderStatus($con, $order_id, $new_status);
            
            // Clean output buffer
            ob_clean();
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Order status updated successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to update order status'
                ]);
            }
            exit;
        }
        
        if ($_POST['action'] === 'getOrderDetails') {
            $order_id = intval($_POST['order_id']);
            
            if ($order_id <= 0) {
                throw new Exception('Invalid order ID');
            }
            
            $order = $ordersModel->getOrderById($con, $order_id);
            $items = $ordersModel->getOrderItems($con, $order_id);
            
            $orderItems = [];
            if ($items) {
                while ($item = $items->fetch_assoc()) {
                    $orderItems[] = $item;
                }
            }
            
            // Clean output buffer
            ob_clean();
            
            if ($order) {
                echo json_encode([
                    'success' => true,
                    'order' => $order,
                    'items' => $orderItems
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Order not found'
                ]);
            }
            exit;
        }
        
        // Unknown action
        ob_clean();
        echo json_encode([
            'success' => false,
            'message' => 'Unknown action'
        ]);
        exit;
        
    } catch (Exception $e) {
        // Clean output buffer and return error
        ob_clean();
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
        exit;
    }
}

// Get all orders for display
try {
    $orders = $ordersModel->getAllOrders($con);
} catch (Exception $e) {
    // Log error but don't break the page
    error_log('Error fetching orders: ' . $e->getMessage());
    $orders = null;
}

?>