<?php
    include('../../config/database.php');
    include('../../Controller/sessioncheck.php');

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $stmt = $conn->prepare('SELECT * FROM orders_tbl WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        $stmt->close();

        if(!$order){
            echo '<script> alert("Order not found");</script>';
        }
    }else{
        echo '<script> alert("No order ID provided");</script>';
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details | JERVS Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../View/assets/stylesheet/dashboard.css">
    <link rel="stylesheet" href="../../View/assets/stylesheet/sidebar.css">
    <link rel="stylesheet" href="../../View/assets/stylesheet/order-management-css/order-details.css">
    <link rel="icon" href="../../View/assets/img/logo-1.png" type="image/x-icon">
</head>
<body>
<?php include('../../View/parts/sidebar.php'); ?>

<div class="main-wrapper">
    <main class="main-content">
        <div class="order-details-container">
            <!-- Dashboard Header -->
            <div class="order-header">
                <div>
                    <h1 class="order-title"><i class="fas fa-file-invoice"></i> Order #<?= htmlspecialchars($order['id']) ?></h1>
                    <p class="order-subtitle">Detailed information about this order</p>
                </div>
                <!-- Pede dito lagyan ng status yung order kung (pending, ready etc.) -->
                <!-- <span class="order-status status-pending">Pending</span> -->
            </div>

            <!-- Order Details Card -->
            <div class="metric-card">
                <?php if($order): ?>
                <div class="order-details-grid">
                    <div class="detail-group">
                        <label>Order Name</label>
                        <div class="detail-value"><?= htmlspecialchars($order['item_name']) ?></div>
                    </div>
                    
                    <div class="detail-group">
                        <label>Quantity</label>
                        <div class="detail-value"><?= htmlspecialchars($order['qty']) ?></div>
                    </div>
                    
                    <div class="detail-group">
                        <label>Initial Deposit</label>
                        <div class="detail-value price">₱<?= number_format(htmlspecialchars($order['deposit']), 2) ?></div>
                    </div>
                    
                    <div class="detail-group">
                        <label>Total Price</label>
                        <div class="detail-value price">₱<?= number_format(htmlspecialchars($order['total_price']), 2) ?></div>
                    </div>
                    
                    <?php $balance = $order['total_price'] - $order['deposit'] ?>
                    <div class="detail-group">
                        <label>Balance</label>
                        <div class="detail-value price">₱<?= number_format($balance, 2) ?></div>
                    </div>
                    
                    <div class="detail-group full-width">
                        <label>Additional Details</label>
                        <div class="detail-value"><?= htmlspecialchars($order['order_details']) ?: 'No additional details' ?></div>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <a href="../../View/pages/add-order.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Orders
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>
</body>
</html>