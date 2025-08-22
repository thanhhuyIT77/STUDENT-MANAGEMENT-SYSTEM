<?php 
include '../Includes/dbcon.php';
include 'Includes/session.php';

// Get student information
$query = "SELECT tblstudents.*, tblclass.className
          FROM tblstudents 
          INNER JOIN tblclass ON tblclass.Id = tblstudents.classId
          WHERE tblstudents.Id = '$_SESSION[userId]'";
$rs = $conn->query($query);
$num = $rs->num_rows;
if($num > 0) {
    $rrw = $rs->fetch_assoc();
}

// Get attendance statistics
$query = "SELECT COUNT(*) as totalAttendance FROM tblattendance WHERE admissionNo = '$_SESSION[admissionNumber]'";
$rs = $conn->query($query);
$totalAttendance = $rs->fetch_assoc()['totalAttendance'];

$query = "SELECT COUNT(*) as presentDays FROM tblattendance WHERE admissionNo = '$_SESSION[admissionNumber]' AND status = '1'";
$rs = $conn->query($query);
$presentDays = $rs->fetch_assoc()['presentDays'];

$query = "SELECT COUNT(*) as absentDays FROM tblattendance WHERE admissionNo = '$_SESSION[admissionNumber]' AND status = '0'";
$rs = $conn->query($query);
$absentDays = $rs->fetch_assoc()['absentDays'];

$attendanceRate = $totalAttendance > 0 ? round(($presentDays / $totalAttendance) * 100, 2) : 0;
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
  <title>Bảng điều khiển sinh viên</title>
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
            <h1 class="h3 mb-0 text-gray-800">Bảng điều khiển sinh viên</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Trang chủ</a></li>
              <li class="breadcrumb-item active" aria-current="page">Bảng điều khiển</li>
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
                      <p><strong>Họ và tên:</strong> <?php echo isset($rrw['firstName']) ? $rrw['firstName'] . ' ' . $rrw['lastName'] . ' ' . $rrw['otherName'] : 'N/A'; ?></p>
                      <p><strong>Mã sinh viên:</strong> <?php echo isset($rrw['admissionNumber']) ? $rrw['admissionNumber'] : 'N/A'; ?></p>
                      <p><strong>Lớp:</strong> <?php echo isset($rrw['className']) ? $rrw['className'] : 'N/A'; ?></p>
                    </div>
                    <div class="col-md-6">
                      <p><strong>Ngày tạo:</strong> <?php echo isset($rrw['dateCreated']) ? $rrw['dateCreated'] : 'N/A'; ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row mb-3">
            <!-- Total Attendance Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Tổng số buổi học</div>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $totalAttendance;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-info"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Present Days Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Số buổi có mặt</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $presentDays;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Absent Days Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Số buổi vắng</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $absentDays;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-times-circle fa-2x text-danger"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Attendance Rate Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Tỷ lệ điểm danh</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $attendanceRate;?>%</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-percentage fa-2x text-warning"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Recent Attendance -->
          <div class="row">
            <div class="col-xl-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Điểm danh gần đây</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>Ngày</th>
                        <th>Trạng thái</th>
                        <th>Năm học</th>
                        <th>Học kỳ</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $query = "SELECT tblattendance.dateTimeTaken, tblattendance.status, 
                               tblsessionterm.sessionName, tblterm.termName
                               FROM tblattendance 
                               INNER JOIN tblsessionterm ON tblsessionterm.Id = tblattendance.sessionTermId
                               INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId
                               WHERE tblattendance.admissionNo = '$_SESSION[admissionNumber]'
                               ORDER BY tblattendance.dateTimeTaken DESC LIMIT 10";
                      $rs = $conn->query($query);
                      $num = $rs->num_rows;
                      if($num > 0) { 
                        while ($rows = $rs->fetch_assoc()) {
                          $status = $rows['status'] == '1' ? 'Có mặt' : 'Vắng';
                          $statusClass = $rows['status'] == '1' ? 'text-success' : 'text-danger';
                          echo "<tr>
                                  <td>".date('d/m/Y', strtotime($rows['dateTimeTaken']))."</td>
                                  <td class='$statusClass'><strong>$status</strong></td>
                                  <td>".$rows['sessionName']."</td>
                                  <td>".$rows['termName']."</td>
                                </tr>";
                        }
                      } else {
                        echo "<tr><td colspan='4' class='text-center'>Chưa có dữ liệu điểm danh</td></tr>";
                      }
                      ?>
                    </tbody>
                  </table>
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
  <!-- Page level plugins -->
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
</body>

</html>
