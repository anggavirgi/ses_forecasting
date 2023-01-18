<?php
  include 'config.php';

  function alert(){
    global $koneksi;
    if(mysqli_affected_rows($koneksi) > 0){
        echo "<script> 
                alert('INPUT BERHASIL !') ;
                document.location.href = 'index.php';            
            </script>";
    } else {
        echo "<script> 
                alert('INPUT GAGAL !') ;
                document.location.href = 'index.php';
            </script>";
    }
  }

  if(isset($_POST["submit"]) == "submit"){
    $namafile = @$_FILES['file']['name'];
    $tmpname = @$_FILES['file']['tmp_name'];

    $ekssplit = explode('.', $namafile);
    $ekstensi = strtolower(end($ekssplit));

    $namafilebaru = uniqid();
    $namafilebaru.= ".";
    $namafilebaru.= $ekstensi;

    move_uploaded_file($tmpname, 'dataset/'.$namafilebaru);
    
    mysqli_query($koneksi, "INSERT INTO dataset(nama_dataset_asli,nama_dataset_baru) VALUES ('$namafile', '$namafilebaru')");
    alert();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <div>
    <h3>UPLOAD FILE</h3>
    <form action="" method="post" enctype="multipart/form-data">
      <input type="file" name="file" id="file">
      <input type="submit" name="submit" id="submit" value="submit">
    </form>
  </div>
  <h3>COBA</h3>
  <?php 
    error_reporting(0);
    include 'assets\spreadsheet-reader-master\SpreadsheetReader.php';
    $data = new SpreadsheetReader("death.csv");
    $a = 0.1;
    // foreach($data as $row){
    //   if($count==0){
    //       $count++;
    //       continue;
    //   }
    //   echo $row[1]."<br>";
    //   $count++;
    // }
  ?>
  <table border=1>
    <tr>
      <th>tanggal</th>
      <th>data</th>
      <th>forecast</th>
      <th>mape</th>
    </tr>
    <tr>
      <td>
       <?php
        foreach($data as $tgl){
          if($count==0){
              $count++;
              continue;
          }
          echo $tgl[0]."<br>";
          $count++;
        }
       ?>
      </td>
      <td>
       <?php
        $temp_val = [];
        foreach($data as $nilai){
          if($count1==0){
              $count1++;
              continue;
          }
          echo $nilai[1]."<br>";
          $count1++;
          $temp_val[] = $nilai[1];
        }
       ?>
      </td>
      <td>
        <?php
          $temp_nilai = [];
          $hasil_fc = [];
          for($j=0; $j<count($temp_val); $j++){
            if($j != 0){
              $x = ($a*$temp_val[$j-1])+((1-$a)*$temp_nilai);
              echo number_format($x, 2, ".", "")."<br>";
              $temp_nilai = $x;
              $hasil_fc[] = $x;
            } else{
              echo number_format($temp_val[0], 2, ".", "")."<br>";
              $temp_nilai = $temp_val[0];
              $hasil_fc[] = $temp_val[0];
            }
          }
        ?>
      </td>
      <td>
        <?php
          $mape = [];
          for($i=0; $i<count($hasil_fc); $i++){
            $y = abs(($temp_val[$i]-$hasil_fc[$i])/$temp_val[$i])*100;
            echo number_format($y, 2, ".", "")."<br>";
            $mape[] = $y;
          }
        ?>
      </td>
    </tr>
  </table>

  <h4>
    Hasil Forecast 1 periode kedepan : 
    <?php
      $hasil_akhir = ($a*end($temp_val))+((1-$a)*$temp_nilai);
      $hasil_akhir_format = number_format($hasil_akhir, 2, ".", "");
      echo $hasil_akhir_format;
    ?>
  </h4>
  <h4>
    MAPE :
    <?php
      $mape_akhir = array_sum($mape)/count($mape);
      $mape_akhir_format = number_format($mape_akhir, 2, ".", "");
      echo $mape_akhir_format;
    ?>
  </h4>
</body>
</html>