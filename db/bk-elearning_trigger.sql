-- bk-elearning trigger
use bk_elearning;

-- Sinh viên phải là người đủ 18 tuổi trở lên
delimiter $$
create trigger check_student_age
before insert on student_details
for each row
begin
    if date_format(from_days(datediff(now(),new.sd_bday)), '%Y') < 18 THEN
	signal sqlstate '45000'
	set message_text = 'ERROR: 
			 AGE MUST BE ATLEAST 18 YEARS!';
	end if;
end$$
delimiter ;

-- Giảng viên ít nhất phải là người tốt nghiệp thạc sĩ nên độ tuổi thấp nhất là 24 tuổi
delimiter $$
create trigger check_instructor_age
before insert on instructor_details
for each row
begin
    if date_format(from_days(datediff(now(),new.ind_bday)), '%Y') < 24 THEN
	signal sqlstate '45000'
	set message_text = 'ERROR: 
			 AGE MUST BE ATLEAST 24 YEARS!';
	end if;
end$$
delimiter ;

-- Quản trị viên hệ thống là người có trình độ đại học trở lên nên phải có số tuổi >= 22
delimiter $$
create trigger check_admin_age
before insert on admin_details
for each row
begin
    if date_format(from_days(datediff(now(),new.ad_bday)), '%Y') < 22 THEN
	signal sqlstate '45000'
	set message_text = 'ERROR: 
			 AGE MUST BE ATLEAST 22 YEARS!';
	end if;
end$$
delimiter ;

-- Số lượng sinh viên tối đa mỗi lớp là 100
delimiter $$
create trigger check_number_of_students
before insert on class_student
for each row
begin
    if CountStudentsofClass(new.class_id) > 100
    then
		signal sqlstate '45000'
		set message_text = 'CAN NOT ADD MORE STUDENTS TO THIS CLASS';
	end if;
end$$
delimiter ;

-- Học kì được thêm vào phải có thời gian bắt đầu từ thời gian hiện tại
delimiter $$
create trigger check_semester
before insert on semester
for each row
begin
    if date_format(from_days(datediff(now(),new.sem_start)), '%Y') < 0 
	THEN
	signal sqlstate '45000'
	set message_text = 'ERROR: 
			 SEMESTER MUST BE STARTED IN CURRENT TIME!';
	end if;
end$$
delimiter ;

-- Sinh viên chỉ học các lớp đã được kích hoạt
delimiter $$
create trigger check_status_of_class
before insert on class_student
for each row
begin
    if (select status_id from class where class_id = new.class_id) = 2
    then
		signal sqlstate '45000'
		set message_text = 'CAN NOT ADD STUDENT TO THIS CLASS';
	end if;
end$$
delimiter ;

-- Sinh viên chỉ có thể viết thông báo trong lớp mà mình tham gia
delimiter $$
create trigger check_student_post_in_class
before insert on post
for each row
begin
	declare student_id integer;
	set student_id = (select sd_id from students_has_account where user_id = new.user_id);
    if (new.class_id and student_id not in (select sd_id, class_id from class_student))
    then
		signal sqlstate '45000'
		set message_text = 'CAN NOT POST IN THIS CLASS';
	end if;
end$$
delimiter ; 

-- Sinh viên chỉ có thể cập nhật thông báo của mình
delimiter $$
create trigger check_student_upd_post
before update on post
for each row
begin
	declare student_id integer;
    set student_id = (select sd_id from students_has_account where user_id = new.user_id);
    if (new.class_id and student_id not in (select sd_id, class_id from class_student))
    then
		signal sqlstate '45000'
		set message_text = 'CAN NOT UPDATE POST IN THIS CLASS';
	end if;
end$$
delimiter ;

-- Sinh viên chỉ có thể xóa thông báo của mình
delimiter $$
create trigger check_student_del_post
before delete on post
for each row
begin
	declare student_id integer;
    set student_id = (select sd_id from students_has_account where user_id = old.user_id);
    if (old.class_id and student_id not in (select sd_id, class_id from class_student))
    then
		signal sqlstate '45000'
		set message_text = 'CAN NOT DELETE POST IN THIS CLASS';
	end if;
end$$
delimiter ;

-- Sinh viên chỉ có thể cập nhật các bình luận của mình
delimiter $$
create trigger check_student_upd_comment
before update on post_comment
for each row
begin
	declare student_id integer;
    set student_id = (select sd_id from students_has_account where user_id = new.user_id);
    if (student_id = null)
    then
		signal sqlstate '45000'
		set message_text = 'CAN NOT UPDATE COMMENT IN THIS POST';
	end if;
end$$
delimiter ;

