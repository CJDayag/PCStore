<?php
// Database connection
$host = 'localhost';
$dbname = 'pcstore1';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$orderId = $_GET['id'];
$updateSuccess = false;

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $newStatus = $_POST['order_status'] ?? '';
        
        $updateStmt = $pdo->prepare("UPDATE orders SET datedel = :status WHERE oid = :oid");
        $updateResult = $updateStmt->execute([
            'status' => $newStatus,
            'oid' => $orderId
        ]);
        
        if ($updateResult) {
            $updateSuccess = true;
        }
    } catch(PDOException $e) {
        die("Error updating order status: " . $e->getMessage());
    }
}

try {
    // Fetch specific order details
    $stmt = $pdo->prepare("
        SELECT o.oid, o.aid, o.dateod, o.total, o.datedel, 
               a.afname, a.alname, a.phone, a.email, 
               a.username, a.gender
        FROM orders o
        LEFT JOIN accounts a ON o.aid = a.aid
        WHERE o.oid = :oid
    ");
    $stmt->execute(['oid' => $orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch order items with product details
    $itemsStmt = $pdo->prepare("
        SELECT 
            p.pname AS name, 
            od.qty, 
            p.price
        FROM order_details od
        JOIN products p ON od.pid = p.pid
        WHERE od.oid = :oid
    ");
    $itemsStmt->execute(['oid' => $orderId]);
    $orderItems = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$order) {
        die("Order not found");
    }
} catch(PDOException $e) {
    die("Error fetching order details: " . $e->getMessage());
}

// Combine first and last name
$customerName = trim($order['afname'] . ' ' . $order['alname']);

// Define status options
$statusOptions = [
    'Order pending',
    'Order processed',
    'Order delivered',
    'Order cancelled'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="container mx-auto max-w-2xl">
        <?php if ($updateSuccess): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">Order status has been updated successfully.</span>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-6 text-center">Order Details</h1>
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Order ID</label>
                        <div class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <?php echo htmlspecialchars($order['oid']); ?>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Order Date</label>
                        <div class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <?php echo htmlspecialchars($order['dateod']); ?>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Customer Name</label>
                        <div class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <?php echo htmlspecialchars($customerName); ?>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Order Status</label>
                        <div class="mt-1">
                            <select name="order_status" class="block w-full rounded-md border-gray-300 shadow-sm">
                                <?php foreach ($statusOptions as $status): ?>
                                    <option value="<?php echo htmlspecialchars($status); ?>" 
                                        <?php echo ($order['datedel'] === $status) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($status); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Username</label>
                        <div class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <?php echo htmlspecialchars($order['username']); ?>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Contact</label>
                        <div class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <?php echo htmlspecialchars($order['phone']); ?>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-4">Order Items</h2>
                    <div class="bg-gray-50 rounded-lg">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="py-2 px-4 text-left">Product</th>
                                    <th class="py-2 px-4 text-right">Quantity</th>
                                    <th class="py-2 px-4 text-right">Unit Price</th>
                                    <th class="py-2 px-4 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $totalItems = 0;
                                foreach ($orderItems as $item): 
                                    $subtotal = $item['qty'] * $item['price'];
                                    $totalItems += $subtotal;
                                ?>
                                    <tr class="border-b last:border-b-0">
                                        <td class="py-2 px-4"><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td class="py-2 px-4 text-right"><?php echo htmlspecialchars($item['qty']); ?></td>
                                        <td class="py-2 px-4 text-right">$<?php echo number_format($item['price'], 2); ?></td>
                                        <td class="py-2 px-4 text-right">$<?php echo number_format($subtotal, 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-100 font-bold">
                                    <td colspan="3" class="py-2 px-4 text-right">Total:</td>
                                    <td class="py-2 px-4 text-right">$<?php echo number_format($order['total'], 2); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="flex justify-between">
                    <a href="inventory.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                        Back to Orders
                    </a>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>