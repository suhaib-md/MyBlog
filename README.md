# Simple Blog Platform

A lightweight PHP blog application that enables users to register, create, edit, and delete blog posts with text and image content.

![Simple Blog Platform](https://raw.githubusercontent.com/suhaib-md/MyBlog/main/screenshot.png)

## Features

- **User Authentication**
  - Registration with username, email, and secure password storage
  - Login with session management
  - Protected routes for authenticated users

- **Blog Post Management**
  - Create new posts with title, content, and optional images
  - View all blog posts on a responsive homepage
  - Edit and delete your own posts
  - Image upload functionality

- **User Interface**
  - Responsive design for mobile and desktop
  - Clean and intuitive dashboard for post management
  - Pagination for browsing through multiple posts

## Architecture

The application follows a simplified Model-View-Controller (MVC) pattern:

![Architecture Diagram](https://raw.githubusercontent.com/suhaib-md/MyBlog/main/architecture.svg)

- **Configuration Layer**: Database connection settings
- **Authentication Layer**: User sessions and access control
- **Controllers**: PHP scripts handling business logic
- **Views**: PHP templates with embedded HTML/CSS
- **Assets**: Static resources including uploaded images

## Technology Stack

- **Backend**: Native PHP with PDO for database operations
- **Database**: MySQL
- **Frontend**: HTML5, CSS3
- **Server**: Apache (XAMPP recommended for development)

## Security Features

- Password hashing using PHP's `password_hash()` with BCRYPT
- Protection against SQL injection with prepared statements
- XSS prevention through consistent output escaping
- Authorization checks for user-specific content
- Secure file upload handling

## Installation

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- PDO PHP extension
- GD PHP extension (for image processing)

### Setup Instructions

1. **Clone the repository**
   ```
   git clone https://github.com/suhaib-md/MyBlog.git
   cd MyBlog
   ```

2. **Create the database**
   ```sql
   CREATE DATABASE simple_blog;
   ```

3. **Import the database schema**
   ```
   mysql -u username -p simple_blog < database/simple_blog.sql
   ```

4. **Configure database connection**
   - Edit `config/db.php` with your database credentials:
   ```php
   $host = 'localhost';
   $db = 'simple_blog';
   $user = 'your_username';
   $pass = 'your_password';
   ```

5. **Set permissions**
   ```
   chmod 755 uploads/
   ```

6. **Access the application**
   - Navigate to `http://localhost/MyBlog` in your browser

## File Structure

```
simple_blog/
├── config/
│   └── db.php                 # Database configuration
├── includes/
│   ├── auth.php               # Authentication checks
│   ├── footer.php             # Common footer template
│   └── navbar.php             # Navigation bar template
├── uploads/                   # Directory for uploaded images
├── index.php                  # Homepage with public blog posts
├── register.php               # User registration
├── login.php                  # User login
├── logout.php                 # Session termination
├── dashboard.php              # User dashboard
├── create_post.php            # Create new posts
├── edit_post.php              # Edit existing posts
└── delete_post.php            # Delete posts
```

## Database Structure

### Users Table

```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Posts Table

```sql
CREATE TABLE posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  image VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## Usage

1. **Register** a new account or log in with existing credentials
2. **Create** a new blog post from your dashboard
3. **View** all posts on the homepage
4. **Edit** or **Delete** your posts from the dashboard
5. **Upload** images to enhance your blog posts

## Future Enhancements

- Rich text editor for post content
- Comments system
- Categories and tags
- User roles (admin, editor, author)
- Social media sharing
- Search functionality
- Email notifications

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Author

Vadakathi Muhammed Suhaib  

---

**Note**: This is a development project and not intended for production use without additional security measures.
