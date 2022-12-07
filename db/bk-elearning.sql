drop schema if exists bk_elearning;
create schema bk_elearning;
use bk_elearning;

create table user_level (
	lvl_id integer not null auto_increment,
    lvl_name varchar(20),
    constraint ul_pk primary key (lvl_id) 
);

create table user_account( 
	user_id integer not null auto_increment,
    lvl_id integer not null, 
    user_img longblob,
    user_name varchar(85),
    user_pass varchar(255),
    user_registered timestamp default CURRENT_TIMESTAMP,
    constraint ua_pk primary key (user_id),
    constraint ua_fk foreign key (lvl_id) references user_level(lvl_id) 
);

create table student_details( 
	sd_id integer not null auto_increment, 
    sd_img longblob,
    sd_studnum varchar(25),
    sd_fname varchar(85),
    sd_mname varchar(85),
    sd_lname varchar(85),
    sd_gender varchar(25) default ('Male' or 'Female'),
    sd_email varchar(100),
    sd_bday date,
    sd_address varchar(255),
    constraint sd_pk primary key (sd_id)
);

create table students_has_account( 
	user_id integer not null,
    sd_id integer not null,
    constraint sha_pk primary key (user_id, sd_id),
    constraint sha_fk1 foreign key (user_id) references user_account(user_id),
    constraint sha_fk2 foreign key (sd_id) references student_details(sd_id)
);

create table instructor_details( 
	ind_id integer not null auto_increment, 
    ind_img longblob,
    ind_empid varchar(25),
    ind_fname varchar(85),
    ind_mname varchar(85),
    ind_lname varchar(85),
    ind_gender varchar(25) default ('Male' or 'Female'),
    ind_email varchar(100),
    ind_bday date,
    ind_address varchar(255),
    constraint ind_pk primary key (ind_id)
);

create table instructors_has_account( 
	user_id integer not null,
    ind_id integer not null,
    constraint iha_pk primary key (user_id, ind_id),
    constraint iha_fk1 foreign key (user_id) references user_account(user_id),
    constraint iha_fk2 foreign key (ind_id) references instructor_details(ind_id)
);

create table admin_details( 
	ad_id integer not null auto_increment, 
    ad_img longblob,
    ad_empid varchar(25),
    ad_fname varchar(85),
    ad_mname varchar(85),
    ad_lname varchar(85),
    ad_gender varchar(25) default ('Male' or 'Female' or 'Other'),
    ad_email varchar(100),
    ad_bday date,
    ad_address varchar(255),
    constraint ad_pk primary key (ad_id)
);

create table admins_has_account( 
	user_id integer not null,
    ad_id integer not null,
    constraint aha_pk primary key (user_id, ad_id),
    constraint aha_fk1 foreign key (user_id) references user_account(user_id),
    constraint aha_fk2 foreign key (ad_id) references admin_details(ad_id)
);

create table faculty( 
	faculty_id integer not null auto_increment,
    faculty_name varchar(85),
    constraint f_pk primary key (faculty_id)
);

create table status (
	status_id integer not null auto_increment,
    status varchar(25),
    constraint st_pk primary key (status_id)
);

create table semester( 
	sem_id integer not null auto_increment,
    sem_start date, 
    sem_end date, 
    status_id integer not null,
    constraint sm_pk primary key (sem_id),
    constraint sm_fk foreign key (status_id) references status(status_id)
);

create table subject( 
	subject_id integer not null auto_increment, 
    subject_name varchar(85),
    sem_id integer not null,
    faculty_id integer not null,
    constraint s_pk primary key (subject_id),
    constraint s_fk1 foreign key (sem_id) references semester(sem_id),
    constraint s_fk2 foreign key (faculty_id) references faculty(faculty_id)
);

create table class( 
	class_id integer not null auto_increment,
    subject_id integer not null,
    status_id integer not null,
	constraint c_pk primary key (class_id),
    constraint c_fk1 foreign key (status_id) references status(status_id),
    constraint c_fk2 foreign key (subject_id) references subject(subject_id) on delete cascade on update cascade
);

create table class_student( 
	class_id integer not null,
    sd_id integer not null,
    constraint cs_pk primary key (class_id, sd_id),
    constraint cs_fk1 foreign key (class_id) references class(class_id) on delete cascade on update cascade,
    constraint cs_fk2 foreign key (sd_id) references student_details(sd_id) on delete cascade on update cascade
);

