-- Database: Student Attendance System
-- Tạo database mới (nếu chưa có)
CREATE DATABASE IF NOT EXISTS `qlsinhvien2` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `qlsinhvien2`;

-- Bảng quản trị viên
CREATE TABLE IF NOT EXISTS `tbladmin` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `emailAddress` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng điểm danh
CREATE TABLE IF NOT EXISTS `tblattendance` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `admissionNo` varchar(255) NOT NULL,
  `classId` varchar(10) NOT NULL,
  `sessionTermId` varchar(10) NOT NULL,
  `status` varchar(10) NOT NULL,
  `dateTimeTaken` varchar(20) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng lớp học
CREATE TABLE IF NOT EXISTS `tblclass` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `className` varchar(255) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng giáo viên chủ nhiệm
CREATE TABLE IF NOT EXISTS `tblclassteacher` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `emailAddress` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phoneNo` varchar(50) NOT NULL,
  `classId` varchar(10) NOT NULL,
  `dateCreated` varchar(50) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng năm học và học kỳ
CREATE TABLE IF NOT EXISTS `tblsessionterm` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `sessionName` varchar(50) NOT NULL,
  `termId` varchar(50) NOT NULL,
  `isActive` varchar(10) NOT NULL,
  `dateCreated` varchar(50) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng sinh viên
CREATE TABLE IF NOT EXISTS `tblstudents` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `otherName` varchar(255) NOT NULL,
  `admissionNumber` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `classId` varchar(10) NOT NULL,
  `dateCreated` varchar(50) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `admissionNumber` (`admissionNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng môn học
