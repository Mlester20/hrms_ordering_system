<?php

class ordersModel{
    
    // Get all orders with user details
    public function getAllOrders($con) {
        $query = "SELECT 
                    o.order_id,
                    o.user_id,
                    o.room_number,
                    o.total_amount,
                    o.order_status,
                    o.payment_status,
                    o.special_instructions,
                    o.ordered_at,
                    o.delivered_at,
                    u.name AS customer_name,
                    u.address,
                    u.email
                  FROM orders o
                  INNER JOIN users u ON o.user_id = u.user_id
                  ORDER BY o.ordered_at DESC";
        
        $result = $con->query($query);
        return $result;
    }
    
    // Get order items by order_id
    public function getOrderItems($con, $order_id) {
        $query = "SELECT 
                    oi.order_item_id,
                    oi.order_id,
                    oi.menu_id,
                    oi.quantity,
                    oi.price,
                    oi.subtotal,
                    oi.notes,
                    m.menu_name AS menu_name
                  FROM order_items oi
                  INNER JOIN menus m ON oi.menu_id = m.menu_id
                  WHERE oi.order_id = ?";
        
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }
    
    // Get single order with user details
    public function getOrderById($con, $order_id) {
        $query = "SELECT 
                    o.order_id,
                    o.user_id,
                    o.room_number,
                    o.total_amount,
                    o.order_status,
                    o.payment_status,
                    o.special_instructions,
                    o.ordered_at,
                    o.delivered_at,
                    u.name AS customer_name,
                    u.address,
                    u.email
                  FROM orders o
                  INNER JOIN users u ON o.user_id = u.user_id
                  WHERE o.order_id = ?";
        
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    // Get orders by status - FIXED VERSION
    public function getOrdersByStatus($con, $status) {
        $query = "SELECT 
                    o.order_id,
                    o.user_id,
                    o.room_number,
                    o.total_amount,
                    o.order_status,
                    o.payment_status,
                    o.special_instructions,
                    o.ordered_at,
                    o.delivered_at,
                    u.name AS customer_name,
                    u.address,
                    u.email
                  FROM orders o
                  INNER JOIN users u ON o.user_id = u.user_id
                  WHERE o.order_status = ?
                  ORDER BY o.ordered_at DESC";
        
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }
    
    // Update order status
    public function updateOrderStatus($con, $order_id, $new_status) {
        // If status is 'delivered', update delivered_at timestamp
        if ($new_status === 'delivered') {
            $query = "UPDATE orders 
                      SET order_status = ?, 
                          delivered_at = NOW() 
                      WHERE order_id = ?";
        } else {
            $query = "UPDATE orders 
                      SET order_status = ? 
                      WHERE order_id = ?";
        }
        
        $stmt = $con->prepare($query);
        $stmt->bind_param("si", $new_status, $order_id);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    // Update payment status
    public function updatePaymentStatus($con, $order_id, $payment_status) {
        $query = "UPDATE orders 
                  SET payment_status = ? 
                  WHERE order_id = ?";
        
        $stmt = $con->prepare($query);
        $stmt->bind_param("si", $payment_status, $order_id);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
}

?>