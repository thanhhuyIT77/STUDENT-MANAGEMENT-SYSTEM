<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

if(isset($_POST['sessionTermId']) && isset($_POST['subjectId']) && isset($_POST['classId'])) {
    $sessionTermId = $_POST['sessionTermId'];
    $subjectId = $_POST['subjectId'];
    $classId = $_POST['classId'];
    
    // Get students with their existing grades
    $query = "SELECT tblstudents.*, 
              COALESCE(tblgrades.assignmentScore, 0) as assignmentScore,
              COALESCE(tblgrades.midtermScore, 0) as midtermScore,
              COALESCE(tblgrades.finalScore, 0) as finalScore,
              COALESCE(tblgrades.averageScore, 0) as averageScore,
              COALESCE(tblgrades.grade, '') as grade,
              COALESCE(tblgrades.remarks, '') as remarks
              FROM tblstudents 
              LEFT JOIN tblgrades ON tblstudents.Id = tblgrades.studentId 
              AND tblgrades.subjectId = '$subjectId' 
              AND tblgrades.sessionTermId = '$sessionTermId'
              WHERE tblstudents.classId = '$classId'
              ORDER BY tblstudents.firstName, tblstudents.lastName";
    
    $rs = $conn->query($query);
    $num = $rs->num_rows;
    
    if($num > 0) {
        echo '<form method="post">';
        echo '<input type="hidden" name="sessionTermId" value="'.$sessionTermId.'">';
        echo '<input type="hidden" name="subjectId" value="'.$subjectId.'">';
        
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered table-striped">';
        echo '<thead class="thead-light">';
        echo '<tr>';
        echo '<th>#</th>';
        echo '<th>Họ và tên</th>';
        echo '<th>Mã sinh viên</th>';
        echo '<th>Điểm bài tập (30%)</th>';
        echo '<th>Điểm giữa kỳ (30%)</th>';
        echo '<th>Điểm cuối kỳ (40%)</th>';
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
            echo '<td>'.$row['firstName'].' '.$row['lastName'].' '.$row['otherName'].'</td>';
            echo '<td>'.$row['admissionNumber'].'</td>';
            echo '<td><input type="number" class="form-control" name="assignmentScore[]" value="'.$row['assignmentScore'].'" min="0" max="10" step="0.1"></td>';
            echo '<td><input type="number" class="form-control" name="midtermScore[]" value="'.$row['midtermScore'].'" min="0" max="10" step="0.1"></td>';
            echo '<td><input type="number" class="form-control" name="finalScore[]" value="'.$row['finalScore'].'" min="0" max="10" step="0.1"></td>';
            echo '<td><span class="font-weight-bold">'.$row['averageScore'].'</span></td>';
            echo '<td><span class="badge badge-'.getGradeColor($row['grade']).'">'.$row['grade'].'</span></td>';
            echo '<td><input type="text" class="form-control" name="remarks[]" value="'.$row['remarks'].'"></td>';
            echo '<input type="hidden" name="studentId[]" value="'.$row['Id'].'">';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        
        echo '<div class="text-center mt-3">';
        echo '<button type="submit" name="saveGrades" class="btn btn-success btn-lg">';
        echo '<i class="fas fa-save"></i> Lưu điểm số';
        echo '</button>';
        echo '</div>';
        echo '</form>';
        
    } else {
        echo '<div class="alert alert-warning">Không có học sinh nào trong lớp này!</div>';
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
