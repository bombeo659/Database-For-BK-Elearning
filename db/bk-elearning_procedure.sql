-- bk-elearning store procedure
use bk_elearning;

-- Trả về thông tin admin
delimiter $$
create procedure GetAdminInfo (in ID integer) 
begin
    select * from admin_details
    where ad_id = ID;
end$$
delimiter ;

-- Thêm thông tin admin
delimiter $$
create procedure AddAdminInfo (image longblob, empid varchar(25), fname varchar(85),
								mname varchar(85), lname varchar(85), gender varchar(25),
                                email varchar(100), bday date, address varchar(255))
begin
	insert into admin_details values(image, empid, fname, mname, lname, gender,
									email, bday, address);
end$$
delimiter ;

-- Trả về thông tin giảng viên
delimiter $$
create procedure GetInstructorInfo (in ID integer) 
begin
    select * from instructor_details
    where ind_id = ID;
end$$
delimiter ;

-- Thêm thông tin giảng viên
delimiter $$
create procedure AddInstructorInfo (image longblob, empid varchar(25), fname varchar(85),
								mname varchar(85), lname varchar(85), gender varchar(25),
                                email varchar(100), bday date, address varchar(255))
begin
	insert into instructor_details values(image, empid, fname, mname, lname, gender,
									email, bday, address);
end$$
delimiter ;

-- Trả về thông tin sinh viên
delimiter $$
create procedure GetStudentInfo (in ID integer) 
begin
    select * from student_details
    where sd_id = ID;
end$$
delimiter ;

-- Thêm thông tin sinh viên
delimiter $$
create procedure AddStudentInfo (image longblob, studnum varchar(25), fname varchar(85),
								mname varchar(85), lname varchar(85), gender varchar(25),
                                email varchar(100), bday date, address varchar(255))
begin
	insert into student_details values(image, studnum, fname, mname, lname, gender,
									email, bday, address);
end$$
delimiter ;

-- Thêm học kì mới
delimiter $$
create procedure AddNewSemester (in semStart date, semEnd date, statusId integer)
begin
	insert into semester values(semStart, semEnd, statusId);
end$$
delimiter ;

-- Thêm môn học mới
delimiter $$
create procedure AddNewSubject (in sname varchar(85), semId integer, facultyId integer)
begin
	insert into subject values(sname, semId, facultyId);
end$$
delimiter ;

-- Thêm lớp học mới
delimiter $$
create procedure AddNewClass (in subjectId integer, statusId integer, indId integer, cname varchar(30))
begin
	insert into class values(subjectId, statusId, indId, cname);
end$$
delimiter ;

-- Thêm sinh viên vào lớp học
delimiter $$
create procedure AddStudentClass (in classId integer, sdId integer)
begin
	insert into class values(classId, sdId);
end$$
delimiter ;

-- Thêm các khoa và ngành mới
delimiter $$
create procedure AddNewFaculty (in falcutyName varchar(85))
begin
	insert into falcuty values(falcutyName);
end$$
delimiter ;

-- Thêm các module cho lớp học
delimiter $$
create procedure AddNewModule (in classId integer, title varchar(85))
begin
	insert into module values(classId, title);
end$$
delimiter ;

-- Thêm topic mới vào module
delimiter $$
create procedure AddNewTopic (in moduleId integer, title varchar(85))
begin
	insert into module_topic values(moduleId, title);
end$$
delimiter ;

-- Thêm subtopic mới vào module
delimiter $$
create procedure AddNewSubTopic (in topicId integer, title varchar(85), content text)
begin
	insert into module_subtopic values(topicId, title, content);
end$$
delimiter ;

-- Đính kèm tệp tài liệu cho lớp học
delimiter $$
create procedure AddAttachment (in classId integer, moduleId integer, at_name varchar(255),
								mime tinytext, at_data longblob, at_date timestamp)
begin
	insert into attachment values(classId, moduleId, at_name, mime, at_data, at_date);
end$$
delimiter ;

-- Thêm thông báo cho lớp học
delimiter $$
create procedure AddPost (in userId integer, classId integer, post_name varchar(85),
							post_description text, post_date timestamp)
begin
	insert into post values(userId, classId, post_name, post_description, post_date);
end$$
delimiter ;

-- Cập nhật thông báo
delimiter $$
create procedure UpdPost(in postId integer, userId integer, post_description text)
begin
	update post p
    set p.post_description=post_description where p.post_id=postId and p.user_id=userId;
end
$$
delimiter ;

-- Xóa thông báo
delimiter $$
create procedure DelPost(in postId integer, userId integer)
begin
	delete from post p where p.post_id=postId and p.user_id=userId;
end
$$
delimiter ;

-- Thêm bình luận vào thông báo trong lớp học
delimiter $$
create procedure AddComment (in userId integer, postId integer, content text, comment_date timestamp)
begin
	insert into post values(userId, postId, content, comment_date);
end$$
delimiter ;

-- Cập nhật bình luận
delimiter $$
create procedure UpdComment(in commentId integer, postId integer, userId integer, comment_content text)
begin
	update post_comment c
    set c.comment_content=comment_content where p.comment_id = comment_id 
								and p.postId=post_id and p.userId=user_id;
end
$$
delimiter ;

-- Xóa bình luận
delimiter $$
create procedure DelComment(in commentId integer, postId integer, userId integer)
begin
	delete from post_comment c where c.comment_id = c.commentId and c.post_id=postId and p.user_id=userId;
end
$$
delimiter ;

-- Thêm bài kiểm tra vào lớp học
delimiter $$
create procedure AddTest (in classId integer, ttId integer, statusId integer, test_name varchar(255),
							added timestamp, expired timestamp, timer varchar(3))
begin
	insert into test values(classId, ttId, statusId, test_name, added, expired, timer);
end$$
delimiter ;

-- Thêm câu hỏi cho bài kiểm tra
delimiter $$
create procedure AddQuestionToTest (in testId integer, question text, questionType integer)
begin
	insert into test_question values(testId, question, questionType);
end$$
delimiter ;

-- Thêm lựa chọn cho câu hỏi
delimiter $$
create procedure AddChoices (in questionId integer, isCorrect tinyint, choice text)
begin
	insert into question_choices values(questionId, isCorrect, choice);
end$$
delimiter ;

-- Chỉnh sửa lựa chọn cho câu hỏi
delimiter $$
create procedure UpdChoices (in choiceId integer, isCorrect tinyint)
begin
	update question_choices qc
	set qc.is_correct = isCorrect where qc.choice_id = choiceId;
end$$
delimiter ;











