<?php
error_reporting(0);
include '../Includes/dbcon.php';
include 'Includes/session.php';

if(isset($_POST['sessionTermId']) && isset($_POST['studentId'])) {
    $sessionTermId = $_POST['sessionTermId'];
    $studentId = $_POST['studentId'];
    
    // Get grades with subject information
    $query = "SELECT tblgrades.*, 
              tblsubjects.subjectName, tblsubjects.subjectCode, tblsubjects.credits,
              tblsessionterm.sessionName, tblterm.termName
              FROM tblgrades 
              INNER JOIN tblsubjects ON tblsubjects.Id = tblgrades.subjectId
              INNER JOIN tblsessionterm ON tblsessionterm.Id = tblgrades.sessionTermId
              INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId
              WHERE tblgrades.studentId = '$studentId' 
              AND tblgrades.sessionTermId = '$sessionTermId'
              ORDER BY tblsubjects.subjectName";
    
    $rs = $conn->query($query);
    $num = $rs->num_rows;
    
    if($num > 0) {
        // Get session info for header
        $headerQuery = "SELECT tblsessionterm.sessionName, tblterm.termName
                       FROM tblsessionterm 
                       INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId
                       WHERE tblsessionterm.Id = '$sessionTermId'";
        $headerRs = $conn->query($headerQuery);
        $headerInfo = $headerRs->fetch_assoc();
        
        echo '<div class="alert alert-info">';
        echo '<strong>Năm học:</strong> '.$headerInfo['sessionName'].' | ';
        echo '<strong>Học kỳ:</strong> '.$headerInfo['termName'];
        echo '</div>';
        
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered table-striped">';
        echo '<thead class="thead-light">';
        echo '<tr>';
        echo '<th>#</th>';
        echo '<th>Môn học</th>';
        echo '<th>Mã môn học</th>';
        echo '<th>Số tín chỉ</th>';
        echo '<th>Điểm bài tập</th>';
        echo '<th>Điểm giữa kỳ</th>';
        echo '<th>Điểm cuối kỳ</th>';
        echo '<th>Điểm trung bình</th>';
        echo '<th>Xếp loại</th>';
        echo '<th>Ghi chú</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        $sn = 0;
        while ($row = $rs->fetch_assoc()) {
            $sn++;
            echo '<tr>';
            echo '<td>'.$sn.'</td>';
            echo '<td>'.$row['subjectName'].'</td>';
            echo '<td>'.$row['subjectCode'].'</td>';
            echo '<td class="text-center">'.$row['credits'].'</td>';
            echo '<td class="text-center">'.$row['assignmentScore'].'</td>';
            echo '<td class="text-center">'.$row['midtermScore'].'</td>';
            echo '<td class="text-center">'.$row['finalScore'].'</td>';
            echo '<td class="text-center font-weight-bold">'.$row['averageScore'].'</td>';
            echo '<td class="text-center"><span class="badge badge-'.getGradeColor($row['grade']).'">'.$row['grade'].'</span></td>';
            echo '<td>'.$row['remarks'].'</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        
    } else {
        echo '<div class="alert alert-warning">Chưa có điểm số cho học kỳ này!</div>';
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
