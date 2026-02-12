<?php

require_once '../includes/config.php';
require_once '../models/dashboardModel.php';

try{
    $dashboardModel = new dashboardModel();
    
    // Get all dashboard metrics
    $totalRevenue = $dashboardModel->getTotalRevenue($con);
    $totalOrders = $dashboardModel->getTotalOrders($con);
    $pendingOrders = $dashboardModel->getPendingOrders($con);
    $completedOrders = $dashboardModel->getCompletedOrders($con);
    $todayRevenue = $dashboardModel->getTodayRevenue($con);
    $todayOrders = $dashboardModel->getTodayOrders($con);
    
    // Get chart data
    $revenueByDay = $dashboardModel->getRevenueByDay($con, 7);
    $orderStatus = $dashboardModel->getOrderStatusDistribution($con);
    $paymentMethods = $dashboardModel->getPaymentMethodDistribution($con);
    $topItems = $dashboardModel->getTopSellingItems($con, 5);
    
    // Prepare data for charts
    $revenueDates = [];
    $revenueAmounts = [];
    while($row = $revenueByDay->fetch_assoc()){
        $revenueDates[] = date('M d', strtotime($row['order_date']));
        $revenueAmounts[] = $row['daily_revenue'];
    }

    $statusLabels = [];
    $statusCounts = [];
    while($row = $orderStatus->fetch_assoc()){
        $statusLabels[] = ucfirst($row['order_status']);
        $statusCounts[] = $row['count'];
    }

    $paymentLabels = [];
    $paymentCounts = [];
    while($row = $paymentMethods->fetch_assoc()){
        $paymentLabels[] = ucfirst($row['payment_method']);
        $paymentCounts[] = $row['count'];
    }
    
}catch(Exception $e){
    die("Error: " . $e->getMessage());
}

?>