CREATE DATABASE IF NOT EXISTS gestao_de_viagens_test;

CREATE USER IF NOT EXISTS 'test_user'@'%' IDENTIFIED BY 'test_pass';

GRANT ALL PRIVILEGES ON gestao_de_viagens_test.* TO 'test_user'@'%';

FLUSH PRIVILEGES;