<?php
// Start session and include configuration
session_start();
require_once '../includes/config.php';
// Page title for the installation guide
$page_title = "Installation Guide";
// Start output buffering

ob_start();
?>
<!--the start of the style to indicate the css -->
<style>
.install-container {
    max-width: 900px;
    margin: 40px auto;
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    font-family: Arial, sans-serif;
    line-height: 1.6;
}

.install-container h1 {
    color: #4A6FA5;
    text-align: center;
}

.install-container code {
    padding: 3px 6px;
    border-radius: 4px;
}

.install-container pre {
    background: #272822;
    color: #f8f8f2;
    padding: 10px;
    border-radius: 6px;
    overflow-x: auto;
}

.install-container h2 {
    color: #333;
    margin-top: 20px;
}
pre code {
  background: #222;
  color: #fff;
  padding: 10px;
  display: block;
}

code {
  background: #eee;
  padding: 2px 4px;
  font-family: monospace;
}

</style>

<div class="install-container">
    <h1>☕ Coffee & Recipes – Installation Guide</h1>
<!-- Requirements section -->
    <h2>📦 Requirements</h2>
    <ul>
        <li>PHP <strong>8.0+</strong></li>
        <li>MySQL <strong>5.7+</strong></li>
        <li>A web server (Apache or Nginx)</li>
        <li>Composer (optional, if external packages are used)</li>
    </ul>
<!-- Installation steps -->
    <h2>⚙️ Installation Steps</h2>

    <h3>1️⃣ Download or Clone the Repository</h3>
    <pre><code>git clone https://github.com/OctoberThe8/CoffeeAndRecipe.git</code></pre>
    <p>Or download the ZIP file and extract it into your web server directory (e.g., <code>htdocs</code> for XAMPP, <code>www</code> for WAMP).</p>

    <h3>2️⃣ Create the Database</h3>
    <ol>
        <li>Open <strong>phpMyAdmin</strong> or use MySQL CLI.</li>
        <li>Create a new database:</li>
    </ol>
    <pre><code>CREATE DATABASE coffee_recipes;</code></pre>
    <p>3. Import the SQL file located in <code>/database/coffee_recipes.sql</code>.</p>

    <h3>3️⃣ Configure Database Settings</h3>
    <p>Edit <code>/includes/config.php</code> and update your database credentials:</p>
    <pre><code>define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'password');
define('DB_NAME', 'coffee_recipes');
define('SITE_URL', 'http://localhost/coffee-recipes');</code></pre>

    <h3>4️⃣ Place the Project in the Server Folder</h3>
    <ul>
        <li><strong>XAMPP:</strong> Move to <code>C:/xampp/htdocs/coffee-recipes/</code></li>
        <li><strong>WAMP:</strong> Move to <code>C:/wamp/www/coffee-recipes/</code></li>
    </ul>

    <h3>5️⃣ Access the Website</h3>
    <p>Open your browser and go to:</p>
    <pre><code>http://localhost/coffee-recipes/public/index.php</code></pre>

    <h3>6️⃣ Admin Login</h3>
    <ul>
        <li><strong>Email:</strong> marco@uwindsor.ca</li>
        <li><strong>Password:</strong> 1234</li>
    </ul>

    <h3>7️⃣ Optional (Hosting Online)</h3>
    <p>
        - Upload files to your hosting (e.g., cPanel). <br>
        - Create a new MySQL database in the hosting panel and import the SQL file. <br>
        - Update <code>config.php</code> with your hosting database credentials.
    </p>
</div>

<?php
// Store the buffered content and include header/footer
$content = ob_get_clean();
include '../includes/header.php';
echo $content;
include '../includes/footer.php';
?>
