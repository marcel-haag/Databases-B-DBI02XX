-- Exercise 1 a) Anlegen der user_table
   CREATE TABLE user_table (
       id INT PRIMARY KEY AUTO_INCREMENT,
       name VARCHAR(45) NOT NULL,
       password VARCHAR(45) NOT NULL
   );

-- Exercise 1 b) Anlegen der todo_table
   CREATE TABLE todo_table (
        id INT PRIMARY KEY AUTO_INCREMENT,
        UserId INT NOT NULL,
        Datum DATE NOT NULL,
        todo VARCHAR(45) NOT NULL,
        FOREIGN KEY (UserId) REFERENCES user_table(id)
   );

-- Exercise 1 c) Füllen der user_table
   INSERT INTO user_table (name, password) VALUES
   ('Thea', '0000'),
   ('Lara', '1111'),
   ('Luisa', '2222');
