    </main>
</div>

<!-- Footer section for the admin panel -->
<footer class="admin-footer">
    <p>&copy; <?= date('Y') ?> Coffee & Recipes Admin Panel. All Rights Reserved.</p>
</footer>

<style>
/* Styling for the admin footer */
.admin-footer {
    text-align: center;
    padding: 10px;
    background: #222;
    color: #fff;
}

/* Overall admin layout styling */
.admin-layout {
    display: flex;
    min-height: 100vh;
}

/* Sidebar styling */
.admin-sidebar {
    width: 220px;
    background: #333;
    color: white;
    padding: 20px;
}
.admin-sidebar h2 {
    text-align: center;
    margin-bottom: 20px;
}
/* Sidebar navigation list */
.admin-sidebar ul {
    list-style: none;
    padding: 0;
}
.admin-sidebar ul li {
    margin: 15px 0;
}
.admin-sidebar ul li a {
    color: white;
    text-decoration: none;
    display: block;
    padding: 8px;
    border-radius: 5px;
}
.admin-sidebar ul li a:hover {
    background: #555;
}
/* Admin content area styling */
.admin-content {
    flex: 1;
    padding: 20px;
    background: #f9f9f9;
}
</style>

</body>
</html>
