
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Initialize variables
$statusMsg = "";
$rrw = array();

// Get class information
$query = "SELECT tblclass.className
    FROM tblclass
    WHERE tblclass.Id = '$_SESSION[classId]'";
$rs = $conn->query($query);
$num = $rs->num_rows;
if($num > 0) {
    $rrw = $rs->fetch_assoc();
} else {
    // Redirect if teacher is not assigned to any class
    echo "<script type = \"text/javascript\">
    alert('Bạn chưa được phân công lớp nào!');
    window.location = (\"../index.php\");
    </script>";
    exit();
}

// Handle attendance submission
if(isset($_POST['save'])){
    $sessionTermId = $_POST['sessionTermId'];
    $dateTimeTaken = $_POST['dateTimeTaken'];
    $studentIds = $_POST['studentId'];
    $statuses = $_POST['status'];
    
    $successCount = 0;
    $errorCount = 0;
    
    for($i = 0; $i < count($studentIds); $i++) {
        $studentId = $studentIds[$i];
        $status = $statuses[$i];
        
        // Check if attendance already exists for this student on this date
        $checkQuery = mysqli_query($conn, "SELECT * FROM tblattendance WHERE admissionNo = '$studentId' AND dateTimeTaken = '$dateTimeTaken'");
        
        if(mysqli_num_rows($checkQuery) > 0) {
            // Update existing attendance
            $updateQuery = mysqli_query($conn, "UPDATE tblattendance SET status = '$status', classId = '$_SESSION[classId]' WHERE admissionNo = '$studentId' AND dateTimeTaken = '$dateTimeTaken'");
            
            if($updateQuery) {
                $successCount++;
            } else {
                $errorCount++;
            }
        } else {
            // Insert new attendance
            $insertQuery = mysqli_query($conn, "INSERT INTO tblattendance(admissionNo, classId, sessionTermId, status, dateTimeTaken) 
                VALUES('$studentId', '$_SESSION[classId]', '$sessionTermId', '$status', '$dateTimeTaken')");
            
            if($insertQuery) {
                $successCount++;
            } else {
                $errorCount++;
            }
        }
    }
    
    if($successCount > 0) {
        $statusMsg = "<div class='alert alert-success'>Cập nhật điểm danh thành công cho $successCount học sinh!</div>";
    }
    if($errorCount > 0) {
        $statusMsg .= "<div class='alert alert-danger'>Có lỗi xảy ra với $errorCount học sinh!</div>";
    }
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
  <title>Điểm danh học sinh</title>
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
            <h1 class="h3 mb-0 text-gray-800">Điểm danh học sinh - Lớp <?php echo isset($rrw['className']) ? $rrw['className'] : 'N/A'; ?></h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Trang chủ</a></li>
              <li class="breadcrumb-item active" aria-current="page">Điểm danh</li>
            </ol>
          </div>

          <?php echo $statusMsg; ?>

          <!-- Filter Form -->
          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Chọn năm học, học kỳ và ngày</h6>
                </div>
                <div class="card-body">
                  <form method="post" id="attendanceForm">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="sessionTermId">Năm học & Học kỳ:</label>
                          <select class="form-control" name="sessionTermId" id="sessionTermId" required>
                            <option value="">Chọn năm học & học kỳ</option>
                            <?php
                            $query = "SELECT tblsessionterm.*, tblterm.termName 
                                     FROM tblsessionterm 
                                     INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId 
                                     ORDER BY tblsessionterm.sessionName DESC, tblterm.termName";
                            $rs = $conn->query($query);
                            while ($row = $rs->fetch_assoc()) {
                              echo "<option value='".$row['Id']."'>".$row['sessionName']." - ".$row['termName']."</option>";
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="dateTimeTaken">Ngày:</label>
                          <input type="date" class="form-control" name="dateTimeTaken" id="dateTimeTaken" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>&nbsp;</label>
                          <button type="button" class="btn btn-primary btn-block" onclick="loadStudents()">
                            <i class="fas fa-search"></i> Tìm học sinh
                          </button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <!-- Students Attendance Table -->
          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Danh sách học sinh</h6>
                </div>
                <div class="card-body">
                  <div id="studentsTable">
                    <div class="text-center text-muted">
                      <i class="fas fa-info-circle fa-2x mb-3"></i>
                      <p>Vui lòng chọn năm học, học kỳ và ngày để hiển thị danh sách học sinh</p>
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
    function loadStudents() {
      var sessionTermId = $('#sessionTermId').val();
      var dateTimeTaken = $('#dateTimeTaken').val();
      
      if(!sessionTermId || !dateTimeTaken) {
        alert('Vui lòng chọn đầy đủ năm học, học kỳ và ngày!');
        return;
      }
      
      $.ajax({
        url: 'getStudentsForAttendance.php',
        type: 'POST',
        data: {
          sessionTermId: sessionTermId,
          dateTimeTaken: dateTimeTaken,
          classId: '<?php echo $_SESSION['classId']; ?>'
        },
        success: function(response) {
          $('#studentsTable').html(response);
        },
        error: function() {
          $('#studentsTable').html('<div class="alert alert-danger">Lỗi khi tải dữ liệu</div>');
        }
      });
    }
  </script>
</body>

</html>