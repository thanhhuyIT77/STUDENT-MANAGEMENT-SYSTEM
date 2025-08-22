<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

if(isset($_POST['sessionTermId']) && isset($_POST['subjectId']) && isset($_POST['classId'])) {
    $sessionTermId = $_POST['sessionTermId'];
    $subjectId = $_POST['subjectId'];
    $classId = $_POST['classId'];
    
    // Get grades with student and subject information
    $query = "SELECT tblgrades.*, 
              tblstudents.firstName, tblstudents.lastName, tblstudents.otherName, tblstudents.admissionNumber,
              tblsubjects.subjectName, tblsubjects.subjectCode,
              tblsessionterm.sessionName, tblterm.termName
              FROM tblgrades 
              INNER JOIN tblstudents ON tblstudents.Id = tblgrades.studentId
              INNER JOIN tblsubjects ON tblsubjects.Id = tblgrades.subjectId
              INNER JOIN tblsessionterm ON tblsessionterm.Id = tblgrades.sessionTermId
              INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId
              WHERE tblgrades.sessionTermId = '$sessionTermId' 
              AND tblgrades.subjectId = '$subjectId'
              AND tblgrades.classId = '$classId'
              ORDER BY tblstudents.firstName, tblstudents.lastName";
    
    $rs = $conn->query($query);
    $num = $rs->num_rows;
    
    if($num > 0) {
        // Get subject and session info for header
        $headerQuery = "SELECT tblsubjects.subjectName, tblsubjects.subjectCode,
                       tblsessionterm.sessionName, tblterm.termName
                       FROM tblsubjects 
                       INNER JOIN tblsessionterm ON 1=1
                       INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId
                       WHERE tblsubjects.Id = '$subjectId' AND tblsessionterm.Id = '$sessionTermId'";
        $headerRs = $conn->query($headerQuery);
        $headerInfo = $headerRs->fetch_assoc();
        
        echo '<div class="alert alert-info">';
        echo '<strong>Môn học:</strong> '.$headerInfo['subjectName'].' ('.$headerInfo['subjectCode'].') | ';
        echo '<strong>Năm học:</strong> '.$headerInfo['sessionName'].' | ';
        echo '<strong>Học kỳ:</strong> '.$headerInfo['termName'];
        echo '</div>';
        
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered table-striped">';
        echo '<thead class="thead-light">';
        echo '<tr>';
        echo '<th>#</th>';
        echo '<th>Họ và tên</th>';
        echo '<th>Mã sinh viên</th>';
        echo '<th>Điểm bài tập</th>';
        echo '<th>Điểm giữa kỳ</th>';
        echo '<th>Điểm cuối kỳ</th>';
        echo '<th>Điểm trung bình</th>';
        echo '<th>Xếp loại</th>';
        echo '<th>Ghi chú</th>';
        echo '<th>Ngày cập nhật</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        $sn = 0;
        $totalStudents = 0;
        $totalAverage = 0;
        $gradeCounts = array('A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'F' => 0);
        
        while ($row = $rs->fetch_assoc()) {
            $sn++;
            $totalStudents++;
            $totalAverage += $row['averageScore'];
            if(isset($gradeCounts[$row['grade']])) {
                $gradeCounts[$row['grade']]++;
            }
            
            echo '<tr>';
            echo '<td>'.$sn.'</td>';
            echo '<td>'.$row['firstName'].' '.$row['lastName'].' '.$row['otherName'].'</td>';
            echo '<td>'.$row['admissionNumber'].'</td>';
            echo '<td class="text-center">'.$row['assignmentScore'].'</td>';
            echo '<td class="text-center">'.$row['midtermScore'].'</td>';
            echo '<td class="text-center">'.$row['finalScore'].'</td>';
            echo '<td class="text-center font-weight-bold">'.$row['averageScore'].'</td>';
            echo '<td class="text-center"><span class="badge badge-'.getGradeColor($row['grade']).'">'.$row['grade'].'</span></td>';
            echo '<td>'.$row['remarks'].'</td>';
            echo '<td class="text-center">'.date('d/m/Y', strtotime($row['dateUpdated'])).'</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        
        // Statistics
        $classAverage = $totalStudents > 0 ? round($totalAverage / $totalStudents, 2) : 0;
        
        echo '<div class="row mt-4">';
        echo '<div class="col-md-6">';
        echo '<div class="card">';
        echo '<div class="card-header"><h6 class="mb-0">Thống kê tổng quan</h6></div>';
        echo '<div class="card-body">';
        echo '<p><strong>Tổng số học sinh:</strong> '.$totalStudents.'</p>';
        echo '<p><strong>Điểm trung bình lớp:</strong> '.$classAverage.'</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
        echo '<div class="col-md-6">';
        echo '<div class="card">';
        echo '<div class="card-header"><h6 class="mb-0">Phân bố xếp loại</h6></div>';
        echo '<div class="card-body">';
        foreach($gradeCounts as $grade => $count) {
            if($count > 0) {
                $percentage = $totalStudents > 0 ? round(($count / $totalStudents) * 100, 1) : 0;
                echo '<p><span class="badge badge-'.getGradeColor($grade).'">'.$grade.'</span>: '.$count.' học sinh ('.$percentage.'%)</p>';
            }
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
    } else {
        echo '<div class="alert alert-warning">Chưa có điểm số cho môn học và học kỳ này!</div>';
    }
} else {
    echo '<div class="alert alert-danger">Dữ liệu không hợp lệ!</div>';
}

function getGradeColor($grade) {
    switch($grade) {
        case 'A': return 'success';
        case 'B': return 'info';
        case 'C': return 'warning';
        case 'D': return 'secondary';
        case 'F': return 'danger';
        default: return 'light';
    }
}
?>
