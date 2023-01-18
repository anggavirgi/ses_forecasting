<?php
include 'Pages/Template/header.php';
?>
<div class="row g-0 ">
  <div class="col-md-2">
    <?php include 'Pages/Template/sidebar.php'; ?>
  </div>
  <div class="col-md-9 Hero">
    <h1 class="text-center">Single Exponential Smoothing</h1>
    <p>Single Exponential Smoothing adalah metode yang menunjukan pembobotan menurun secara eksponensial terhadap nilai observasi yang lebih lama.</p>
    <p>Metode ini memberikan sebuah pembobotan eksponensial rata-rata bergerak dari semua nilai observasi sebelumnya.</p>
    <p>Metode ini tidak dipengaruhi oleh trend maupun musim.</p>
    <p>Rumusnya
      Y’t+1 = αYt + (1-α) Y’t . . . . . . . . . . . . . (1)
      <br> Keterangan:
      <br>Y’t+1= nilai peramalan untuk periode berikutnya
      <br>Yt = Data aktual untuk periode t
      <br>Y’t = nilai peramalan untuk periode t
      <br>α = faktor bobot penghalusan (0 < α < 1) </p>
        <p>Besaran Nilai α</p>
        <p>Nilai Y’t belum diketahui, maka lakukan inisialisasi nilai :
          <br> - tetapkan peramalan pertama sama dengan data/observasi pertama, Y1=Y’1

          <br> - Gunakan rata-rata dari lima atau enam data pertama sebagai peramalan pertama, Y1=MA(5) atau Y1=MA(6)
        </p>
        <div class="author">
          <p>Aliffedo Desvian - 200441100028</p>
          <p>Dwi Angga Virgi - 200441100174</p>
        </div>
  </div>
</div>



<?php
include 'Pages/Template/footer.php';
?>