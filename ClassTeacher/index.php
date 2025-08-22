
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Initialize variables
$rrw = array();

// Get class information
$query = "SELECT tblclass.className
    FROM tblclassteacher
    INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId
    Where tblclassteacher.Id = '$_SESSION[userId]'";
$rs = $conn->query($query);
$num = $rs->num_rows;
if($num > 0) {
    $rrw = $rs->fetch_assoc();
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
  <title>Bảng điều khiển giáo viên</title>
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
            <h1 class="h3 mb-0 text-gray-800">Bảng điều khiển giáo viên - Lớp (<?php echo isset($rrw['className']) ? $rrw['className'] : 'N/A'; ?>)</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Trang chủ</a></li>
              <li class="breadcrumb-item active" aria-current="page">Bảng điều khiển</li>
            </ol>
          </div>

          <div class="row mb-3">
          <!-- Students Card -->
          <?php 
$query1=mysqli_query($conn,"SELECT * from tblstudents WHERE classId = '$_SESSION[classId]'");                       
$students = mysqli_num_rows($query1);
?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Học sinh</div>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $students;?></div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <!-- <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 20.4%</span>
                        <span>Since last month</span> -->
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-info"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Attendance Card -->
             <?php 
$query1=mysqli_query($conn,"SELECT * from tblattendance WHERE classId = '$_SESSION[classId]' AND dateTimeTaken = CURDATE()");                       
$attendance = mysqli_num_rows($query1);
?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Điểm danh hôm nay</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $attendance;?></div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <!-- <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                        <span>Since last month</span> -->
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar-check fa-2x text-primary"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Subjects Card -->
             <?php 
$query1=mysqli_query($conn,"SELECT * from tblsubjects WHERE isActive = 1");                       
$subjects = mysqli_num_rows($query1);
?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Môn học</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $subjects;?></div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <!-- <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
                        <span>Since last years</span> -->
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-book fa-2x text-success"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Grades Card -->
             <?php 
$query1=mysqli_query($conn,"SELECT * from tblgrades WHERE classId = '$_SESSION[classId]'");                       
$grades = mysqli_num_rows($query1);
?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Bảng điểm</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $grades;?></div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <!-- <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
                        <span>Since last years</span> -->
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-chart-bar fa-2x text-warning"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Recent Activities -->
          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Hoạt động gần đây</h6>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <h6>Điểm danh gần đây</h6>
                      <div class="table-responsive">
                        <table class="table table-sm">
                          <thead>
                            <tr>
                              <th>Ngày</th>
                              <th>Số học sinh</th>
                              <th>Trạng thái</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $query = "SELECT dateTimeTaken, COUNT(*) as count, 
                                     SUM(CASE WHEN status = '1' THEN 1 ELSE 0 END) as present
                                     FROM tblattendance 
                                     WHERE classId = '$_SESSION[classId]' 
                                     GROUP BY dateTimeTaken 
                                     ORDER BY dateTimeTaken DESC 
                                     LIMIT 5";
                            $rs = $conn->query($query);
                            if($rs->num_rows > 0) {
                              while($row = $rs->fetch_assoc()) {
                                $present = $row['present'];
                                $total = $row['count'];
                                $absent = $total - $present;
                                echo "<tr>
                                        <td>".date('d/m/Y', strtotime($row['dateTimeTaken']))."</td>
                                        <td>$present có mặt, $absent vắng</td>
                                        <td><span class='badge badge-success'>Hoàn thành</span></td>
                                      </tr>";
                              }
                            } else {
                              echo "<tr><td colspan='3' class='text-center'>Chưa có dữ liệu điểm danh</td></tr>";
                            }
                            ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <h6>Điểm số gần đây</h6>
                      <div class="table-responsive">
                        <table class="table table-sm">
                          <thead>
                            <tr>
                              <th>Môn học</th>
                              <th>Điểm TB</th>
                              <th>Xếp loại</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $query = "SELECT tblsubjects.subjectName, 
                                     AVG(tblgrades.averageScore) as avgScore,
                                     COUNT(tblgrades.Id) as gradeCount
                                     FROM tblgrades 
                                     INNER JOIN tblsubjects ON tblsubjects.Id = tblgrades.subjectId
                                     WHERE tblgrades.classId = '$_SESSION[classId]' 
                                     GROUP BY tblgrades.subjectId 
                                     ORDER BY avgScore DESC 
                                     LIMIT 5";
                            $rs = $conn->query($query);
                            if($rs->num_rows > 0) {
                              while($row = $rs->fetch_assoc()) {
                                $avgScore = round($row['avgScore'], 1);
                                $grade = '';
                                if($avgScore >= 8.5) $grade = 'A';
                                elseif($avgScore >= 7.0) $grade = 'B';
                                elseif($avgScore >= 5.5) $grade = 'C';
                                elseif($avgScore >= 4.0) $grade = 'D';
                                else $grade = 'F';
                                
                                echo "<tr>
                                        <td>".$row['subjectName']."</td>
                                        <td>$avgScore</td>
                                        <td><span class='badge badge-info'>$grade</span></td>
                                      </tr>";
                              }
                            } else {
                              echo "<tr><td colspan='3' class='text-center'>Chưa có dữ liệu điểm số</td></tr>";
                            }
                            ?>
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
</body>

</html>