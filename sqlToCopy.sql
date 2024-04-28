CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(255) NOT NULL
);

INSERT INTO users (username, password, role) VALUES ('admin', 'adminpass', 'admin');
INSERT INTO users (username, password, role) VALUES ('user', 'userpass', 'user');
