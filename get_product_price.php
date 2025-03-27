<?php
require_once('includes/load.php');

if (isset($_GET['id'])) {
    $p_id = (int)$_GET['id'];
    $result = $db->query("SELECT price FROM products WHERE id = '{$p_id}'");
    $product = $db->fetch_assoc($result);
    echo $product ? $product['price'] : '0';
}
?>