CREATE TABLE IF NOT EXISTS `tblsubjects` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `subjectName` varchar(255) NOT NULL,
  `subjectCode` varchar(50) NOT NULL,
  `credits` int(11) DEFAULT 3,
  `isActive` tinyint(1) DEFAULT 1,
  `dateCreated` date DEFAULT CURRENT_DATE,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `subjectCode` (`subjectCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng phân công môn học cho lớp
CREATE TABLE IF NOT EXISTS `tblclasssubjects` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `classId` int(11) NOT NULL,
  `subjectId` int(11) NOT NULL,
  `teacherId` int(11) DEFAULT NULL,
  `isActive` tinyint(1) DEFAULT 1,
  `dateCreated` date DEFAULT CURRENT_DATE,
  PRIMARY KEY (`Id`),
  KEY `classId` (`classId`),
  KEY `subjectId` (`subjectId`),
  KEY `teacherId` (`teacherId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng điểm số sinh viên
CREATE TABLE IF NOT EXISTS `tblgrades` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `studentId` int(11) NOT NULL,
  `subjectId` int(11) NOT NULL,
  `sessionTermId` int(11) NOT NULL,
  `classId` int(11) NOT NULL,
  `assignmentScore` decimal(5,2) DEFAULT 0.00,
  `midtermScore` decimal(5,2) DEFAULT 0.00,
  `finalScore` decimal(5,2) DEFAULT 0.00,
  `averageScore` decimal(5,2) DEFAULT 0.00,
  `grade` varchar(2) DEFAULT NULL,
  `remarks` text,
  `dateCreated` date DEFAULT CURRENT_DATE,
  `dateUpdated` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `unique_grade` (`studentId`, `subjectId`, `sessionTermId`),
  KEY `studentId` (`studentId`),
  KEY `subjectId` (`subjectId`),
  KEY `sessionTermId` (`sessionTermId`),
  KEY `classId` (`classId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng học kỳ
CREATE TABLE IF NOT EXISTS `tblterm` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `termName` varchar(20) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm dữ liệu mẫu cho bảng admin
INSERT INTO `tbladmin` (`firstName`, `lastName`, `emailAddress`, `password`) VALUES
('Admin', '', 'admin@mail.com', 'D00F5D5217896FB7FD601412CB890830'),
('huy', '', 'admin10@gmail.com', 'e10adc3949ba59abbe56e057f20f883e'),
('CHUONG', '', 'chuong@gmail.com', '202cb962ac59075b964b07152d234b70');

-- Thêm dữ liệu mẫu cho bảng lớp học
INSERT INTO `tblclass` (`className`) VALUES
('CN22A'),
('CN22F'),
('CN22E'),
('CN22D'),
('CN22D4');

-- Thêm dữ liệu mẫu cho bảng học kỳ
INSERT INTO `tblterm` (`termName`) VALUES
('HK1'),
('HK2'),
('HKHE');

-- Thêm dữ liệu mẫu cho bảng năm học và học kỳ
INSERT INTO `tblsessionterm` (`sessionName`, `termId`, `isActive`, `dateCreated`) VALUES
('2025-2026', '1', '1', '2025-08-21'),
('2025-2026', '2', '0', '2025-08-21'),
('2024-2025', '1', '0', '2024-08-21'),
('2024-2025', '2', '0', '2024-08-21');

-- Thêm dữ liệu mẫu cho bảng giáo viên
INSERT INTO `tblclassteacher` (`firstName`, `lastName`, `emailAddress`, `password`, `phoneNo`, `classId`, `dateCreated`) VALUES
('Demola', 'Ade', 'teacher3@gmail.com', '32250170a0dca92d53ec9624f336ca24', '09672002882', '1', '2022-11-01'),
('Ryan', 'Mbeche', 'teacher4@mail.com', '32250170a0dca92d53ec9624f336ca24', '7014560000', '3', '2022-10-07'),
('John', 'Keroche', 'teacher@mail.com', '32250170a0dca92d53ec9624f336ca24', '0100000030', '4', '2022-10-07'),
('Huy', 'Nguyen', 'thanhhuypm77@gmail.com', '202cb962ac59075b964b07152d234b70', '0352839986', '1', '24-7-2025');

-- Thêm dữ liệu mẫu cho bảng sinh viên
INSERT INTO `tblstudents` (`firstName`, `lastName`, `otherName`, `admissionNumber`, `password`, `classId`, `dateCreated`) VALUES
('Lam Van', 'Chuong', 'none', '0352839986', '827ccb0eea8a706c4c34a16891f84e7b', '1', '2025-24-07'),
('Nguyen Minh', 'Truc', 'none', '0352839977', '12345', '4', '2025-07-24');

-- Thêm dữ liệu mẫu cho bảng môn học
INSERT INTO `tblsubjects` (`subjectName`, `subjectCode`, `credits`) VALUES
('Toán học', 'MATH101', 3),
('Vật lý', 'PHY101', 3),
('Hóa học', 'CHEM101', 3),
('Sinh học', 'BIO101', 3),
('Lịch sử', 'HIST101', 2),
('Địa lý', 'GEO101', 2),
('Văn học', 'LIT101', 2),
('Tiếng Anh', 'ENG101', 3),
('Tin học', 'CS101', 3),
('Giáo dục công dân', 'CIV101', 1);

-- Thêm dữ liệu mẫu cho bảng phân công môn học
INSERT INTO `tblclasssubjects` (`classId`, `subjectId`, `teacherId`) VALUES
(1, 1, 4), -- Toán học cho lớp CN22A
(1, 2, 4), -- Vật lý cho lớp CN22A
(1, 3, 4), -- Hóa học cho lớp CN22A
(4, 1, 6), -- Toán học cho lớp CN22E
(4, 2, 6), -- Vật lý cho lớp CN22E
(4, 3, 6); -- Hóa học cho lớp CN22E

-- Thêm dữ liệu mẫu cho bảng điểm danh
INSERT INTO `tblattendance` (`admissionNo`, `classId`, `sessionTermId`, `status`, `dateTimeTaken`) VALUES
('ASDFLKJ', '1', '1', '1', '2020-11-01'),
('HSKSDD', '1', '1', '1', '2020-11-01'),
('JSLDKJ', '1', '1', '1', '2020-11-01'),
('AMS110', '4', '1', '1', '2021-10-07'),
('AMS133', '4', '1', '0', '2021-10-07'),
('AMS135', '4', '1', '0', '2021-10-07'),
('AMS144', '4', '1', '1', '2021-10-07'),
('AMS148', '4', '1', '0', '2021-10-07'),
('AMS151', '4', '1', '1', '2021-10-07'),
('AMS159', '4', '1', '1', '2021-10-07'),
('AMS161', '4', '1', '1', '2021-10-07'),
('0352839977', '4', '1', '1', '2025-08-21');

-- Thêm dữ liệu mẫu cho bảng điểm số
INSERT INTO `tblgrades` (`studentId`, `subjectId`, `sessionTermId`, `classId`, `assignmentScore`, `midtermScore`, `finalScore`, `averageScore`, `grade`, `remarks`) VALUES
(1, 1, 1, 1, 8.5, 8.0, 8.8, 8.4, 'A', 'Học sinh xuất sắc'),
(1, 2, 1, 1, 7.5, 8.0, 7.8, 7.8, 'B', 'Cần cải thiện'),
(1, 3, 1, 1, 9.0, 9.0, 9.0, 9.0, 'A', 'Xuất sắc'),
(18, 1, 1, 4, 7.0, 7.5, 7.8, 7.4, 'B', 'Kết quả tốt'),
(18, 2, 1, 4, 6.5, 7.0, 7.2, 6.9, 'C', 'Cần cố gắng hơn');

-- Tạo các index để tối ưu hiệu suất truy vấn
CREATE INDEX idx_attendance_date ON tblattendance(dateTimeTaken);
CREATE INDEX idx_attendance_student ON tblattendance(admissionNo);
CREATE INDEX idx_grades_student ON tblgrades(studentId);
CREATE INDEX idx_grades_subject ON tblgrades(subjectId);
CREATE INDEX idx_students_class ON tblstudents(classId);
CREATE INDEX idx_teacher_class ON tblclassteacher(classId);

-- Cập nhật AUTO_INCREMENT cho các bảng
ALTER TABLE `tbladmin` AUTO_INCREMENT = 6;
ALTER TABLE `tblattendance` AUTO_INCREMENT = 213;
ALTER TABLE `tblclass` AUTO_INCREMENT = 8;
ALTER TABLE `tblclassteacher` AUTO_INCREMENT = 9;
ALTER TABLE `tblsessionterm` AUTO_INCREMENT = 5;
ALTER TABLE `tblstudents` AUTO_INCREMENT = 19;
ALTER TABLE `tblsubjects` AUTO_INCREMENT = 11;
ALTER TABLE `tblterm` AUTO_INCREMENT = 4;
ALTER TABLE `tblclasssubjects` AUTO_INCREMENT = 7;
ALTER TABLE `tblgrades` AUTO_INCREMENT = 6;
