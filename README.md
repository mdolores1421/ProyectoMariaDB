# ProyectoMariaDB
Personal Project for the WAI (Web Application Interaction) class
This project demonstrates the use of PHP in combination with MariaDB for database-driven web applications. The application provides features like managing records, querying the database, and exporting data in various formats.

**Technologies Used**
This project demonstrates the use of PHP in combination with MariaDB for database-driven web applications. The application provides features like managing records, querying the database, and exporting data in various formats.

**Technologies Used**
PHP: Server-side scripting language used for backend development.
MariaDB/MySQL: Database system used to store and manage data.
HTML/CSS: For structuring and styling the web pages.
PDO (PHP Data Objects): A database access layer for connecting to MariaDB and performing SQL operations securely.

**Features**
Login System: User authentication to restrict access to certain parts of the application.
CRUD Operations: Create, Read, Update, and Delete records from the database.
Search Functionality: Allows users to search for records based on specific criteria.
Data Export: Export records in various formats including CSV, TXT, and JSON.
Responsive UI: Simple and responsive design for easy use on different devices.

**Installation Requirements**
A server with PHP support (e.g., Apache or Nginx).
MariaDB or MySQL installed and running.
Basic understanding of PHP and database management.

**Setup Instructions**
Clone the Repository:

git clone https://github.com/username/php-mariadb-project.git

**Set up the Database:**
Create a MariaDB database to store the records.

CREATE DATABASE my_database;
Import the necessary tables (you can use a SQL dump file if provided).

**Configure Database Connection:**
Update the database connection details in the PHP files:

$host = "localhost"; // Or your MariaDB server IP
$user = "your_username";
$password = "your_password";
$db = "my_database";
Deploy the Application:

Upload the files to your server and make sure the web server is configured to run PHP.

**Access the Application:**
Open your browser and navigate to the application’s URL (e.g., http://localhost/index.php).

**Usage**
Login: Users can log in using predefined credentials to access the full functionality of the app.
Manage Records: Add, edit, or delete records from the database.
Search: Use the search feature to find specific records based on input criteria.
Export Data: Download the records in CSV, TXT, or JSON format as needed.
Contributions
