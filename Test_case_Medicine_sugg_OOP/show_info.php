<?php
require_once "connection.php";
include_once "header.php";

//$db = new DB();

?>

<div class="container">

  <?php
   
    $getCompany = $_POST['company'];
    $getGenre = $_POST['genre'];

    $stmt = $db->showInfo($getCompany, $getGenre);
    $stmt->execute();
    $data = $stmt->fetchAll();

    $total = $db->medInfo($getCompany, $getGenre);
    //echo $total;
    
  ?>

  <h3>Search results for: <?php echo $getGenre . ' (' . $getCompany . ')' ; ?></h3>

    <?php if(!empty($data)) { ?>
     <table class="table table-hover">
        <thead>
        <tr>
          <th>Medicine Name</th>
          <th>Company</th>
          <th>Disease Name</th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($data as $row) { ?>

          <tr>
            <td><?php echo $row['med_name'] ?></td>
            <td><?php echo $row['maker_company'] ?></td>
            <td><?php echo $row['disease_name'] ?></td>
          </tr>

          <?php } ?>

        </tbody>
      </table>
    <?php } else { ?>
      <div class="error">No medicine found!</div>
    <?php } ?>

</div>

</body>
</html>