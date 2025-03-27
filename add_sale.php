
<?php /*
$page_title = 'Add Sale';
require_once('includes/load.php');

// Check user permission level
page_require_level(3);

// Debugging - Display Errors (Remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch available products for dropdown
$products = $db->query("SELECT id, name, quantity FROM products");


if (isset($_POST['add_sale'])) {
    $req_fields = array('s_id', 'quantity', 'price', 'date');
    validate_fields($req_fields);

    if (empty($errors)) {
        $p_id    = $db->escape((int)$_POST['s_id']);
        $s_qty   = $db->escape((int)$_POST['quantity']);
        $s_price = $db->escape($_POST['price']); 
        $s_date  = $db->escape($_POST['date']);

        // Fetch current stock quantity
        $stock_query = $db->query("SELECT quantity FROM products WHERE id = '{$p_id}'");
        $product = $db->fetch_assoc($stock_query);

        if ($product && $product['quantity'] >= $s_qty) {
            // Deduct sold quantity from stock
            $new_qty = $product['quantity'] - $s_qty;
            $update_stock = $db->query("UPDATE products SET quantity = '{$new_qty}' WHERE id = '{$p_id}'");

            if ($update_stock) {
                // Insert the sale record
                $sql = "INSERT INTO sales (product_id, qty, price, date) VALUES ('{$p_id}', '{$s_qty}', '{$s_price}', '{$s_date}')";
                if ($db->query($sql)) {
                    // Check if stock is running low
                    if ($new_qty < 5) { // Adjust threshold as needed
                        $session->msg('w', "Warning: Stock for this product is low ({$new_qty} remaining).");
                    } else {
                        $session->msg('s', "Sale added successfully.");
                    }
                    redirect('sales.php', false);
                } else {
                    $session->msg('d', 'Sorry, sale could not be added.');
                    redirect('add_sale.php', false);
                }
            } else {
                $session->msg('d', 'Failed to update stock.');
                redirect('add_sale.php', false);
            }
        } else {
            $session->msg('d', 'Not enough stock available.');
            redirect('add_sale.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_sale.php', false);
    }
} */
?>

<?php
$page_title = 'Add Sale';
require_once('includes/load.php');

page_require_level(3);

// Debugging - Display Errors (Remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch available products
$products = $db->query("SELECT id, name, quantity FROM products");

// Telegram bot details
define('TELEGRAM_BOT_TOKEN', '7021217782:AAGJIt-ggxMCrT0feZoNS67a-DeiIn3Orek');
define('TELEGRAM_CHAT_ID', '5589099922');

// Function to send Telegram notification
function send_telegram_message($message) {
    $url = "https://api.telegram.org/bot" . TELEGRAM_BOT_TOKEN . "/sendMessage";
    $data = ['chat_id' => TELEGRAM_CHAT_ID, 'text' => $message];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ]
    ];
    $context = stream_context_create($options);
    file_get_contents($url, false, $context);
}

if (isset($_POST['add_sale'])) {
    $req_fields = array('s_id', 'quantity', 'price', 'date');
    validate_fields($req_fields);

    if (empty($errors)) {
        $p_id    = $db->escape((int)$_POST['s_id']);
        $s_qty   = $db->escape((int)$_POST['quantity']);
        $s_price = $db->escape($_POST['price']); 
        $s_date  = $db->escape($_POST['date']);

        // Fetch current stock quantity
        $stock_query = $db->query("SELECT name, quantity FROM products WHERE id = '{$p_id}'");
        $product = $db->fetch_assoc($stock_query);

        if ($product && $product['quantity'] >= $s_qty) {
            // Deduct sold quantity from stock
            $new_qty = $product['quantity'] - $s_qty;
            $update_stock = $db->query("UPDATE products SET quantity = '{$new_qty}' WHERE id = '{$p_id}'");

            if ($update_stock) {
                // Insert the sale record
                $sql = "INSERT INTO sales (product_id, qty, price, date) VALUES ('{$p_id}', '{$s_qty}', '{$s_price}', '{$s_date}')";
                if ($db->query($sql)) {
                    
                    // Check if stock is running low
                    if ($new_qty < 30) {  
                        $low_stock_msg = "⚠️ Low Stock Alert! \n \nProduct: {$product['name']}\nRemaining Stock: {$new_qty}";
                        send_telegram_message($low_stock_msg);
                    }

                    $session->msg('s', "Sale added successfully.");
                    redirect('sales.php', false);
                } else {
                    $session->msg('d', 'Sorry, sale could not be added.');
                    redirect('add_sale.php', false);
                }
            } else {
                $session->msg('d', 'Failed to update stock.');
                redirect('add_sale.php', false);
            }
        } else {
            $session->msg('d', 'Not enough stock available.');
            redirect('add_sale.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_sale.php', false);
    }
}
?>



<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Add Sale</span>
        </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="add_sale.php">
          <table class="table table-bordered">
            <thead>
              <th> Item </th>
              <th> Price </th>
              <th> Qty </th>
              <th> Date </th>
              <th> Action </th>
            </thead>
            <tbody>
              <tr>
                <td>
                  <select name="s_id" class="form-control">
                    <option value="">Select Product</option>
                    <?php while ($product = $db->fetch_assoc($products)): ?>
                      <option value="<?php echo $product['id']; ?>">
                        <?php echo $product['name']; ?> (Stock: <?php echo $product['quantity']; ?>)
                      </option>
                    <?php endwhile; ?>
                  </select>
                </td>
                <td>
                  <input type="text" name="price" class="form-control" placeholder="Price">
                </td>
                <td>
                  <input type="number" name="quantity" class="form-control" placeholder="Quantity">
                </td>
                <td>
                  <input type="date" name="date" class="form-control">
                </td>
                <td>
                  <button type="submit" name="add_sale" class="btn btn-success">Add</button>
                </td>
              </tr>
            </tbody>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>