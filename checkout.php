<?php
session_start();
$conn = new mysqli("localhost", "root", "", "online_store");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $shipping_address = $conn->real_escape_string($_POST['shipping_address']);
    $total_price = $_POST['total_price'];

    $conn->query("INSERT INTO orders (user_id, total_price, shipping_address) VALUES ($user_id, $total_price, '$shipping_address')");
    $order_id = $conn->insert_id;

    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $conn->query("INSERT INTO order_items (order_id, product_id, quantity) VALUES ($order_id, $product_id, $quantity)");
        $conn->query("UPDATE products SET stock = stock - $quantity WHERE id = $product_id");
    }

    echo "تم إتمام الطلب بنجاح!";
    unset($_SESSION['cart']);
}
?>

<form action="checkout.php" method="post">
    <label>عنوان الشحن:</label>
    <input type="text" name="shipping_address" required>
    <label>السعر الكلي:</label>
    <input type="text" name="total_price" required>
    <button type="submit">إتمام الطلب</button>
</form>
