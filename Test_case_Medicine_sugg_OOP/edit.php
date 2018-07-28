<?php
require 'connection.php';
include 'header.php';

$id = $_GET['id'];

// get data
$stmt = $connect->prepare("SELECT * FROM medicine WHERE med_id='$id'");
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// update
if(isset($_POST['update'])) {

  // Get data from FROM
  $genre = $_POST['genre'];
  $name = $_POST['med_name'];
  $med_type = $_POST['med_type'];
  $company = $_POST['maker_company'];
  $disease_name = $_POST['disease_name'];
  $description = $_POST['description'];

	try {
		$stmt = $connect->prepare("UPDATE medicine SET genre='$genre', med_name='$name', med_type='$med_type', maker_company='$company', disease_name='$disease_name', description=' $description' WHERE med_id='$id'");
		$stmt->execute();
		header('Location: dashboard.php?action=updated');
		exit;
	}
	catch(PDOException $e) {
		echo $e->getMessage();
	}
}

?>

<div class="container">
  <h2>Update Data</h2>
  <form class="form-horizontal" action="" method="POST">
    <div class="form-group">
      <label class="control-label col-sm-2" >Genre:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" value="<?php echo $data['genre'] ?>" name="genre">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" >Medicine name:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" value="<?php echo $data['med_name'] ?>" name="med_name">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" >Type:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" value="<?php echo $data['med_type'] ?>" name="med_type">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2">Company:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" value="<?php echo $data['maker_company'] ?>" name="maker_company">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2">Disease Name:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" value="<?php echo $data['disease_name'] ?>" name="disease_name">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2">Description:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" value="<?php echo $data['description'] ?>" name="description">
      </div>
    </div>
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-default" name="update">Update</button>
      </div>
    </div>
  </form>
</div>