<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("الوصول مرفوض! هذه الصفحة مخصصة للمشرفين فقط.");
}

$conn = new mysqli("localhost", "root", "", "online_store");

// إدارة المنتجات
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'add_product') {
        $name = $conn->real_escape_string($_POST['name']);
        $description = $conn->real_escape_string($_POST['description']);
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $image = $conn->real_escape_string($_POST['image']);

        $conn->query("INSERT INTO products (name, description, price, stock, image) VALUES ('$name', '$description', $price, $stock, '$image')");
    } elseif ($_POST['action'] == 'delete_product' && isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        $conn->query("DELETE FROM products WHERE id=$product_id");
    }
}

$products = $conn->query("SELECT * FROM products");
$users = $conn->query("SELECT * FROM users");
$orders = $conn->query("SELECT * FROM orders");
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم الآدمن</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5 mb-5">
        <h1 class="text-center mb-4">لوحة تحكم الآدمن</h1>

        <ul class="nav nav-tabs" id="adminTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="products-tab" data-toggle="tab" href="#products" role="tab">إدارة المنتجات</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="users-tab" data-toggle="tab" href="#users" role="tab">إدارة المستخدمين</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="orders-tab" data-toggle="tab" href="#orders" role="tab">إدارة الطلبات</a>
            </li>
        </ul>

        <div class="tab-content mt-4">
            <!-- إدارة المنتجات -->
            <div class="tab-pane fade show active" id="products" role="tabpanel">
                <h3>إضافة منتج جديد</h3>
                <form method="POST" class="mb-4">
                    <input type="hidden" name="action" value="add_product">
                    <div class="form-group">
                        <label>اسم المنتج:</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>الوصف:</label>
                        <input type="text" name="description" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>السعر:</label>
                        <input type="number" name="price" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>الكمية المتاحة:</label>
                        <input type="number" name="stock" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>رابط الصورة:</label>
                        <input type="text" name="image" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">إضافة منتج</button>
                </form>

                <h3>قائمة المنتجات</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>اسم المنتج</th>
                            <th>الوصف</th>
                            <th>السعر</th>
                            <th>الكمية المتاحة</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($product = $products->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($product['name']) ?></td>
                                <td><?= htmlspecialchars($product['description']) ?></td>
                                <td><?= htmlspecialchars($product['price']) ?> دينار</td>
                                <td><?= htmlspecialchars($product['stock']) ?></td>
                                <td>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="action" value="delete_product">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- إدارة المستخدمين -->
            <div class="tab-pane fade" id="users" role="tabpanel">
                <h3>قائمة المستخدمين</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>اسم المستخدم</th>
                            <th>البريد الإلكتروني</th>
                            <th>الدور</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $users->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['role']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- إدارة الطلبات -->
            <div class="tab-pane fade" id="orders" role="tabpanel">
                <h3>قائمة الطلبات</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>رقم الطلب</th>
                            <th>رقم المستخدم</th>
                            <th>السعر الكلي</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = $orders->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($order['id']) ?></td>
                                <td><?= htmlspecialchars($order['user_id']) ?></td>
                                <td><?= htmlspecialchars($order['total_price']) ?> دينار</td>
                                <td><?= htmlspecialchars($order['status']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>