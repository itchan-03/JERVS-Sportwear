<?php
    include('../../config/database.php');
    include('../../Controller/sessioncheck.php');

   //for updating
   if($_SERVER['REQUEST_METHOD']=== 'POST'){
    $id = $_POST['id'];
    $item = $_POST['item'];
    $deposit = $_POST['deposit'];
    $qty = $_POST['qty'];
    $total_price = $_POST['tPrice'];
    $addInfo = $_POST['addInfo'];

    $stmt = $conn->prepare('UPDATE orders_tbl SET item_name = ?, deposit = ?, qty = ?, total_price = ?, order_details = ?, last_updated = NOW() WHERE id=?');
    $stmt->bind_param('siidsi', $item, $deposit, $qty, $total_price, $addInfo, $id);

    if($stmt->execute()){
        header('Location: ../../View/pages/add-order.php?success=1');
        exit;
    }else{
        echo '<script> alert("Failed to update the record");window.location.reload(); </script>';
    }
    $stmt->close();
}

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
        exit;
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
    <title>Edit Order | JERVS Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../View/assets/stylesheet/dashboard.css">
    <link rel="stylesheet" href="../../View/assets/stylesheet/order-management-css/edit.css">
    <link rel="stylesheet" href="../../View/assets/stylesheet/sidebar.css">
    <link rel="icon" href="../../View/assets/img/logo-1.png" type="image/x-icon">
</head>
<body>
<?php include('../../View/parts/sidebar.php'); ?>

<div class="main-wrapper">
    <main class="main-content">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="header-content">
                <h1 class="dashboard-title"><i class="fas fa-edit"></i> Edit Order</h1>
                <p class="dashboard-subtitle">Update order details</p>
            </div>
        </div>

        <!-- Edit Order Form -->
        <div class="metric-card">
            <div class="chart-header">
                <h2 class="chart-title">Order #<?= htmlspecialchars($order['id']) ?></h2>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?= htmlspecialchars($order['id']) ?>">
                
                <div class="form-group">
                    <label>Order Name:</label>
                    <input type="text" name="item" class="form-control" value="<?= htmlspecialchars($order['item_name']) ?>" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Initial Deposit</label>
                        <input type="number" name="deposit" class="form-control" value="<?= htmlspecialchars($order['deposit']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="qty" class="form-control" value="<?= htmlspecialchars($order['qty']) ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Total Price</label>
                    <input type="number" name="tPrice" class="form-control" value="<?= htmlspecialchars($order['total_price']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Additional Info</label>
                    <textarea name="addInfo" class="form-control" rows="3"><?= htmlspecialchars($order['order_details']) ?></textarea>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <a href="../../View/pages/add-order.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>
</body>
</html>