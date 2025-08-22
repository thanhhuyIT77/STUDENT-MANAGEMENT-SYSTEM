<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Initialize variables
$statusMsg = "";
$row = array();

// Handle form submissions
if(isset($_POST['save'])){
    $subjectName = $_POST['subjectName'];
    $subjectCode = $_POST['subjectCode'];
    $credits = $_POST['credits'];
    $dateCreated = date("Y-m-d");
    
    // Check if subject code already exists
    $query = mysqli_query($conn, "SELECT * FROM tblsubjects WHERE subjectCode = '$subjectCode'");
    $ret = mysqli_fetch_array($query);
    
    if($ret > 0){ 
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Mã môn học này đã tồn tại!</div>";
    } else {
        $query = mysqli_query($conn, "INSERT INTO tblsubjects(subjectName, subjectCode, credits, dateCreated) 
        VALUES('$subjectName','$subjectCode','$credits','$dateCreated')");
        
        if ($query) {
            $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Tạo môn học thành công!</div>";
        } else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Đã xảy ra lỗi!</div>";
        }
    }
}

// Handle edit
if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $id = $_GET['Id'];
    $query = mysqli_query($conn, "SELECT * FROM tblsubjects WHERE Id = '$id'");
    $row = mysqli_fetch_array($query);
}

// Handle update
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $subjectName = $_POST['subjectName'];
    $subjectCode = $_POST['subjectCode'];
    $credits = $_POST['credits'];
    
    $query = mysqli_query($conn, "UPDATE tblsubjects SET subjectName='$subjectName', subjectCode='$subjectCode', 
    credits='$credits' WHERE Id = '$id'");
    
    if ($query) {
        $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Cập nhật thành công!</div>";
        $row = array(); // Clear form
    } else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Đã xảy ra lỗi!</div>";
    }
}

// Handle delete
if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    $id = $_GET['Id'];
    $query = mysqli_query($conn, "DELETE FROM tblsubjects WHERE Id = '$id'");
    
    if ($query) {
        $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Xóa môn học thành công!</div>";
    } else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Đã xảy ra lỗi!</div>";
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
  <title>Quản lý môn học</title>
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
            <h1 class="h3 mb-0 text-gray-800">Quản lý môn học</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Trang chủ</a></li>
              <li class="breadcrumb-item active" aria-current="page">Quản lý môn học</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary"><?php echo isset($row['Id']) ? 'Cập nhật môn học' : 'Thêm môn học mới'; ?></h6>
                </div>
                <div class="card-body">
                  <?php echo $statusMsg; ?>
                  <form method="post">
                    <?php if(isset($row['Id'])): ?>
                      <input type="hidden" name="id" value="<?php echo $row['Id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Tên môn học<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="subjectName" value="<?php echo isset($row['subjectName']) ? $row['subjectName'] : ''; ?>" required>
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Mã môn học<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="subjectCode" value="<?php echo isset($row['subjectCode']) ? $row['subjectCode'] : ''; ?>" required>
                      </div>
                    </div>
                    
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Số tín chỉ</label>
                        <input type="number" class="form-control" name="credits" value="<?php echo isset($row['credits']) ? $row['credits'] : '3'; ?>" min="1" max="10">
                      </div>
                    </div>
                    
                    <button type="submit" name="<?php echo isset($row['Id']) ? 'update' : 'save'; ?>" class="btn btn-primary">
                      <?php echo isset($row['Id']) ? 'Cập nhật' : 'Lưu'; ?>
                    </button>
                    
                    <?php if(isset($row['Id'])): ?>
                      <a href="manageSubjects.php" class="btn btn-secondary">Hủy</a>
                    <?php endif; ?>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <!-- Input Group -->
          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Danh sách môn học</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>Tên môn học</th>
                        <th>Mã môn học</th>
                        <th>Số tín chỉ</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $query = "SELECT * FROM tblsubjects ORDER BY subjectName";
                      $rs = $conn->query($query);
                      $num = $rs->num_rows;
                      $sn = 0;
                      if($num > 0) { 
                        while ($rows = $rs->fetch_assoc()) {
                          $sn = $sn + 1;
                          echo "<tr>
                                  <td>".$sn."</td>
                                  <td>".$rows['subjectName']."</td>
                                  <td>".$rows['subjectCode']."</td>
                                  <td>".$rows['credits']."</td>
                                  <td>".$rows['dateCreated']."</td>
                                  <td>
                                    <a href='manageSubjects.php?Id=".$rows['Id']."&action=edit' class='btn btn-sm btn-primary'>
                                      <i class='fas fa-edit'></i> Sửa
                                    </a>
                                    <a href='manageSubjects.php?Id=".$rows['Id']."&action=delete' class='btn btn-sm btn-danger' onclick='return confirm(\"Bạn có chắc muốn xóa môn học này?\")'>
                                      <i class='fas fa-trash'></i> Xóa
                                    </a>
                                  </td>
                                </tr>";
                        }
                      } else {
                        echo "<tr><td colspan='6' class='text-center'>Không có dữ liệu</td></tr>";
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
