<?php
session_start();
$conn = new mysqli("localhost", "root", "", "online_store");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]++;
        } else {
            $_SESSION['cart'][$product_id] = 1;
        }
    }

    if (isset($_POST['update_quantity']) && isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id] = $quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    if (isset($_POST['remove_product_id'])) {
        $product_id = $_POST['remove_product_id'];
        unset($_SESSION['cart'][$product_id]);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['get_cart'])) {
    $cart_items = [];
    $total_items = 0;

    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $result = $conn->query("SELECT * FROM products WHERE id=$product_id");
        if ($product = $result->fetch_assoc()) {
            $product['quantity'] = $quantity;
            $cart_items[] = $product;
            $total_items += $quantity;
        }
    }

    echo json_encode([
        'items' => $cart_items,
        'total_items' => $total_items
    ]);
    exit;
}

$conn->close();
?>