create table post( 
	post_id integer not null auto_increment,
    user_id integer not null,
    class_id integer not null,
    post_name varchar(85),
    post_description text,
    post_date timestamp,
    constraint post_pk primary key (post_id),
    constraint post_fk1 foreign key (class_id) references class(class_id) on delete cascade on update cascade,
    constraint post_fk2 foreign key (user_id) references user_account(user_id) on delete cascade on update cascade
);

create table post_comment( 
	comment_id integer not null auto_increment,
    user_id integer not null,
    post_id integer not null,
    comment_content text,
    comment_date timestamp,
    constraint pc_pk primary key (comment_id),
    constraint pc_fk1 foreign key (post_id) references post(post_id) on delete cascade on update cascade,
    constraint pc_fk2 foreign key (user_id) references user_account(user_id) on delete cascade on update cascade
);

create table module(
	module_id integer not null auto_increment,
    class_id integer not null,
    module_title varchar(85),
    constraint module_pk primary key (module_id),
    constraint module_fk1 foreign key (class_id) references class(class_id) on delete cascade on update cascade
);

create table module_topic(
	topic_id integer not null auto_increment,
    module_id integer not null,
    topic_title varchar(85),
    constraint mt_pk primary key (topic_id),
    constraint mt_fk1 foreign key (module_id) references module(module_id) on delete cascade on update cascade
);

create table module_subtopic(
	subtopic_id integer not null auto_increment,
    topic_id integer not null,
    subtopic_title varchar(85),
    subtopic_content text,
    constraint mst_pk primary key (subtopic_id),
    constraint mst_fk1 foreign key (topic_id) references module_topic(topic_id) on delete cascade on update cascade
);

create table attachment( 
	attachment_id integer not null auto_increment,
	class_id integer not null,
    module_id integer not null,
    attachment_name varchar(255),
    attachment_mime tinytext,
    attachment_data longblob,
    attachment_date timestamp,
    constraint att_pk primary key (attachment_id),
    constraint att_fk1 foreign key (class_id) references class(class_id) on delete cascade on update cascade,
    constraint att_fk2 foreign key (module_id) references module(module_id) on delete cascade on update cascade
);

create table test_type(
	tt_id integer not null auto_increment,
    tt_name varchar(25),
    constraint tt_pk primary key (tt_id)
);

create table test( 
	test_id integer not null auto_increment,
    class_id integer not null,
    tt_id integer not null,
    status_id integer not null,
    test_name varchar(255),
    test_added timestamp,
    test_expired timestamp,
    test_timer varchar(3),
    constraint test_pk primary key (test_id),
    constraint test_fk1 foreign key (class_id) references class(class_id) on delete cascade on update cascade,
    constraint test_fk2 foreign key (tt_id) references test_type(tt_id),
    constraint test_fk3 foreign key (status_id) references status(status_id)
);

create table test_question( 
	question_id integer not null auto_increment, 
    test_id integer not null, 
    question text,
    question_type tinyint,
    constraint tq_pk primary key (question_id),
    constraint tq_fk foreign key (test_id) references test(test_id) on delete cascade on update cascade
);

create table question_choices( 
	choice_id integer not null auto_increment,
    question_id integer not null,
    is_correct tinyint,
    choice text,
    constraint qc_pk primary key (choice_id),
    constraint qc_fk foreign key (question_id) references test_question(question_id) on delete cascade on update cascade
);

create table test_attemp( 
	test_id integer not null,
    user_id integer not null,
    count tinyint,
    constraint ta_pk primary key (test_id, user_id),
    constraint ta_fk1 foreign key (test_id) references test(test_id) on delete cascade on update cascade,
    constraint ta_fk2 foreign key (user_id) references user_account(user_id) on delete cascade on update cascade
);

create table test_score( 
	test_id integer not null, 
    user_id integer not null, 
    score integer,
    constraint ts_pk primary key (test_id, user_id),
    constraint ts_fk1 foreign key (test_id) references test(test_id) on delete cascade on update cascade,
    constraint ts_fk2 foreign key (user_id) references user_account(user_id) on delete cascade on update cascade
);


INSERT INTO `bk_elearning`.`user_level` (`lvl_id`, `lvl_name`) VALUES ('1', 'Student');
INSERT INTO `bk_elearning`.`user_level` (`lvl_id`, `lvl_name`) VALUES ('2', 'Instructor');
INSERT INTO `bk_elearning`.`user_level` (`lvl_id`, `lvl_name`) VALUES ('3', 'Admin');

