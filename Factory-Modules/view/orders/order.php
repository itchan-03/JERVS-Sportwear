<?php
include('../../config/database.php');
include('../../Controller/sessioncheckup.php');

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['current_phase'])){
    $order_id = intval($_POST['order_id']);
    $new_phase = $_POST['current_phase'];

    $valid_phases = ['start', 'printing', 'heatpress', 'sewing', 'ready'];
    if(in_array($new_phase, $valid_phases)){
        $stmt = $conn->prepare('UPDATE orders_tbl SET current_phase = ?, last_updated = NOW() WHERE id = ?');
        $stmt->bind_param('si', $new_phase, $order_id);
        $stmt->execute();
        $stmt->close();

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }else {
        echo "Failed to update order.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Processing | JERVS Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/universal.css">
    <link rel="stylesheet" href="../../assets/css/order.css">
    <link rel="icon" href="../../assets/img/logo-1.png" type="image/x-icon">
</head>
<body>
<?php include('../partials/sidebar.php'); ?>

<div class="main-wrapper">
    <main class="main-content">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="header-content">
                <h1 class="dashboard-title"><i class="fas fa-cogs"></i> Order Processing</h1>
                <p class="dashboard-subtitle">Track and update production stages</p>
            </div>
            <div class="header-actions">
                <button onclick="location.reload()" class="btn btn-secondary">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="sales-section">
            <div class="section-header">
                <h2 class="section-title"><i class="fas fa-list"></i> Production Queue</h2>
            </div>
            <div class="table-responsive">
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Order name</th>
                            <th>Deposit</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $stmt = 'SELECT * FROM orders_tbl';
                            $result = $conn->query($stmt);
                        ?>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <form method="POST">
                                        <td><?= htmlspecialchars($row['item_name']) ?></td>
                                        <td>₱<?= number_format(htmlspecialchars($row['deposit']), 2) ?></td>
                                        <td>
                                            <select name="current_phase" class="form-control">
                                                <?php
                                                $options = ['start', 'printing', 'heatpress', 'sewing', 'ready'];
                                                    
                                                foreach($options as $opt):
                                                    $selected = ($row['current_phase'] === $opt) ? 'selected' : '';
                                                ?>
                                                    <option value="<?= $opt ?>" <?= $selected ?>>
                                                        <?= ucfirst(str_replace('_', ' ', $opt)) ?>
                                                    </option>
                                                <?php endforeach ?>
                                            </select>
                                        </td>
                                        <td><?= htmlspecialchars($row['last_updated']) ?></td>
                                        <td>
                                            <input type="hidden" name="order_id" value="<?= intval($row['id']) ?>"/>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Update
                                            </button>
                                        </td>
                                    </form>
                                </tr>
                            <?php endwhile ?>
                        <?php else: ?>
                            <tr><td colspan="5">No orders in production.</td></tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
</body>
</html>