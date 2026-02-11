<?php
// Error handling for JSON responses
error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();

require_once '../includes/config.php';
require_once '../models/ordersModel.php';

$ordersModel = new ordersModel();

// Handle AJAX requests for updating order status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    ob_start();
    
    try {
        header('Content-Type: application/json');
        
        if ($_POST['action'] === 'updateStatus') {
            $order_id = intval($_POST['order_id']);
            $new_status = $_POST['status'];
            
            $valid_statuses = ['pending', 'preparing', 'ready', 'delivered', 'cancelled'];
            if (!in_array($new_status, $valid_statuses)) {
                throw new Exception('Invalid status');
            }
            
            $result = $ordersModel->updateOrderStatus($con, $order_id, $new_status);
            
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
        
        ob_clean();
        echo json_encode([
            'success' => false,
            'message' => 'Unknown action'
        ]);
        exit;
        
    } catch (Exception $e) {
        ob_clean();
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
        exit;
    }
}

// Get filter status from URL parameter, default to 'pending'
$filterStatus = isset($_GET['status']) ? $_GET['status'] : 'pending';
$valid_statuses = ['all', 'pending', 'preparing', 'ready', 'delivered', 'cancelled'];

// Validate status
if (!in_array($filterStatus, $valid_statuses)) {
    $filterStatus = 'pending';
}

// Get orders based on filter
try {
    if ($filterStatus === 'all') {
        $orders = $ordersModel->getAllOrders($con);
    } else {
        $orders = $ordersModel->getOrdersByStatus($con, $filterStatus);
    }
    
    // Get counts for each status
    $statusCounts = [];
    $allStatuses = ['pending', 'preparing', 'ready', 'delivered', 'cancelled'];
    foreach ($allStatuses as $status) {
        $result = $ordersModel->getOrdersByStatus($con, $status);
        $statusCounts[$status] = $result ? $result->num_rows : 0;
    }
    $allOrders = $ordersModel->getAllOrders($con);
    $statusCounts['all'] = $allOrders ? $allOrders->num_rows : 0;
    
} catch (Exception $e) {
    error_log('Error fetching orders: ' . $e->getMessage());
    $orders = null;
    $statusCounts = ['all' => 0, 'pending' => 0, 'preparing' => 0, 'ready' => 0, 'delivered' => 0, 'cancelled' => 0];
}

?>