INSERT INTO `bk_elearning`.`student_details` (`sd_id`, `sd_studnum`, `sd_fname`, `sd_lname`, `sd_gender`) VALUES ('1', '1915676', 'Trong', 'Nguyen', 'Male');
INSERT INTO `bk_elearning`.`student_details` (`sd_id`, `sd_studnum`, `sd_fname`, `sd_lname`, `sd_gender`) VALUES ('2', '1915886', 'Van', 'Nguyen', 'Male');

INSERT INTO `bk_elearning`.`instructor_details` (`ind_id`, `ind_empid`, `ind_fname`, `ind_lname`, `ind_gender`) VALUES ('1', '1234500', 'Anh', 'Pham', 'Male');

INSERT INTO `bk_elearning`.`user_account` (`user_id`, `lvl_id`, `user_name`, `user_pass`) VALUES (NULL, '3', 'admin', '$2y$10$//h0t8eRfxInUe6LNS9FLOZ5UpqLCPEfUbyHncGBRJ1yGPbOIxFmy');
INSERT INTO `bk_elearning`.`user_account` (`user_id`, `lvl_id`, `user_name`, `user_pass`) VALUES ('2', '1', '1915676', '$2y$10$xAefNlSgH1glfUDG08egG.NERBqplNE0A904d2DdmrZsuxJC8Lqjy');
INSERT INTO `bk_elearning`.`user_account` (`user_id`, `lvl_id`, `user_name`, `user_pass`) VALUES ('3', '1', '1915886', '$2y$10$xAefNlSgH1glfUDG08egG.NERBqplNE0A904d2DdmrZsuxJC8Lqjy');
INSERT INTO `bk_elearning`.`user_account` (`user_id`, `lvl_id`, `user_name`, `user_pass`) VALUES ('4', '2', '1234500', '$2y$10$xAefNlSgH1glfUDG08egG.NERBqplNE0A904d2DdmrZsuxJC8Lqjy');

INSERT INTO `bk_elearning`.`faculty` (`faculty_id`, `faculty_name`) VALUES ('1', 'Computer Science and Engineering');
INSERT INTO `bk_elearning`.`faculty` (`faculty_id`, `faculty_name`) VALUES ('2', 'Electrical and Electronics Engineering');
INSERT INTO `bk_elearning`.`faculty` (`faculty_id`, `faculty_name`) VALUES ('3', 'Mechanical Engineering');
INSERT INTO `bk_elearning`.`faculty` (`faculty_id`, `faculty_name`) VALUES ('4', 'Civil Engineering');
INSERT INTO `bk_elearning`.`faculty` (`faculty_id`, `faculty_name`) VALUES ('5', 'Chemical Engineering');
INSERT INTO `bk_elearning`.`faculty` (`faculty_id`, `faculty_name`) VALUES ('6', 'Applied Science');
INSERT INTO `bk_elearning`.`faculty` (`faculty_id`, `faculty_name`) VALUES ('7', 'Industrial Management');
INSERT INTO `bk_elearning`.`faculty` (`faculty_id`, `faculty_name`) VALUES ('8', 'Transportation Engineering');

INSERT INTO `bk_elearning`.`status` (`status_id`, `status`) VALUES ('1', 'Enable');
INSERT INTO `bk_elearning`.`status` (`status_id`, `status`) VALUES ('2', 'Disable');

INSERT INTO `bk_elearning`.`semester` (`sem_id`, `sem_start`, `sem_end`, `status_id`) VALUES ('1', '2021-09-01', '2022-05-01', '2');
INSERT INTO `bk_elearning`.`semester` (`sem_id`, `sem_start`, `sem_end`, `status_id`) VALUES ('2', '2022-09-01', '2023-05-01', '1');

INSERT INTO `bk_elearning`.`subject` (`subject_id`, `subject_name`, `sem_id`, `faculty_id`) VALUES ('1', 'Database Systems', '2', '1');
INSERT INTO `bk_elearning`.`subject` (`subject_id`, `subject_name`, `sem_id`, `faculty_id`) VALUES ('2', 'Embedded System', '2', '1');

ALTER TABLE class ADD ind_id integer not null;
ALTER TABLE class ADD constraint c_fk4 FOREIGN KEY (ind_id) references instructor_details(ind_id);

INSERT INTO `bk_elearning`.`class` (`class_id`, `subject_id`, `status_id`, `ind_id`) VALUES ('1', '1', '1', '1');

alter table class add column class_name varchar(30);