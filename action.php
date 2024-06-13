<?php
  require_once("./config/db.php");

  $qry = "SELECT * FROM nik where used=0 order by id desc limit 1";
  $query = mysqli_query($koneksi, $qry);
  $row = mysqli_fetch_array($query);
  if(!$query) {
    $nik = '';
  } else {
    $nik = $row['nik'];
  }
  if (isset($_GET['do']) && $_GET['do'] == 'get_nik') {
    echo $nik;
  }
?>