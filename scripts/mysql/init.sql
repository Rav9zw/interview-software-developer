CREATE DATABASE `interview` DEFAULT CHARACTER SET utf8;
CREATE DATABASE `interview-test` DEFAULT CHARACTER SET utf8;
CREATE USER 'interview'@'%' IDENTIFIED BY 'interview-password';
CREATE USER 'interview-test'@'%' IDENTIFIED BY 'interview-test-password';
GRANT ALL PRIVILEGES ON `interview`.* TO 'interview'@'%';
GRANT ALL PRIVILEGES ON `interview-test`.* TO 'interview-test'@'%';
