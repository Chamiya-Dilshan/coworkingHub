create DATABASE coworking_hub;
USE coworking_hub;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE bookings(
    id INT AUTO_INCREMENT PRIMARY KEY, 
    user_id INT NOT NULL,
    space_name VARCHAR(100) NOT NULL,
    booking_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON DELETE CASCADE
);

CREATE TABLE cafe_items(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255)
);

CREATE TABLE cart(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    item_id INT NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT DEFAULT 1,

    FOREIGN  KEY(user_id)
    REFERENCES users(id)
    ON DELETE CASCADE,

    FOREIGN KEY(item_id)
    REFERENCES cafe_items(id)
    ON DELETE CASCADE
);

INSERT INTO cafe_items(name,price,image) VALUES
('Cappuccinno','450','https://tse1.mm.bing.net/th/id/OIP.q3V6_vIi9AvkrQznjI-xQQHaF4?pid=Api&h=220&P=0&w=220'),
('Espresso','300','https://tse1.mm.bing.net/th/id/OIP.q3V6_vIi9AvkrQznjI-xQQHaF4?pid=Api&h=220&P=0&w=220'),
('Latte','400','https://tse1.mm.bing.net/th/id/OIP.q3V6_vIi9AvkrQznjI-xQQHaF4?pid=Api&h=220&P=0&w=220'),
('Chocolate Muffin','250','https://tse1.mm.bing.net/th/id/OIP.q3V6_vIi9AvkrQznjI-xQQHaF4?pid=Api&h=220&P=0&w=220'),
('Sandwich','350','https://tse1.mm.bing.net/th/id/OIP.q3V6_vIi9AvkrQznjI-xQQHaF4?pid=Api&h=220&P=0&w=220');
