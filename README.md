# BookShine Dashboard

BookShine is a clean and modern Admin Dashboard designed for managing a blog or library system. It allows administrators to manage articles, categories, and comments with an intuitive interface built with PHP and Tailwind CSS.

## Features

- **Articles Management:** View articles, track view counts, and toggle publication status instantly (AJAX).
- **Categories Management:** List all categories and delete unwanted ones.
- **Comments System:** View user comments, approve/reject them via a status toggle, or delete them.
- **Modern UI:** Responsive design using **Tailwind CSS**.
- **Database Integration:** Uses **PDO** for secure database connections.

## Technologies Used

- **Backend:** PHP (Native)
- **Frontend:** HTML5, Tailwind CSS (CDN), JavaScript (Vanilla)
- **Database:** MySQL
- **Icons:** FontAwesome

## Installation & Setup

1.  **Clone or Download** the project files to your server directory (e.g., `htdocs` in XAMPP).
2.  **Database Configuration:**
    - Open your database manager (e.g., PhpMyAdmin).
    - Create a database named **`projetsql`**.
    - Import the following SQL structure to create the necessary tables:

```sql
-- Create Users Table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    email VARCHAR(100),
    password VARCHAR(255)
);

-- Create Category Table
CREATE TABLE category (
    id_cat INT AUTO_INCREMENT PRIMARY KEY,
    nom_cat VARCHAR(100),
    description TEXT
);

-- Create Articles (Postes) Table
CREATE TABLE postes (
    id_artc INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    image_url VARCHAR(255),
    view_count INT DEFAULT 0,
    status TINYINT(1) DEFAULT 1,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Create Comments Table
CREATE TABLE comments (
    id_comnt INT AUTO_INCREMENT PRIMARY KEY,
    contenu TEXT,
    statues TINYINT(1) DEFAULT 0,
    Date_cr DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    id_artc INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (id_artc) REFERENCES postes(id_artc)
);
```
