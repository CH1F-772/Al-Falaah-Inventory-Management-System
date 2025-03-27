<?php
  $page_title = 'Admin Home Page';
  require_once('includes/load.php');
  page_require_level(1);
?>
<?php
  $c_categorie     = count_by_id('categories');
  $c_product       = count_by_id('products');
  $c_sale          = count_by_id('sales');
  $c_user          = count_by_id('users');
  $products_sold   = find_higest_saleing_product('10');
  $recent_products = find_recent_product_added('5');
  $recent_sales    = find_recent_sale_added('5');
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<!-- Data Tabs Row -->

<style>
  .panel-box {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
  }

  .panel-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
  }

  .panel-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 24px;
    color: white;
    margin-right: 15px;
  }

  .bg-secondary1 { background: #6c757d; } /* Gray */
  .bg-red { background: #dc3545; } /* Red */
  .bg-blue2 { background: #007bff; } /* Blue */
  .bg-green { background: #28a745; } /* Green */

  .panel-value h2 {
    margin: 0;
    font-size: 22px;
    font-weight: bold;
  }

  .panel-value p {
    margin: 5px 0 0;
    font-size: 14px;
    color: #6c757d;
  }

  .data-card {
    text-decoration: none;
    color: black;
  }
</style>

<div class="row">
  <a href="users.php" class="data-card">
    <div class="col-md-3">
      <div class="panel-box">
        <div class="panel-icon bg-secondary1">
          <i class="glyphicon glyphicon-user"></i>
        </div>
        <div class="panel-value">
          <h2><?php echo $c_user['total']; ?></h2>
          <p>Users</p>
        </div>
      </div>
    </div>
  </a>
  
  <a href="categorie.php" class="data-card">
    <div class="col-md-3">
      <div class="panel-box">
        <div class="panel-icon bg-red">
          <i class="glyphicon glyphicon-th-large"></i>
        </div>
        <div class="panel-value">
          <h2><?php echo $c_categorie['total']; ?></h2>
          <p>Categories</p>
        </div>
      </div>
    </div>
  </a>

  <a href="product.php" class="data-card">
    <div class="col-md-3">
      <div class="panel-box">
        <div class="panel-icon bg-blue2">
          <i class="glyphicon glyphicon-shopping-cart"></i>
        </div>
        <div class="panel-value">
          <h2><?php echo $c_product['total']; ?></h2>
          <p>Products</p>
        </div>
      </div>
    </div>
  </a>

  <a href="sales.php" class="data-card">
    <div class="col-md-3">
      <div class="panel-box">
        <div class="panel-icon bg-green">
          <i class="glyphicon glyphicon-usd"></i>
        </div>
        <div class="panel-value">
          <h2><?php echo $c_sale['total']; ?></h2>
          <p>Sales</p>
        </div>
      </div>
    </div>
  </a>
</div>


<!-- Newly Added Products Row -->
<div class="row" style="margin-top: 30px;">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Newly Added Products</span>
        </strong>
      </div>
      <div class="panel-body">
        <div class="row" style="display: flex; flex-wrap: wrap; justify-content: space-between;">
          <?php foreach ($recent_products as $recent_product): ?>
            <div class="col-md-2" style="min-width: 220px; flex: 1 1 auto; margin: 10px;">
              <div class="panel panel-default">
                <div class="panel-body text-center">
                  <?php if($recent_product['media_id'] === '0'): ?>
                    <img class="img-responsive img-circle" src="uploads/products/no_image.png" alt="" style="width: 100px; height: 100px;">
                  <?php else: ?>
                    <img class="img-responsive img-circle" src="uploads/products/<?php echo $recent_product['image']; ?>" alt="" style="width: 100px; height: 100px;">
                  <?php endif; ?>
                  <h4><?php echo remove_junk(first_character($recent_product['name'])); ?></h4>
                  <p><strong>MWK<?php echo (int)$recent_product['sale_price']; ?></strong></p>
                  <p class="text-muted"><?php echo remove_junk(first_character($recent_product['categorie'])); ?></p>
                  <a href="edit_product.php?id=<?php echo (int)$recent_product['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .equal-height-row {
    display: flex;
    align-items: stretch;
  }
  .equal-height-row .panel {
    display: flex;
    flex-direction: column;
    height: 100%;
  }
  .equal-height-row .panel-body {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
  }
  .equal-height-row .table {
    flex-grow: 1;
  }
</style>

<div class="row equal-height-row">
  <!-- Top Selling Goods Panel -->
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Top Selling Goods</span>
        </strong>
      </div>
      <div class="panel-body">
        <table class="table table-striped table-bordered table-condensed">
          <thead>
            <tr>
              <th>Title</th>
              <th>Times Sold</th>
              <th>Total Quantity</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products_sold as $product_sold): ?>
              <tr>
                <td><?php echo remove_junk(first_character($product_sold['name'])); ?></td>
                <td><?php echo (int)$product_sold['totalSold']; ?></td>
                <td><?php echo (int)$product_sold['totalQty']; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Latest Sales Panel -->
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Latest Sales</span>
        </strong>
      </div>
      <div class="panel-body">
        <table class="table table-striped table-bordered table-condensed">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th>Product Name</th>
              <th>Date</th>
              <th>Total Sale</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recent_sales as $recent_sale): ?>
              <tr>
                <td class="text-center"><?php echo count_id(); ?></td>
                <td>
                  <a href="edit_sale.php?id=<?php echo (int)$recent_sale['id']; ?>">
                    <?php echo remove_junk(first_character($recent_sale['name'])); ?>
                  </a>
                </td>
                <td><?php echo remove_junk(ucfirst($recent_sale['date'])); ?></td>
                <td>MWK<?php echo remove_junk(first_character($recent_sale['price'])); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>



<?php include_once('layouts/footer.php'); ?>