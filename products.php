<?php
session_start();
$conn = new mysqli("localhost", "root", "", "online_store");

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title>المنتجات</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        #cart-details {
            display: none;
            position: fixed;
            bottom: 80px;
            right: 10px;
            max-height: 400px;
            width: 350px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            overflow-y: auto;
            z-index: 100;
        }

        #cart-info {
            position: fixed;
            bottom: 10px;
            right: 10px;
            z-index: 9999;
        }

        #cart-info button {
            border-radius: 8px;
            padding: 10px 20px;
        }

        #cart-items li {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #cart-items li button {
            font-size: 12px;
            padding: 5px 10px;
        }

        #cart-items li input {
            width: 60px;
            margin-right: 10px;
        }

        .modal-backdrop.show {
            opacity: 0.3 !important;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="my-4">المنتجات المتاحة</h1>
        <div class="row">
            <?php while ($product = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <p class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($product['description']) ?></p>
                            <p class="card-text"><?= htmlspecialchars($product['price']) ?> دينار</p>
                            <form action="cart.php" method="post">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <a href="javascript:void(0);" onclick="addToCart(<?= $product['id'] ?>)" class="btn btn-primary">إضافة إلى السلة</a>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- زر السلة الثابت في أسفل الصفحة -->
    <div id="cart-info">
        <button onclick="showCartDetails()" class="btn btn-success">
            السلة (<span id="cart-count">0</span> عنصر)
        </button>
    </div>

    <!-- تفاصيل السلة -->
    <div id="cart-details">
        <h5 class="text-center">محتويات السلة:</h5>
        <ul id="cart-items" class="list-unstyled">
            <!-- سيتم إدراج عناصر السلة هنا -->
        </ul>
        <button onclick="checkout()" class="btn btn-success btn-block">إتمام الطلب</button>
    </div>

</body>
<script>
    // دالة لإضافة منتج إلى السلة باستخدام AJAX
    function addToCart(productId) {
        fetch('cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `product_id=${productId}`
            })
            .then(response => response.text())
            .then(() => updateCartCount());
    }

    // دالة لتحديث عدد العناصر في السلة
    function updateCartCount() {
        fetch('cart.php?get_cart=1')
            .then(response => response.json())
            .then(data => {
                document.getElementById('cart-count').innerText = data.total_items;
            });
    }

    // دالة لإظهار تفاصيل السلة
    function showCartDetails() {
        const cartDetails = document.getElementById('cart-details');

        if (cartDetails.style.display === 'block') {
            cartDetails.style.display = 'none';
        } else {
            fetch('cart.php?get_cart=1')
                .then(response => response.json())
                .then(data => {
                    const cartItemsContainer = document.getElementById('cart-items');
                    cartItemsContainer.innerHTML = '';

                    data.items.forEach(item => {
                        const listItem = document.createElement('li');
                        listItem.innerHTML = `
                            <span>${item.name} - 
                                <form action="cart.php" method="post" style="display:inline;">
                                    <input type="number" name="quantity" value="${item.quantity}" min="1" class="form-control form-control-sm">
                                    <input type="hidden" name="product_id" value="${item.id}">
                                    <button type="submit" name="update_quantity" class="btn btn-warning btn-sm mt-2">تحديث</button>
                                </form>
                            </span>
                            <span>x ${item.price} دينار</span>
                            <button onclick="removeFromCart(${item.id})" class="btn btn-danger btn-sm">إزالة</button>
                        `;
                        cartItemsContainer.appendChild(listItem);
                    });

                    cartDetails.style.display = 'block';
                });
        }
    }

    // دالة لإزالة منتج من السلة
    function removeFromCart(productId) {
        fetch('cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `remove_product_id=${productId}`
            })
            .then(response => response.text())
            .then(() => {
                updateCartCount();
                showCartDetails();
            });
    }

    // دالة لإتمام الطلب
    function checkout() {
        window.location.href = 'checkout.php';
    }

    // تحديث السلة عند تحميل الصفحة
    window.onload = updateCartCount;
</script>

</html>
<?php $conn->close(); ?>