
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Initialize variables
$statusMsg = "";
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

// Handle delete
if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    $id = $_GET['Id'];
    $query = mysqli_query($conn, "DELETE FROM tblstudents WHERE Id = '$id'");
    
    if ($query) {
        $statusMsg = "<div class='alert alert-success'>Xóa sinh viên thành công!</div>";
    } else {
        $statusMsg = "<div class='alert alert-danger'>Đã xảy ra lỗi!</div>";
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
  <title>Tất cả học sinh trong lớp</title>
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
            <h1 class="h3 mb-0 text-gray-800">Tất cả học sinh trong lớp (<?php echo isset($rrw['className']) ? $rrw['className'] : 'N/A'; ?>)</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Trang chủ</a></li>
              <li class="breadcrumb-item active" aria-current="page">Xem học sinh</li>
            </ol>
          </div>

          <?php echo $statusMsg; ?>

          <!-- Input Group -->
          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Danh sách học sinh</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>Họ và tên</th>
                        <th>Tên khác</th>
                        <th>Mã sinh viên</th>
                        <th>Lớp</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $query = "SELECT tblstudents.Id,tblclass.className,tblstudents.firstName,
                      tblstudents.lastName,tblstudents.otherName,tblstudents.admissionNumber,tblstudents.dateCreated
                      FROM tblstudents
                      INNER JOIN tblclass ON tblclass.Id = tblstudents.classId
                      WHERE tblstudents.classId = '$_SESSION[classId]'
                      ORDER BY tblstudents.firstName, tblstudents.lastName";
                      $rs = $conn->query($query);
                      $num = $rs->num_rows;
                      $sn = 0;
                      if($num > 0) { 
                        while ($rows = $rs->fetch_assoc()) {
                          $sn = $sn + 1;
                          echo "<tr>
                                  <td>".$sn."</td>
                                  <td>".$rows['firstName']." ".$rows['lastName']."</td>
                                  <td>".$rows['otherName']."</td>
                                  <td>".$rows['admissionNumber']."</td>
                                  <td>".$rows['className']."</td>
                                  <td>".$rows['dateCreated']."</td>
                                  <td>
                                    <a href='viewStudents.php?Id=".$rows['Id']."&action=delete' class='btn btn-sm btn-danger' onclick='return confirm(\"Bạn có chắc muốn xóa sinh viên này?\")'>
                                      <i class='fas fa-trash'></i> Xóa
                                    </a>
                                  </td>
                                </tr>";
                        }
                      } else {
                        echo "<tr><td colspan='7' class='text-center'>Không có dữ liệu</td></tr>";
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <!--Row-->

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