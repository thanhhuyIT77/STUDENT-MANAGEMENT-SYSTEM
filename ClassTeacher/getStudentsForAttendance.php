<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

if(isset($_POST['sessionTermId']) && isset($_POST['dateTimeTaken']) && isset($_POST['classId'])) {
    $sessionTermId = $_POST['sessionTermId'];
    $dateTimeTaken = $_POST['dateTimeTaken'];
    $classId = $_POST['classId'];
    
    // Get students with their existing attendance status
    $query = "SELECT tblstudents.*, 
              COALESCE(tblattendance.status, '0') as attendanceStatus
              FROM tblstudents 
              LEFT JOIN tblattendance ON tblstudents.admissionNumber = tblattendance.admissionNo 
              AND tblattendance.dateTimeTaken = '$dateTimeTaken'
              WHERE tblstudents.classId = '$classId'
              ORDER BY tblstudents.firstName, tblstudents.lastName";
    
    $rs = $conn->query($query);
    $num = $rs->num_rows;
    
    if($num > 0) {
        echo '<form method="post">';
        echo '<input type="hidden" name="sessionTermId" value="'.$sessionTermId.'">';
        echo '<input type="hidden" name="dateTimeTaken" value="'.$dateTimeTaken.'">';
        
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered table-striped">';
        echo '<thead class="thead-light">';
        echo '<tr>';
        echo '<th>#</th>';
        echo '<th>Họ và tên</th>';
        echo '<th>Mã sinh viên</th>';
        echo '<th>Lớp</th>';
        echo '<th>Trạng thái điểm danh</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        $sn = 0;
        while ($row = $rs->fetch_assoc()) {
            $sn++;
            $status = $row['attendanceStatus'];
            $statusText = ($status == '1') ? 'Có mặt' : 'Vắng';
            $statusClass = ($status == '1') ? 'text-success' : 'text-danger';
            
            echo '<tr>';
            echo '<td>'.$sn.'</td>';
            echo '<td>'.$row['firstName'].' '.$row['lastName'].' '.$row['otherName'].'</td>';
            echo '<td>'.$row['admissionNumber'].'</td>';
            echo '<td>Lớp '.$classId.'</td>';
            echo '<td>';
            echo '<select class="form-control" name="status[]">';
            echo '<option value="0" '.($status == '0' ? 'selected' : '').'>Vắng</option>';
            echo '<option value="1" '.($status == '1' ? 'selected' : '').'>Có mặt</option>';
            echo '</select>';
            echo '<input type="hidden" name="studentId[]" value="'.$row['admissionNumber'].'">';
            echo '</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        
        echo '<div class="text-center mt-3">';
        echo '<button type="submit" name="save" class="btn btn-success btn-lg">';
        echo '<i class="fas fa-save"></i> Lưu điểm danh';
        echo '</button>';
        echo '</div>';
        echo '</form>';
        
    } else {
        echo '<div class="alert alert-warning">Không có học sinh nào trong lớp này!</div>';
    }
} else {
    echo '<div class="alert alert-danger">Dữ liệu không hợp lệ!</div>';
}
?>
