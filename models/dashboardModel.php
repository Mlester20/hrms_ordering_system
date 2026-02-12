<?php

class dashboardModel{
    
    public function getTotalRevenue($con){
        try{
            $query = "SELECT SUM(total_amount) as total_revenue FROM orders";
            $stmt = $con->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row['total_revenue'] ?? 0;
        }catch(Exception $e){
            throw new Exception("Error getting total revenue: " . $e->getMessage(), 500);
        }
    }

    public function getTotalOrders($con){
        try{
            $query = "SELECT COUNT(*) as total_orders FROM orders";
            $stmt = $con->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row['total_orders'] ?? 0;
        }catch(Exception $e){
            throw new Exception("Error getting total orders: " . $e->getMessage(), 500);
        }
    }

    public function getPendingOrders($con){
        try{
            $query = "SELECT COUNT(*) as pending_orders FROM orders WHERE order_status = 'pending'";
            $stmt = $con->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row['pending_orders'] ?? 0;
        }catch(Exception $e){
            throw new Exception("Error getting pending orders: " . $e->getMessage(), 500);
        }
    }

    public function getCompletedOrders($con){
        try{
            $query = "SELECT COUNT(*) as completed_orders FROM orders WHERE order_status = 'delivered'";
            $stmt = $con->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row['completed_orders'] ?? 0;
        }catch(Exception $e){
            throw new Exception("Error getting completed orders: " . $e->getMessage(), 500);
        }
    }

    public function getTodayRevenue($con){
        try{
            $query = "SELECT SUM(total_amount) as today_revenue FROM orders WHERE DATE(ordered_at) = CURDATE()";
            $stmt = $con->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row['today_revenue'] ?? 0;
        }catch(Exception $e){
            throw new Exception("Error getting today revenue: " . $e->getMessage(), 500);
        }
    }

    public function getTodayOrders($con){
        try{
            $query = "SELECT COUNT(*) as today_orders FROM orders WHERE DATE(ordered_at) = CURDATE()";
            $stmt = $con->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row['today_orders'] ?? 0;
        }catch(Exception $e){
            throw new Exception("Error getting today orders: " . $e->getMessage(), 500);
        }
    }

    // For Line Chart - Last 7 days revenue
    public function getRevenueByDay($con, $days = 7){
        try{
            $query = "SELECT DATE(ordered_at) as order_date, SUM(total_amount) as daily_revenue 
                      FROM orders 
                      WHERE ordered_at >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                      GROUP BY DATE(ordered_at)
                      ORDER BY order_date ASC";
            $stmt = $con->prepare($query);
            $stmt->bind_param("i", $days);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            return $result;
        }catch(Exception $e){
            throw new Exception("Error getting revenue by day: " . $e->getMessage(), 500);
        }
    }

    // For Pie Chart - Order Status Distribution
    public function getOrderStatusDistribution($con){
        try{
            $query = "SELECT order_status, COUNT(*) as count 
                      FROM orders 
                      GROUP BY order_status";
            $stmt = $con->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            return $result;
        }catch(Exception $e){
            throw new Exception("Error getting order status: " . $e->getMessage(), 500);
        }
    }

    // For Pie Chart - Payment Method Distribution
    public function getPaymentMethodDistribution($con){
        try{
            $query = "SELECT payment_method, COUNT(*) as count 
                      FROM orders 
                      GROUP BY payment_method";
            $stmt = $con->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            return $result;
        }catch(Exception $e){
            throw new Exception("Error getting payment methods: " . $e->getMessage(), 500);
        }
    }

    // Top Selling Items
    public function getTopSellingItems($con, $limit = 5){
        try{
            $query = "SELECT m.menu_name, SUM(oi.quantity) as total_sold, SUM(oi.subtotal) as total_revenue
                      FROM order_items oi
                      INNER JOIN menus m ON oi.menu_id = m.menu_id
                      GROUP BY oi.menu_id
                      ORDER BY total_sold DESC
                      LIMIT ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("i", $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            return $result;
        }catch(Exception $e){
            throw new Exception("Error getting top items: " . $e->getMessage(), 500);
        }
    }
}

?>