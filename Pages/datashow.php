<?php
include '../assets/Database/config.php';

$id_dataset = $_GET['id'];

$datas = mysqli_query($koneksi, "SELECT * FROM dataset WHERE id_dataset='$id_dataset'");
$data = mysqli_fetch_array($datas);
$nama_data = $data['nama_dataset_baru'];

error_reporting(0);
include '../assets/Library/spreadsheet-reader-master/SpreadsheetReader.php';
$reader = new SpreadsheetReader("../assets/Temp/" . $nama_data);

?>
<?php
include 'Template/header.php';
?>
<div class="row g-0 ">
  <div class="col-md-2">
    <?php include 'Template/sidebar.php'; ?>
  </div>
  <div class="col-md-9 Hero mt-5 ">
    <h3 class="fw-bold"><?php echo $data['nama_dataset_asli'] ?></h3>
    <!-- <label for="myMultiselect">BS custom multiselect</label> -->
    <div id="myMultiselect" class="multiselect" style="display: none;">
      <div id="mySelectLabel" class="selectBox" onclick="toggleCheckboxArea()">
        <select class="form-select">
          <option>
            <?php
            // get name column
            foreach ($reader as $row) {
              foreach ($row as $key => $value) {
                // implode value to string
                $name = implode(", ", $row);
                echo $name;
                break;
              }
              break;
            }

            ?>
          </option>
        </select>
        <div class="overSelect"></div>
      </div>
      <div id="mySelectOptions">

      </div>
    </div>
    <hr>
    <div class="wrapper mb-3">
      <table class="table table-bordered border-primary " id="ResultTable">
        <!-- show excel to table based on reader and many columns -->
        <?php
        $i = 0;
        foreach ($reader as $row) {
          if ($i == 0) {
            echo '<thead class="table-dark">';
            echo '<tr>';
            foreach ($row as $key => $value) {
              echo '<th scope="col" class="' . $key . '">' . $value . '</th>';
            }
            echo '</tr>';
            echo '</thead>';
          } else {
            echo '<tbody>';
            echo '<tr>';
            foreach ($row as $key => $value) {
              echo '<td class="' . $key . '">' . $value . '</td>';
            }
            echo '</tr>';
            echo '</tbody>';
          }
          $i++;
        }
        ?>

      </table>
    </div>
    <form method="post" action="forecast.php" class="row">
      <input type="hidden" name="id" value="<?php echo $data['id_dataset'] ?>">
      <div class="col-6">
        <span class="fw-bold fs-4 text-danger">&alpha;</span> = <input type="text" name="alpha" id="alpha" class="col-label-sm" placeholder=" contoh : 0.1">
      </div>
      <div class="col-6 text-end">
        <button class="btn btn-warning ">Ramal <i class="bi bi-chevron-right"></i></button>
      </div>
    </form>
  </div>
</div>

<script src="../assets/js/jquery.min.js"></script>  
<script src="../assets/js/xlsx.core.min.js"></script>
<script type="text/javascript">
  $table = $("#ResultTable");
  $tr = $table.find("th");
  var checkBlock = "";
  // var selected = [];
  $tr.each(function() {
    var colName = $(this).html();
    var id = $(this).attr("class");
    var count = 0;
    checkBlock =
      checkBlock +
      '<label><input type="checkbox" checked onChange=""checkboxStatusChange() class="checkblock" thClass="' +
      id + '" value="' + z + '"/> ' + colName + '</label>';
    count++;
  });
  var finalCheckBox = "<tr>" + checkBlock + "</tr>";
  $("#mySelectOptions").html(checkBlock);
</script>
<script>
  $(document).on("click", "input.checkblock", function() {
    initMultiselect();
    var className = $(this).attr("thClass");
    var className1 = "." + className;

    if ($(this).is(":checked")) {
      $table.find(className1).show();
    } else {
      $table.find(className1).hide();
    }
  });
</script>
<script>
  function initMultiselect() {
    checkboxStatusChange();

    document.addEventListener("click", function(evt) {
      var flyoutElement = document.getElementById('myMultiselect'),
        targetElement = evt.target; // clicked element

      do {
        if (targetElement == flyoutElement) {
          // This is a click inside. Do nothing, just return.
          //console.log('click inside');
          return;
        }

        // Go up the DOM
        targetElement = targetElement.parentNode;
      } while (targetElement);

      // This is a click outside.
      toggleCheckboxArea(true);
      //console.log('click outside');
    });
  }

  function checkboxStatusChange() {
    var multiselect = document.getElementById("mySelectLabel");
    var multiselectOption = multiselect.getElementsByTagName('option')[0];

    var values = [];
    var checkboxes = document.getElementById("mySelectOptions");
    var checkedCheckboxes = checkboxes.querySelectorAll('input[type=checkbox]:checked');

    for (const item of checkedCheckboxes) {
      var checkboxValue = item.getAttribute('value');
      values.push(checkboxValue);
    }

    var dropdownValue = "Nothing is selected";
    if (values.length > 0) {
      dropdownValue = values.join(', ');
    }

    multiselectOption.innerText = dropdownValue;
  }

  function toggleCheckboxArea(onlyHide = false) {
    var checkboxes = document.getElementById("mySelectOptions");
    var displayValue = checkboxes.style.display;

    if (displayValue != "block") {
      if (onlyHide == false) {
        checkboxes.style.display = "block";
      }
    } else {
      checkboxes.style.display = "none";
    }
  }
</script>
<?php
include 'Template/footer.php';
?>