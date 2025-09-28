<?php
// Session must be started at the very beginning
session_start();

// Redirect to login if user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$host = "localhost";
$dbname = "shp";
$username_db = "root";
$password_db = "";

$total = 0;
$productsInCart = [];

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username_db, $password_db);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the cart is not empty before looping
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        // Prepare the SQL statement to prevent SQL injection
        $stmt = $db->prepare("SELECT * FROM products WHERE id = :id");
        
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $stmt->bindParam(':id', $product_id);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                $item_total = $quantity * $product['price'];
                $total += $item_total;
                $product['quantity'] = $quantity;
                $productsInCart[] = $product;
            }
        }
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Shopping Cart</title>
    <style>
        body {
            background-color: green;
        }
        header, nav, main, footer {
            background-color: white;
            padding: 20px;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }
        nav li {
            margin-right: 15px;
        }
        nav a {
            color: black;
            text-decoration: none;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #dddddd;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        footer {
            background-color: green;
            color: black;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <header>
        <h1><?php echo htmlspecialchars($_SESSION['username']); ?>'s Shopping Cart</h1>
    </header>

    <nav>
        <ul>
            <li><a href="shop.php">Home</a></li>
            <li><a href="shop.php">Products</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <main>
        <section>
            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
                <?php if (empty($productsInCart)): ?>
                    <tr>
                        <td colspan="4">Your cart is empty.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($productsInCart as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                            <td>$<?php echo htmlspecialchars($product['price']); ?></td>
                            <td>$<?php echo htmlspecialchars($product['quantity'] * $product['price']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3">Total:</td>
                        <td>$<?php echo htmlspecialchars($total); ?></td>
                    </tr>
                <?php endif; ?>
            </table>
            <form action="checkout.php" method="post">
                <input type="submit" value="Checkout" class="button" />
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2023 GFG Shopping Web Application</p>
    </footer>
</body>

</html>