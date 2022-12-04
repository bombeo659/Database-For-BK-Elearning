-- bk-elearning function
use bk_elearning;

-- Tính số lượng sinh viên của lớp
delimiter $$
create function CountStudentsofClass(classId integer)
returns integer
DETERMINISTIC
READS SQL DATA
begin
	declare sNumber integer;
    set sNumber=(select count(*) from class_student c where c.class_id=classId);
    return sNumber;
end $$
delimiter ;

-- Tính số lượng giảng viên tham gia giảng dạy của môn học
delimiter $$
create function CountIndsofSubject(subjectId integer)
returns integer
DETERMINISTIC
READS SQL DATA
begin
	declare indNumber integer;
    set indNumber=(select count(ind_id) from class c where c.subject_id=subjectId);
    return indNumber;
end$$
delimiter ;

-- Tính điểm trung bình của bài kiểm tra 
delimiter $$
create function CountAverageScore(testId integer, userId integer)
returns integer
DETERMINISTIC
READS SQL DATA
begin
	declare AvgScore integer;
    set AvgScore=(select Avg(score) from test_score t where t.test_id=testId and t.user_id = userId);
    return indNumber;
end$$
delimiter ;

-- Trả về số lượng sinh viên có số điểm trung bình bài kiểm tra cao hơn so với số điểm cho trước
delimiter $$
create function CountNumberPass(testId integer, userId integer)
returns integer
DETERMINISTIC
READS SQL DATA
begin
	declare cnt integer;
    select count(user_id) into cnt
	from test_score
	where user_id = userId and test_id = userId and 
		score > (select avg(score) from test_score t where t.test_id=userId and t.user_id = userId);
    return cnt;
end$$
delimiter ;

-- Tính số lượng sinh viên đã có được cấp tài khoản
delimiter $$
create function CountStudentsHasAccounts()
returns integer
DETERMINISTIC
READS SQL DATA
begin
	declare sNumber integer;
    set sNumber=(select count(*) from students_has_account);
    return sNumber;
end$$
delimiter ;

-- Tính số lượng giảng viên đã có được cấp tài khoản
delimiter $$
create function CountInstructorsHasAccounts()
returns integer
DETERMINISTIC
READS SQL DATA
begin
	declare sNumber integer;
    set sNumber=(select count(*) from instructors_has_account);
    return sNumber;
end$$
delimiter ;

-- Tính số lượng admin đã có được cấp tài khoản
delimiter $$
create function CountAdminsHasAccounts()
returns integer
DETERMINISTIC
READS SQL DATA
begin
	declare sNumber integer;
    set sNumber=(select count(*) from admins_has_account);
    return sNumber;
end$$
delimiter ;

-- Tính số lớp học của mỗi sinh viên
delimiter $$
create function CountSubjectOfStudent(studentId integer)
returns integer
DETERMINISTIC
READS SQL DATA
begin
	declare sNumber integer;
    set sNumber=(select count(*) from class_student c where c.sd_id = studentId);
    return sNumber;
end$$
delimiter ;






