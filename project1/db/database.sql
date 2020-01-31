DROP DATABASE IF EXISTS university;
CREATE DATABASE university;
USE university;

CREATE TABLE course (
    course_number VARCHAR(10) NOT NULL,
    course_name VARCHAR(50) DEFAULT "",
    credit_hours INT(1) UNSIGNED DEFAULT "0",
    department VARCHAR(10) DEFAULT "",
    PRIMARY KEY (course_number)
);

CREATE TABLE section (
    section_identifier INT(10) UNSIGNED NOT NULL,
    course_number VARCHAR(10) NOT NULL,
    semester VARCHAR(10) DEFAULT "",
    year VARCHAR(10) DEFAULT "",
    instructor VARCHAR(10) DEFAULT "",
    PRIMARY KEY (section_identifier)
);

CREATE TABLE grade_report (
    student_number INT(10) UNSIGNED NOT NULL,
    section_identifier INT(10) NOT NULL,
    grade VARCHAR(1) NOT NULL,
    PRIMARY KEY (student_number)
);

CREATE TABLE prerequisite (
    course_number VARCHAR(10) NOT NULL,
    prerequisite_number VARCHAR(10) NOT NULL,
    PRIMARY KEY (course_number)
);

CREATE TABLE student (
    student_number INT(10) UNSIGNED NOT NULL,
    name VARCHAR(30) DEFAULT "",
    class INT(1) UNSIGNED DEFAULT "0",
    major VARCHAR(10) DEFAULT "",
    PRIMARY KEY (student_number)
);