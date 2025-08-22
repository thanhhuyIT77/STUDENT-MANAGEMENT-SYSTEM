<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Initialize variables
$statusMsg = "";
$rrw = array();

// Get class information
$query = "SELECT tblclass.className,tblclassarms.classArmName 
    FROM tblclassteacher
    INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId
    INNER JOIN tblclassarms ON tblclassarms.Id = tblclassteacher.classArmId
    Where tblclassteacher.Id = '$_SESSION[userId]'";
$rs = $conn->query($query);
$num = $rs->num_rows;
if($num > 0) {
    $rrw = $rs->fetch_assoc();
}

// Handle download request
if(isset($_POST['download'])) {
    $selectedDate = $_POST['selectedDate'];
    $downloadType = $_POST['downloadType'];
    
    if($downloadType == 'excel') {
        downloadExcel($conn, $selectedDate, $_SESSION['classId'], $_SESSION['classArmId']);
    } else if($downloadType == 'pdf') {
        downloadPDF($conn, $selectedDate, $_SESSION['classId'], $_SESSION['classArmId']);
    }
}

// Function to download Excel file
function downloadExcel($conn, $date, $classId, $classArmId) {
    $filename = "Bao_cao_diem_danh_" . date('Y-m-d', strtotime($date)) . ".xls";
    
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");
    
    echo '<table border="1">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>#</th>';
    echo '<th>Họ</th>';
    echo '<th>Tên</th>';
    echo '<th>Tên khác</th>';
    echo '<th>Mã sinh viên</th>';
    echo '<th>Lớp</th>';
    echo '<th>Phân lớp</th>';
    echo '<th>Năm học</th>';
    echo '<th>Học kỳ</th>';
    echo '<th>Trạng thái</th>';
    echo '<th>Ngày</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    
    $cnt = 1;
    $ret = mysqli_query($conn, "SELECT tblattendance.Id,tblattendance.status,tblattendance.dateTimeTaken,tblclass.className,
        tblclassarms.classArmName,tblsessionterm.sessionName,tblsessionterm.termId,tblterm.termName,
        tblstudents.firstName,tblstudents.lastName,tblstudents.otherName,tblstudents.admissionNumber
        FROM tblattendance
        INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
        INNER JOIN tblclassarms ON tblclassarms.Id = tblattendance.classArmId
        INNER JOIN tblsessionterm ON tblsessionterm.Id = tblattendance.sessionTermId
        INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId
        INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo
        WHERE tblattendance.dateTimeTaken = '$date' 
        AND tblattendance.classId = '$classId' 
        AND tblattendance.classArmId = '$classArmId'
        ORDER BY tblstudents.firstName, tblstudents.lastName");
    
    if(mysqli_num_rows($ret) > 0) {
        while ($row = mysqli_fetch_array($ret)) {
            $status = ($row['status'] == '1') ? "Có mặt" : "Vắng";
            
            echo '<tr>';
            echo '<td>' . $cnt . '</td>';
            echo '<td>' . $row['lastName'] . '</td>';
            echo '<td>' . $row['firstName'] . '</td>';
            echo '<td>' . $row['otherName'] . '</td>';
            echo '<td>' . $row['admissionNumber'] . '</td>';
            echo '<td>' . $row['className'] . '</td>';
            echo '<td>' . $row['classArmName'] . '</td>';
            echo '<td>' . $row['sessionName'] . '</td>';
            echo '<td>' . $row['termName'] . '</td>';
            echo '<td>' . $status . '</td>';
            echo '<td>' . date('d/m/Y', strtotime($row['dateTimeTaken'])) . '</td>';
            echo '</tr>';
            $cnt++;
        }
    } else {
        echo '<tr><td colspan="11" style="text-align: center;">Không có dữ liệu điểm danh cho ngày này</td></tr>';
    }
    
    echo '</tbody>';
    echo '</table>';
    exit();
}

// Function to download PDF (placeholder - would need TCPDF or similar library)
function downloadPDF($conn, $date, $classId, $classArmId) {
    // This would require TCPDF or similar PDF library
    // For now, redirect to Excel download
    downloadExcel($conn, $date, $classId, $classArmId);
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/attnlg.jpg" rel="icon">
  <title>Tải xuống báo cáo điểm danh</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
      <?php include "Includes/sidebar.php";?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
       <?php include "Includes/topbar.php";?>
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Tải xuống báo cáo điểm danh</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Trang chủ</a></li>
              <li class="breadcrumb-item active" aria-current="page">Tải xuống báo cáo</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Tải xuống báo cáo điểm danh - Lớp (<?php echo isset($rrw['className']) ? $rrw['className'] : 'N/A'; ?> - <?php echo isset($rrw['classArmName']) ? $rrw['classArmName'] : 'N/A'; ?>)</h6>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="selectedDate">Chọn ngày:</label>
                          <input type="date" class="form-control" id="selectedDate" name="selectedDate" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="downloadType">Định dạng tải xuống:</label>
                          <select class="form-control" id="downloadType" name="downloadType" required>
                            <option value="excel">Excel (.xls)</option>
                            <option value="pdf">PDF (.pdf)</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>&nbsp;</label>
                          <button type="submit" name="download" class="btn btn-primary btn-block">
                            <i class="fas fa-download"></i> Tải xuống báo cáo
                          </button>
                        </div>
                      </div>
                    </div>
                  </form>
                  
                  <hr>
                  
                  <!-- Preview of selected date data -->
                  <div class="row">
                    <div class="col-12">
                      <h6 class="font-weight-bold text-primary">Xem trước dữ liệu điểm danh:</h6>
                      <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="previewTable">
                          <thead class="thead-light">
                            <tr>
                              <th>#</th>
                              <th>Họ</th>
                              <th>Tên</th>
                              <th>Tên khác</th>
                              <th>Mã sinh viên</th>
                              <th>Lớp</th>
                              <th>Phân lớp</th>
                              <th>Năm học</th>
                              <th>Học kỳ</th>
                              <th>Trạng thái</th>
                              <th>Ngày</th>
                            </tr>
                          </thead>
                          <tbody id="previewData">
                            <!-- Data will be loaded via AJAX -->
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
       <?php include "Includes/footer.php";?>
      <!-- Footer -->
    </div>
  </div>

  <!-- Scroll to top -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
  
  <script>
    $(document).ready(function() {
      // Load preview data on page load
      loadPreviewData();
      
      // Load preview data when date changes
      $('#selectedDate').change(function() {
        loadPreviewData();
      });
      
      function loadPreviewData() {
        var selectedDate = $('#selectedDate').val();
        
        $.ajax({
          url: 'getAttendanceData.php',
          type: 'POST',
          data: {
            date: selectedDate,
            classId: '<?php echo $_SESSION['classId']; ?>',
            classArmId: '<?php echo $_SESSION['classArmId']; ?>'
          },
          success: function(response) {
            $('#previewData').html(response);
          },
          error: function() {
            $('#previewData').html('<tr><td colspan="11" class="text-center text-danger">Lỗi khi tải dữ liệu</td></tr>');
          }
        });
      }
    });
  </script>
</body>

</html>