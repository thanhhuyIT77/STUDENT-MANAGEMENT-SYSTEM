<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

if(isset($_POST['date']) && isset($_POST['classId']) && isset($_POST['classArmId'])) {
    $date = $_POST['date'];
    $classId = $_POST['classId'];
    $classArmId = $_POST['classArmId'];
    
    $cnt = 1;
    $ret = mysqli_query($conn, "SELECT tblattendance.Id,tblattendance.status,tblattendance.dateTimeTaken,tblclass.className,
        tblclassarms.classArmName,tblsessionterm.sessionName,tblsessionterm.termId,tblterm.termName,
        tblstudents.firstName,tblstudents.lastName,tblstudents.otherName,tblstudents.admissionNumber
        FROM tblattendance
        INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
        INNER JOIN tblclassarms ON tblclassarms.Id = tblattendance.classArmId
        INNER JOIN tblsessionterm ON tblsessionterm.Id = tblattendance.sessionTermId
        INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId
        INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo
        WHERE tblattendance.dateTimeTaken = '$date' 
        AND tblattendance.classId = '$classId' 
        AND tblattendance.classArmId = '$classArmId'
        ORDER BY tblstudents.firstName, tblstudents.lastName");
    
    if(mysqli_num_rows($ret) > 0) {
        while ($row = mysqli_fetch_array($ret)) {
            $status = ($row['status'] == '1') ? "Có mặt" : "Vắng";
            $statusClass = ($row['status'] == '1') ? "text-success" : "text-danger";
            
            echo '<tr>';
            echo '<td>' . $cnt . '</td>';
            echo '<td>' . $row['lastName'] . '</td>';
            echo '<td>' . $row['firstName'] . '</td>';
            echo '<td>' . $row['otherName'] . '</td>';
            echo '<td>' . $row['admissionNumber'] . '</td>';
            echo '<td>' . $row['className'] . '</td>';
            echo '<td>' . $row['classArmName'] . '</td>';
            echo '<td>' . $row['sessionName'] . '</td>';
            echo '<td>' . $row['termName'] . '</td>';
            echo '<td class="' . $statusClass . '"><strong>' . $status . '</strong></td>';
            echo '<td>' . date('d/m/Y', strtotime($row['dateTimeTaken'])) . '</td>';
            echo '</tr>';
            $cnt++;
        }
    } else {
        echo '<tr><td colspan="11" class="text-center text-muted">Không có dữ liệu điểm danh cho ngày ' . date('d/m/Y', strtotime($date)) . '</td></tr>';
    }
} else {
    echo '<tr><td colspan="11" class="text-center text-danger">Dữ liệu không hợp lệ</td></tr>';
}
?>
