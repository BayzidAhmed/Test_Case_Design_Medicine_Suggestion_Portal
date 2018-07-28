<?php
require 'connection.php';
$title = 'All Medicines';
include 'header.php';

// get data
$data = $db->getRows('medicine',array('order_by'=>'med_id ASC'));

if(isset($_GET['action'])) {
  if($_GET['action'] == 'updated') {
    $msg = 'Updated....!';
  }
  if($_GET['action'] == 'removed') {
    $msg = 'Removed....!';
  }
}
?>

<div class="container">
  <h3>Search Medicine</h3>

  <?php if(isset($msg)) { ?>
  <div class="alert alert-success">
    <strong>Success!</strong> <?php echo $msg; ?>
  </div>
  <?php } ?>
  
  <?php if(!empty($data)) { ?>

  <form class="form-horizontal" action="show_info.php" method="POST">
    <select name="company" class="selectpicker" data-show-subtext="true" data-live-search="true">
      <option data-tokens="#" value="">Select a Company</option>
      <?php foreach ($data as $row) { ?>
      <option data-tokens="<?php echo $row['maker_company'] ?>"><?php echo $row['maker_company'] ?></option>
      <?php } ?>
    </select>
    <select name="genre" class="selectpicker" data-show-subtext="true" data-live-search="true">
      <option data-tokens="#" value="">Select Drug Class</option>
      <?php foreach ($data as $row) { ?>
      <option data-tokens="<?php echo $row['genre'] ?>"><?php echo $row['genre'] ?></option>
      <?php } ?>
    </select>
    <input class="btn btn-primary" type="submit" name="submit" value="Search">
  </form>

  <div class="tab-content">
    <div id="tab1" class="tab-pane fade in active">
      <h3>Medicine Information</h3>
       <table class="table table-bordered">
        <thead>
        <tr>
          <th>Medicine Class</th>
          <th>Medicine Name</th>
          <th>Medicine Type</th>
          <th>Company</th>
          <th>Disease Name</th>
          <th>Contains</th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($data as $row) { ?>

          <tr>
            <td><?php echo $row['genre'] ?></td>
            <td><?php echo $row['med_name'] ?></td>
            <td><?php echo $row['med_type'] ?></td>
            <td><?php echo $row['maker_company'] ?></td>
            <td><?php echo $row['disease_name'] ?></td>
            <td><?php echo $row['description'] ?></td>
          </tr>

          <?php } ?>

        </tbody>
        </table>
    </div>
    <div id="menu1" class="tab-pane fade">
      <h3>Fixtures1</h3>
      <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
    </div>
    <div id="menu2" class="tab-pane fade">
      <h3>Fixtures</h3>
      <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
    </div>
    <div id="menu3" class="tab-pane fade">
      <h3>Fixtures</h3>
      <p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
    </div>
  </div>

  <?php } else {
    echo "No data";
  }?>
</div>

</body>
</html>