-- Sinh viên chỉ có thể xóa các bình luận của mình
delimiter $$
create trigger check_student_del_comment
before delete on post_comment
for each row
begin
	declare student_id integer;
    set student_id = (select sd_id from students_has_account where user_id = old.user_id);
    if (student_id = null)
    then
		signal sqlstate '45000'
		set message_text = 'CAN NOT DELETE COMMENT IN THIS POST';
	end if;
end$$
delimiter ;

-- Giảng viên chỉ có thể thông báo vào các lớp mình đang giảng dạy
delimiter $$
create trigger check_instructor_post_in_class
before insert on post
for each row
begin
	declare instructor_id integer;
	set instructor_id = (select ind_id from instructors_has_account where user_id = new.user_id);
    if (new.class_id not in (select class_id from class where ind_id = instructor_id))
    then
		signal sqlstate '45000'
		set message_text = 'CAN NOT POST IN THIS CLASS';
	end if;
end$$
delimiter ;

-- Giảng viên chỉ có thể cập nhật thông báo của mình
delimiter $$
create trigger check_instructor_upd_post
before update on post
for each row
begin
	declare instructor_id integer;
    set instructor_id = (select ind_id from instructors_has_account where user_id = new.user_id);
    if (new.class_id not in (select class_id from class where ind_id = instructor_id))
    then
		signal sqlstate '45000'
		set message_text = 'CAN NOT UPDATE POST IN THIS CLASS';
	end if;
end$$
delimiter ;

-- Giảng viên chỉ có thể xóa thông báo của mình
delimiter $$
create trigger check_instructor_del_post
before delete on post
for each row
begin
	declare instructor_id integer;
    set instructor_id = (select ind_id from instructors_has_account where user_id = old.user_id);
    if (old.class_id not in (select class_id from class where ind_id = instructor_id))
    then
		signal sqlstate '45000'
		set message_text = 'CAN NOT DELETE POST IN THIS CLASS';
	end if;
end$$
delimiter ;

-- Giảng viên chỉ có thể cập nhật các bình luận của mình
delimiter $$
create trigger check_instructor_upd_comment
before update on post_comment
for each row
begin
	declare instructor_id integer;
    set instructor_id = (select ind_id from instructors_has_account where user_id = new.user_id);
    if (instructor_id = null)
    then
		signal sqlstate '45000'
		set message_text = 'CAN NOT UPDATE COMMENT IN THIS POST';
	end if;
end$$
delimiter ;

-- Giảng viên chỉ có thể xóa các bình luận của mình
delimiter $$
create trigger check_instructor_del_comment
before delete on post_comment
for each row
begin
	declare instructor_id integer;
    set instructor_id = (select ind_id from instructors_has_account where user_id = old.user_id);
    if (instructor_id = null)
    then
		signal sqlstate '45000'
		set message_text = 'CAN NOT DELETE COMMENT IN THIS POST';
	end if;
end$$
delimiter ;

-- Giảng viên chỉ có thể thêm module vào lớp đã được kích hoạt
delimiter $$
create trigger check_module_in_class
before insert on module
for each row
begin
    if ((select status_id from class where class_id = new.class_id) = 2)
    then
		signal sqlstate '45000'
		set message_text = 'CAN NOT ADD MODULE TO THIS CLASS';
	end if;
end$$
delimiter ;

-- Giảng viên chỉ có thể thêm topic vào lớp đã được kích hoạt
delimiter $$
create trigger check_topic_in_module
before insert on module_topic
for each row
begin
    if ((select status_id from class 
				where class_id in (select class_id 
									from module 
                                    where module_id = new.module_id) = 2))
    then
		signal sqlstate '45000'
		set message_text = 'CAN NOT ADD TOPIC TO THIS MODULE';
	end if;
end$$
delimiter ;

-- Giảng viên chỉ có thể thêm tài liệu vào các lớp đã kích hoạt
delimiter $$
create trigger check_attachment_in_class
before insert on attachment
for each row
begin
    if ((select status_id from class where class_id = new.class_id) = 2)
    then
		signal sqlstate '45000'
		set message_text = 'CAN NOT ADD FILE TO THIS CLASS';
	end if;
end$$
delimiter ;

-- Bài kiểm tra được thêm vào các lớp được kích hoạt
delimiter $$
create trigger check_test_in_class
before insert on test
for each row
begin
    if ((select status_id from class where class_id = new.class_id) = 2)
    then
		signal sqlstate '45000'
		set message_text = 'CAN NOT ADD TEST TO THIS CLASS';
	end if;
end$$
delimiter ;

-- Bài kiểm tra được thêm vào phải có thời hạn ít nhất là 1h
delimiter $$
create trigger check_test_date_in_class
before insert on test
for each row
begin
    if (timestampdiff(hour, new.test_added, new.test_expired) < 1)
    then
		signal sqlstate '45000'
		set message_text = 'CAN NOT ADD TEST TO THIS CLASS';
	end if;
end$$
delimiter ;




