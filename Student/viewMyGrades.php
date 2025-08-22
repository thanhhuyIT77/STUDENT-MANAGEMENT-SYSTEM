<?php 
include '../Includes/dbcon.php';
include 'Includes/session.php';

// Get student information
$query = "SELECT tblstudents.*, tblclass.className 
          FROM tblstudents 
          INNER JOIN tblclass ON tblclass.Id = tblstudents.classId
          WHERE tblstudents.Id = '$_SESSION[userId]'";
$rs = $conn->query($query);
$studentInfo = $rs->fetch_assoc();

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
  <title>Xem điểm số của tôi</title>
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
            <h1 class="h3 mb-0 text-gray-800">Điểm số của tôi</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Trang chủ</a></li>
              <li class="breadcrumb-item active" aria-current="page">Điểm số</li>
            </ol>
          </div>

          <!-- Student Info Card -->
          <div class="row mb-4">
            <div class="col-xl-12">
              <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Thông tin sinh viên</h6>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <p><strong>Họ và tên:</strong> <?php echo $studentInfo['firstName'] . ' ' . $studentInfo['lastName'] . ' ' . $studentInfo['otherName']; ?></p>
                      <p><strong>Mã sinh viên:</strong> <?php echo $studentInfo['admissionNumber']; ?></p>
                      <p><strong>Lớp:</strong> <?php echo $studentInfo['className']; ?></p>
                    </div>
                    <div class="col-md-6">
                      <p><strong>Ngày tạo:</strong> <?php echo $studentInfo['dateCreated']; ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Filter Form -->
          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Chọn học kỳ để xem điểm</h6>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="sessionTermId">Năm học & Học kỳ:</label>
                        <select class="form-control" id="sessionTermId" onchange="loadGrades()">
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
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Grades Table -->
          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Bảng điểm chi tiết</h6>
                </div>
                <div class="card-body">
                  <div id="gradesTable">
                    <div class="text-center text-muted">
                      <i class="fas fa-info-circle fa-2x mb-3"></i>
                      <p>Vui lòng chọn năm học và học kỳ để xem điểm số</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Grade Summary -->
          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Tổng kết điểm</h6>
                </div>
                <div class="card-body">
                  <div id="gradeSummary">
                    <!-- Summary will be loaded here -->
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
    function loadGrades() {
      var sessionTermId = $('#sessionTermId').val();
      
      if(!sessionTermId) {
        $('#gradesTable').html('<div class="text-center text-muted"><i class="fas fa-info-circle fa-2x mb-3"></i><p>Vui lòng chọn năm học và học kỳ để xem điểm số</p></div>');
        $('#gradeSummary').html('');
        return;
      }
      
      $.ajax({
        url: 'getMyGrades.php',
        type: 'POST',
        data: {
          sessionTermId: sessionTermId,
          studentId: '<?php echo $_SESSION['userId']; ?>'
        },
        success: function(response) {
          $('#gradesTable').html(response);
        },
        error: function() {
          $('#gradesTable').html('<div class="alert alert-danger">Lỗi khi tải dữ liệu</div>');
        }
      });
      
      // Load summary
      $.ajax({
        url: 'getGradeSummary.php',
        type: 'POST',
        data: {
          sessionTermId: sessionTermId,
          studentId: '<?php echo $_SESSION['userId']; ?>'
        },
        success: function(response) {
          $('#gradeSummary').html(response);
        },
        error: function() {
          $('#gradeSummary').html('<div class="alert alert-danger">Lỗi khi tải tổng kết</div>');
        }
      });
    }
  </script>
</body>

</html>
