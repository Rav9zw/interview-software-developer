CREATE DATABASE `interview` DEFAULT CHARACTER SET utf8;
CREATE USER 'interview'@'%' IDENTIFIED BY 'interview-password';
GRANT ALL PRIVILEGES ON `interview`.* TO 'interview'@'%';
