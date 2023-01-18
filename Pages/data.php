<?php

include '../assets/Database/config.php';

function alert()
{
  global $koneksi;
  if (mysqli_affected_rows($koneksi) > 0) {
    echo "<script> 
                alert('INPUT BERHASIL !') ;
                document.location.href = 'data.php';            
            </script>";
  } else {
    echo "<script> 
                alert('INPUT GAGAL !') ;
                document.location.href = 'data.php';
            </script>";
  }
}

if (isset($_POST["submit"]) == "submit") {
  $namafile = @$_FILES['file']['name'];
  $tmpname = @$_FILES['file']['tmp_name'];

  $ekssplit = explode('.', $namafile);
  $ekstensi = strtolower(end($ekssplit));

  $namafilebaru = uniqid();
  $namafilebaru .= ".";
  $namafilebaru .= $ekstensi;

  move_uploaded_file($tmpname, '../assets/Temp/' . $namafilebaru);

  mysqli_query($koneksi, "INSERT INTO dataset(nama_dataset_asli,nama_dataset_baru) VALUES ('$namafile', '$namafilebaru')");
  alert();
}

$aksi = @$_GET['aksi'];
$id = @$_GET['id'];
$nama = @$_GET['nama'];

if ($aksi == "hapus") {

  unlink("../assets/Temp/$nama");


  mysqli_query($koneksi, "DELETE FROM dataset WHERE id_dataset='$id'");
}

$datas = mysqli_query($koneksi, "SELECT * FROM dataset");
?>



<?php
include 'Template/header.php';
?>
<div class="row g-0 ">
  <div class="col-md-2">
    <?php include 'Template/sidebar.php'; ?>
  </div>
  <div class="col-md-9 Hero">
    <h3 class="mt-4">Dataset</h3>
    <hr>
    <form action="" method="post" enctype="multipart/form-data">
      <span class="fw-bold">Tambah dataset :</span>
      <input type="file" name="file" id="file">
      <input class="btn btn-primary" type="submit" name="submit" id="submit" value="submit">
    </form>
    <hr>
    <table class="table table-bordered border-primary">
      <tr class="text-center">
        <th style="width: 60px;">No</th>
        <th>Nama Dataset</th>
        <th class="w-25">Aksi</th>
      </tr>
      <?php $no = 1; ?>
      <?php foreach ($datas as $data) { ?>
        <tr>
          <td class="text-center"><?php echo $no; ?></td>
          <td><?php echo $data['nama_dataset_asli'] ?></td>
          <?php $no++; ?>
          <td class="text-center">
            <a style="text-decoration: none;" href="datashow.php?id=<?php echo $data['id_dataset'] ?>">
              <button class="btn btn-success"><i class="bi bi-check-circle"></i>&nbsp; Pilih</button>
            </a>
            <a style="text-decoration: none;" href="data.php?id=<?php echo $data['id_dataset'] ?>&aksi=hapus&nama=<?php echo $data['nama_dataset_baru'] ?>">
              <button class="btn btn-danger"><i class="bi bi-trash3"></i>&nbsp; Hapus</button>
            </a>
          </td>
        </tr>
      <?php } ?>
    </table>
  </div>
</div>

<?php
include 'Template/footer.php';
?>