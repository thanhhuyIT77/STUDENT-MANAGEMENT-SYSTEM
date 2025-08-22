
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Initialize variables
$statusMsg = "";
$row = array();

// Handle form submissions
if(isset($_POST['save'])){
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $emailAddress = $_POST['emailAddress'];
    $password = $_POST['password'];
    $phoneNo = $_POST['phoneNo'];
    $classId = $_POST['classId'];
    $dateCreated = date("Y-m-d");
    
    // Check if email already exists
    $query = mysqli_query($conn, "SELECT * FROM tblclassteacher WHERE emailAddress = '$emailAddress'");
    $ret = mysqli_fetch_array($query);
    
    if($ret > 0){ 
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Email này đã tồn tại!</div>";
    } else {
        $query = mysqli_query($conn, "INSERT INTO tblclassteacher(firstName, lastName, emailAddress, password, phoneNo, classId, dateCreated) 
        VALUES('$firstName','$lastName','$emailAddress','$password','$phoneNo','$classId','$dateCreated')");
        
        if ($query) {
            $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Tạo giáo viên thành công!</div>";
        } else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Đã xảy ra lỗi!</div>";
        }
    }
}

// Handle edit
if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $id = $_GET['Id'];
    $query = mysqli_query($conn, "SELECT * FROM tblclassteacher WHERE Id = '$id'");
    $row = mysqli_fetch_array($query);
}

// Handle update
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $emailAddress = $_POST['emailAddress'];
    $password = $_POST['password'];
    $phoneNo = $_POST['phoneNo'];
    $classId = $_POST['classId'];
    
    $query = mysqli_query($conn, "UPDATE tblclassteacher SET firstName='$firstName', lastName='$lastName', 
    emailAddress='$emailAddress', password='$password', phoneNo='$phoneNo', classId='$classId' WHERE Id = '$id'");
    
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
    $query = mysqli_query($conn, "DELETE FROM tblclassteacher WHERE Id = '$id'");
    
    if ($query) {
        $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Xóa giáo viên thành công!</div>";
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
  <title>Tạo giáo viên chủ nhiệm</title>
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
            <h1 class="h3 mb-0 text-gray-800">Tạo giáo viên chủ nhiệm</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Trang chủ</a></li>
              <li class="breadcrumb-item active" aria-current="page">Tạo giáo viên</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary"><?php echo isset($row['Id']) ? 'Cập nhật giáo viên' : 'Thêm giáo viên mới'; ?></h6>
                </div>
                <div class="card-body">
                  <?php echo $statusMsg; ?>
                  <form method="post">
                    <?php if(isset($row['Id'])): ?>
                      <input type="hidden" name="id" value="<?php echo $row['Id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Họ<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="firstName" value="<?php echo isset($row['firstName']) ? $row['firstName'] : ''; ?>" required>
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Tên<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="lastName" value="<?php echo isset($row['lastName']) ? $row['lastName'] : ''; ?>" required>
                      </div>
                    </div>
                    
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Email<span class="text-danger ml-2">*</span></label>
                        <input type="email" class="form-control" name="emailAddress" value="<?php echo isset($row['emailAddress']) ? $row['emailAddress'] : ''; ?>" required>
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Mật khẩu<span class="text-danger ml-2">*</span></label>
                        <input type="password" class="form-control" name="password" value="<?php echo isset($row['password']) ? $row['password'] : ''; ?>" required>
                      </div>
                    </div>
                    
                    <div class="form-group row mb-3">
                      <div class="col-xl-6">
                        <label class="form-control-label">Số điện thoại<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="phoneNo" value="<?php echo isset($row['phoneNo']) ? $row['phoneNo'] : ''; ?>" required>
                      </div>
                      <div class="col-xl-6">
                        <label class="form-control-label">Lớp<span class="text-danger ml-2">*</span></label>
                        <select class="form-control" name="classId" required>
                          <option value="">Chọn lớp</option>
                          <?php
                          $query = "SELECT * FROM tblclass ORDER BY className";
                          $rs = $conn->query($query);
                          while ($rows = $rs->fetch_assoc()) {
                            $selected = (isset($row['classId']) && $row['classId'] == $rows['Id']) ? 'selected' : '';
                            echo "<option value='".$rows['Id']."' ".$selected.">".$rows['className']."</option>";
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    
                    <button type="submit" name="<?php echo isset($row['Id']) ? 'update' : 'save'; ?>" class="btn btn-primary">
                      <?php echo isset($row['Id']) ? 'Cập nhật' : 'Lưu'; ?>
                    </button>
                    
                    <?php if(isset($row['Id'])): ?>
                      <a href="createClassTeacher.php" class="btn btn-secondary">Hủy</a>
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
                  <h6 class="m-0 font-weight-bold text-primary">Danh sách giáo viên chủ nhiệm</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>Họ và tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Lớp</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $query = "SELECT tblclassteacher.Id,tblclass.className,tblclassteacher.firstName,
                      tblclassteacher.lastName,tblclassteacher.emailAddress,tblclassteacher.phoneNo,tblclassteacher.dateCreated
                      FROM tblclassteacher
                      INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId
                      ORDER BY tblclassteacher.firstName, tblclassteacher.lastName";
                      $rs = $conn->query($query);
                      $num = $rs->num_rows;
                      $sn = 0;
                      if($num > 0) { 
                        while ($rows = $rs->fetch_assoc()) {
                          $sn = $sn + 1;
                          echo "<tr>
                                  <td>".$sn."</td>
                                  <td>".$rows['firstName']." ".$rows['lastName']."</td>
                                  <td>".$rows['emailAddress']."</td>
                                  <td>".$rows['phoneNo']."</td>
                                  <td>".$rows['className']."</td>
                                  <td>".$rows['dateCreated']."</td>
                                  <td>
                                    <a href='createClassTeacher.php?Id=".$rows['Id']."&action=edit' class='btn btn-sm btn-primary'>
                                      <i class='fas fa-edit'></i> Sửa
                                    </a>
                                    <a href='createClassTeacher.php?Id=".$rows['Id']."&action=delete' class='btn btn-sm btn-danger' onclick='return confirm(\"Bạn có chắc muốn xóa giáo viên này?\")'>
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