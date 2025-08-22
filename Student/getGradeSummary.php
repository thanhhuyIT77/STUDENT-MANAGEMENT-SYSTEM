<?php
error_reporting(0);
include '../Includes/dbcon.php';
include 'Includes/session.php';

if(isset($_POST['sessionTermId']) && isset($_POST['studentId'])) {
    $sessionTermId = $_POST['sessionTermId'];
    $studentId = $_POST['studentId'];
    
    // Get grade summary
    $query = "SELECT 
              COUNT(*) as totalSubjects,
              SUM(tblsubjects.credits) as totalCredits,
              AVG(tblgrades.averageScore) as overallAverage,
              COUNT(CASE WHEN tblgrades.grade = 'A' THEN 1 END) as gradeA,
              COUNT(CASE WHEN tblgrades.grade = 'B' THEN 1 END) as gradeB,
              COUNT(CASE WHEN tblgrades.grade = 'C' THEN 1 END) as gradeC,
              COUNT(CASE WHEN tblgrades.grade = 'D' THEN 1 END) as gradeD,
              COUNT(CASE WHEN tblgrades.grade = 'F' THEN 1 END) as gradeF
              FROM tblgrades 
              INNER JOIN tblsubjects ON tblsubjects.Id = tblgrades.subjectId
              WHERE tblgrades.studentId = '$studentId' 
              AND tblgrades.sessionTermId = '$sessionTermId'";
    
    $rs = $conn->query($query);
    $summary = $rs->fetch_assoc();
    
    if($summary['totalSubjects'] > 0) {
        $overallGrade = '';
        $overallAverage = $summary['overallAverage'];
        
        if($overallAverage >= 8.5) $overallGrade = 'A';
        elseif($overallAverage >= 7.0) $overallGrade = 'B';
        elseif($overallAverage >= 5.5) $overallGrade = 'C';
        elseif($overallAverage >= 4.0) $overallGrade = 'D';
        else $overallGrade = 'F';
        
        echo '<div class="row">';
        
        // Overall Statistics
        echo '<div class="col-md-6">';
        echo '<div class="card">';
        echo '<div class="card-header"><h6 class="mb-0">Thống kê tổng quan</h6></div>';
        echo '<div class="card-body">';
        echo '<p><strong>Tổng số môn học:</strong> '.$summary['totalSubjects'].'</p>';
        echo '<p><strong>Tổng số tín chỉ:</strong> '.$summary['totalCredits'].'</p>';
        echo '<p><strong>Điểm trung bình:</strong> <span class="font-weight-bold text-primary">'.number_format($overallAverage, 2).'</span></p>';
        echo '<p><strong>Xếp loại chung:</strong> <span class="badge badge-'.getGradeColor($overallGrade).'">'.$overallGrade.'</span></p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
        // Grade Distribution
        echo '<div class="col-md-6">';
        echo '<div class="card">';
        echo '<div class="card-header"><h6 class="mb-0">Phân bố xếp loại</h6></div>';
        echo '<div class="card-body">';
        
        $grades = array(
            'A' => $summary['gradeA'],
            'B' => $summary['gradeB'],
            'C' => $summary['gradeC'],
            'D' => $summary['gradeD'],
            'F' => $summary['gradeF']
        );
        
        foreach($grades as $grade => $count) {
            if($count > 0) {
                $percentage = round(($count / $summary['totalSubjects']) * 100, 1);
                echo '<div class="d-flex justify-content-between align-items-center mb-2">';
                echo '<span class="badge badge-'.getGradeColor($grade).'">'.$grade.'</span>';
                echo '<span>'.$count.' môn ('.$percentage.'%)</span>';
                echo '</div>';
                
                // Progress bar
                echo '<div class="progress mb-3" style="height: 8px;">';
                echo '<div class="progress-bar bg-'.getGradeColor($grade).'" style="width: '.$percentage.'%"></div>';
                echo '</div>';
            }
        }
        
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
        echo '</div>';
        
        // Performance Analysis
        echo '<div class="row mt-4">';
        echo '<div class="col-12">';
        echo '<div class="card">';
        echo '<div class="card-header"><h6 class="mb-0">Phân tích kết quả</h6></div>';
        echo '<div class="card-body">';
        
        if($overallAverage >= 8.0) {
            echo '<div class="alert alert-success">';
            echo '<i class="fas fa-star"></i> <strong>Xuất sắc!</strong> Bạn đã đạt kết quả rất tốt trong học kỳ này.';
            echo '</div>';
        } elseif($overallAverage >= 7.0) {
            echo '<div class="alert alert-info">';
            echo '<i class="fas fa-thumbs-up"></i> <strong>Tốt!</strong> Bạn đã đạt kết quả tốt, hãy tiếp tục phấn đấu.';
            echo '</div>';
        } elseif($overallAverage >= 5.5) {
            echo '<div class="alert alert-warning">';
            echo '<i class="fas fa-exclamation-triangle"></i> <strong>Trung bình!</strong> Bạn cần cố gắng hơn nữa để cải thiện kết quả.';
            echo '</div>';
        } else {
            echo '<div class="alert alert-danger">';
            echo '<i class="fas fa-exclamation-circle"></i> <strong>Cần cải thiện!</strong> Bạn cần học tập chăm chỉ hơn để đạt kết quả tốt hơn.';
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
    } else {
        echo '<div class="alert alert-warning">Chưa có dữ liệu điểm số để tính toán tổng kết!</div>';
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
