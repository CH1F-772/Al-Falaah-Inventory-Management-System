<?php
require_once('includes/load.php');
page_require_level(2);

if(isset($_POST['query'])) {
    $search = remove_junk($db->escape($_POST['query']));
    
    $sql  = "SELECT p.id, p.name, p.quantity, p.buy_price, p.sale_price, p.date, c.name AS categorie, m.file_name AS image, p.media_id ";
    $sql .= "FROM products p ";
    $sql .= "LEFT JOIN categories c ON c.id = p.categorie_id ";
    $sql .= "LEFT JOIN media m ON m.id = p.media_id ";
    $sql .= "WHERE p.name LIKE '%$search%' ";
    $sql .= "OR c.name LIKE '%$search%' ";
    $sql .= "ORDER BY p.id DESC";

    $result = $db->query($sql);

    if($db->num_rows($result) > 0) {
        while($product = $db->fetch_assoc($result)): ?>
            <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <td>
                  <?php if($product['media_id'] === '0'): ?>
                    <img class="img-avatar img-circle" src="uploads/products/no_image.png" alt="">
                  <?php else: ?>
                    <img class="img-avatar img-circle" src="uploads/products/<?php echo $product['image']; ?>" alt="">
                  <?php endif; ?>
                </td>
                <td> <?php echo remove_junk($product['name']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['categorie']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['quantity']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['buy_price']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['sale_price']); ?></td>
                <td class="text-center"> <?php echo read_date($product['date']); ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-info btn-xs" title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
            </tr>
        <?php endwhile;
    } else {
        echo "<tr><td colspan='9' class='text-center'>No products found</td></tr>";
    }
}
?>