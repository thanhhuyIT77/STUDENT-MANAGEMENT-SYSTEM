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

// Handle grade submission
if(isset($_POST['saveGrades'])){
    $sessionTermId = $_POST['sessionTermId'];
    $subjectId = $_POST['subjectId'];
    $studentIds = $_POST['studentId'];
    $assignmentScores = $_POST['assignmentScore'];
    $midtermScores = $_POST['midtermScore'];
    $finalScores = $_POST['finalScore'];
    $remarks = $_POST['remarks'];
    
    $successCount = 0;
    $errorCount = 0;
    
    for($i = 0; $i < count($studentIds); $i++) {
        $studentId = $studentIds[$i];
        $assignmentScore = $assignmentScores[$i];
        $midtermScore = $midtermScores[$i];
        $finalScore = $finalScores[$i];
        $remark = $remarks[$i];
        
        // Calculate average score
        $averageScore = 0;
        $scoreCount = 0;
        if($assignmentScore > 0) { $averageScore += $assignmentScore; $scoreCount++; }
        if($midtermScore > 0) { $averageScore += $midtermScore; $scoreCount++; }
        if($finalScore > 0) { $averageScore += $finalScore; $scoreCount++; }
        
        if($scoreCount > 0) {
            $averageScore = $averageScore / $scoreCount;
        }
        
        // Determine grade
        $grade = '';
        if($averageScore >= 8.5) $grade = 'A';
        elseif($averageScore >= 7.0) $grade = 'B';
        elseif($averageScore >= 5.5) $grade = 'C';
        elseif($averageScore >= 4.0) $grade = 'D';
        else $grade = 'F';
        
        // Check if grade already exists
        $checkQuery = mysqli_query($conn, "SELECT * FROM tblgrades WHERE studentId = '$studentId' AND subjectId = '$subjectId' AND sessionTermId = '$sessionTermId'");
        
        if(mysqli_num_rows($checkQuery) > 0) {
            // Update existing grade
            $updateQuery = mysqli_query($conn, "UPDATE tblgrades SET 
                assignmentScore = '$assignmentScore', 
                midtermScore = '$midtermScore', 
                finalScore = '$finalScore', 
                averageScore = '$averageScore', 
                grade = '$grade', 
                remarks = '$remark' 
                WHERE studentId = '$studentId' AND subjectId = '$subjectId' AND sessionTermId = '$sessionTermId'");
            
            if($updateQuery) {
                $successCount++;
            } else {
                $errorCount++;
            }
        } else {
            // Insert new grade
            $insertQuery = mysqli_query($conn, "INSERT INTO tblgrades(studentId, subjectId, sessionTermId, classId, assignmentScore, midtermScore, finalScore, averageScore, grade, remarks) 
                VALUES('$studentId', '$subjectId', '$sessionTermId', '$_SESSION[classId]', '$assignmentScore', '$midtermScore', '$finalScore', '$averageScore', '$grade', '$remark')");
            
            if($insertQuery) {
                $successCount++;
            } else {
                $errorCount++;
            }
        }
    }
    
    if($successCount > 0) {
        $statusMsg = "<div class='alert alert-success'>Cập nhật điểm thành công cho $successCount học sinh!</div>";
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
  <title>Quản lý điểm số</title>
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
            <h1 class="h3 mb-0 text-gray-800">Quản lý điểm số - Lớp (<?php echo isset($rrw['className']) ? $rrw['className'] : 'N/A'; ?>)</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Trang chủ</a></li>
              <li class="breadcrumb-item active" aria-current="page">Quản lý điểm số</li>
            </ol>
          </div>

          <?php echo $statusMsg; ?>

          <!-- Filter Form -->
          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Chọn môn học và học kỳ</h6>
                </div>
                <div class="card-body">
                  <form method="post" id="gradeForm">
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
                          <label for="subjectId">Môn học:</label>
                          <select class="form-control" name="subjectId" id="subjectId" required>
                            <option value="">Chọn môn học</option>
                            <?php
                            $query = "SELECT * FROM tblsubjects WHERE isActive = 1 ORDER BY subjectName";
                            $rs = $conn->query($query);
                            while ($row = $rs->fetch_assoc()) {
                              echo "<option value='".$row['Id']."'>".$row['subjectName']." (".$row['subjectCode'].")</option>";
                            }
                            ?>
                          </select>
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

          <!-- Students Grades Table -->
          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Nhập điểm học sinh</h6>
                </div>
                <div class="card-body">
                  <div id="studentsTable">
                    <div class="text-center text-muted">
                      <i class="fas fa-info-circle fa-2x mb-3"></i>
                      <p>Vui lòng chọn năm học, học kỳ và môn học để hiển thị danh sách học sinh</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- View Grades -->
          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Xem điểm số</h6>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="viewSessionTermId">Năm học & Học kỳ:</label>
                        <select class="form-control" id="viewSessionTermId">
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
                        <label for="viewSubjectId">Môn học:</label>
                        <select class="form-control" id="viewSubjectId">
                          <option value="">Chọn môn học</option>
                          <?php
                          $query = "SELECT * FROM tblsubjects WHERE isActive = 1 ORDER BY subjectName";
                          $rs = $conn->query($query);
                          while ($row = $rs->fetch_assoc()) {
                            echo "<option value='".$row['Id']."'>".$row['subjectName']." (".$row['subjectCode'].")</option>";
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-info btn-block" onclick="viewGrades()">
                          <i class="fas fa-eye"></i> Xem điểm
                        </button>
                      </div>
                    </div>
                  </div>
                  
                  <div id="gradesTable">
                    <!-- Grades will be loaded here -->
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
      var subjectId = $('#subjectId').val();
      
      if(!sessionTermId || !subjectId) {
        alert('Vui lòng chọn đầy đủ năm học, học kỳ và môn học!');
        return;
      }
      
      $.ajax({
        url: 'getStudentsForGrades.php',
        type: 'POST',
        data: {
          sessionTermId: sessionTermId,
          subjectId: subjectId,
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
    
    function viewGrades() {
      var sessionTermId = $('#viewSessionTermId').val();
      var subjectId = $('#viewSubjectId').val();
      
      if(!sessionTermId || !subjectId) {
        alert('Vui lòng chọn đầy đủ năm học, học kỳ và môn học!');
        return;
      }
      
      $.ajax({
        url: 'getGrades.php',
        type: 'POST',
        data: {
          sessionTermId: sessionTermId,
          subjectId: subjectId,
          classId: '<?php echo $_SESSION['classId']; ?>'
        },
        success: function(response) {
          $('#gradesTable').html(response);
        },
        error: function() {
          $('#gradesTable').html('<div class="alert alert-danger">Lỗi khi tải dữ liệu</div>');
        }
      });
    }
  </script>
</body>

</html>
