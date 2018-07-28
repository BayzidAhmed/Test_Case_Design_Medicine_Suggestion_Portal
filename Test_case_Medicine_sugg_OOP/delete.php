<?php
require 'connection.php';
include 'header.php';

$id = $_GET['id'];
$db->delete($id);

?>