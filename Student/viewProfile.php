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
    $studentInfo = $rs->fetch_assoc();
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

// Get recent attendance
$query = "SELECT tblattendance.dateTimeTaken, tblattendance.status, 
         tblsessionterm.sessionName, tblterm.termName
         FROM tblattendance 
         INNER JOIN tblsessionterm ON tblsessionterm.Id = tblattendance.sessionTermId
         INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId
         WHERE tblattendance.admissionNo = '$_SESSION[admissionNumber]'
         ORDER BY tblattendance.dateTimeTaken DESC LIMIT 5";
$recentAttendance = $conn->query($query);

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
  <title>Thông tin sinh viên</title>
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
            <h1 class="h3 mb-0 text-gray-800">Thông tin sinh viên</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Trang chủ</a></li>
              <li class="breadcrumb-item active" aria-current="page">Thông tin cá nhân</li>
            </ol>
          </div>

          <!-- Student Info Card -->
          <div class="row mb-4">
            <div class="col-xl-12">
              <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Thông tin cá nhân</h6>
                  <a href="index.php" class="btn btn-primary btn-sm">
                    <i class="fas fa-arrow-left"></i> Quay lại
                  </a>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <h6 class="text-primary mb-3">Thông tin cơ bản</h6>
                      <table class="table table-borderless">
                        <tr>
                          <td width="150"><strong>Họ và tên:</strong></td>
                          <td><?php echo isset($studentInfo['firstName']) ? $studentInfo['firstName'] . ' ' . $studentInfo['lastName'] . ' ' . $studentInfo['otherName'] : 'N/A'; ?></td>
                        </tr>
                        <tr>
                          <td><strong>Mã sinh viên:</strong></td>
                          <td><span class="badge badge-info"><?php echo isset($studentInfo['admissionNumber']) ? $studentInfo['admissionNumber'] : 'N/A'; ?></span></td>
                        </tr>
                        <tr>
                          <td><strong>Lớp:</strong></td>
                          <td><span class="badge badge-primary"><?php echo isset($studentInfo['className']) ? $studentInfo['className'] : 'N/A'; ?></span></td>
                        </tr>
                        <tr>
                          <td><strong>Ngày tạo:</strong></td>
                          <td><?php echo isset($studentInfo['dateCreated']) ? date('d/m/Y', strtotime($studentInfo['dateCreated'])) : 'N/A'; ?></td>
                        </tr>
                      </table>
                    </div>
                    <div class="col-md-6">
                      <h6 class="text-primary mb-3">Thống kê điểm danh</h6>
                      <div class="row">
                        <div class="col-6">
                          <div class="text-center">
                            <div class="h4 text-info"><?php echo $totalAttendance;?></div>
                            <small class="text-muted">Tổng buổi học</small>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="text-center">
                            <div class="h4 text-success"><?php echo $presentDays;?></div>
                            <small class="text-muted">Có mặt</small>
                          </div>
                        </div>
                      </div>
                      <div class="row mt-3">
                        <div class="col-6">
                          <div class="text-center">
                            <div class="h4 text-danger"><?php echo $absentDays;?></div>
                            <small class="text-muted">Vắng</small>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="text-center">
                            <div class="h4 text-warning"><?php echo $attendanceRate;?>%</div>
                            <small class="text-muted">Tỷ lệ</small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Attendance Progress -->
          <div class="row mb-4">
            <div class="col-xl-12">
              <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Tiến độ điểm danh</h6>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-8">
                      <div class="progress mb-3" style="height: 25px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: <?php echo $attendanceRate;?>%" 
                             aria-valuenow="<?php echo $attendanceRate;?>" 
                             aria-valuemin="0" aria-valuemax="100">
                          <?php echo $attendanceRate;?>%
                        </div>
                      </div>
                      <small class="text-muted">
                        Tỷ lệ điểm danh: <?php echo $presentDays;?> có mặt / <?php echo $totalAttendance;?> buổi học
                      </small>
                    </div>
                    <div class="col-md-4 text-center">
                      <?php if($attendanceRate >= 80): ?>
                        <div class="text-success">
                          <i class="fas fa-trophy fa-3x"></i>
                          <div class="mt-2">Xuất sắc!</div>
                        </div>
                      <?php elseif($attendanceRate >= 60): ?>
                        <div class="text-info">
                          <i class="fas fa-thumbs-up fa-3x"></i>
                          <div class="mt-2">Tốt!</div>
                        </div>
                      <?php else: ?>
                        <div class="text-warning">
                          <i class="fas fa-exclamation-triangle fa-3x"></i>
                          <div class="mt-2">Cần cải thiện</div>
                        </div>
                      <?php endif; ?>
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
                  <table class="table table-bordered table-striped">
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
                      if($recentAttendance->num_rows > 0) { 
                        while ($rows = $recentAttendance->fetch_assoc()) {
                          $status = $rows['status'] == '1' ? 'Có mặt' : 'Vắng';
                          $statusClass = $rows['status'] == '1' ? 'badge-success' : 'badge-danger';
                          echo "<tr>
                                  <td>".date('d/m/Y', strtotime($rows['dateTimeTaken']))."</td>
                                  <td><span class='badge $statusClass'>$status</span></td>
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

          <!-- Quick Actions -->
          <div class="row">
            <div class="col-xl-12">
              <div class="card">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Thao tác nhanh</h6>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-3 text-center mb-3">
                      <a href="viewMyAttendance.php" class="btn btn-outline-primary btn-lg btn-block">
                        <i class="fas fa-calendar-check fa-2x mb-2"></i><br>
                        Xem điểm danh
                      </a>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                      <a href="viewMyGrades.php" class="btn btn-outline-success btn-lg btn-block">
                        <i class="fas fa-chart-line fa-2x mb-2"></i><br>
                        Xem điểm số
                      </a>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                      <a href="viewAttendanceReport.php" class="btn btn-outline-info btn-lg btn-block">
                        <i class="fas fa-file-alt fa-2x mb-2"></i><br>
                        Báo cáo
                      </a>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                      <a href="index.php" class="btn btn-outline-secondary btn-lg btn-block">
                        <i class="fas fa-home fa-2x mb-2"></i><br>
                        Trang chủ
                      </a>
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
</body>

</html>
