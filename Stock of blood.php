<html>
    <head>
        <style>
            :root{
                --primary-color: #8B0000; /*deep red sidebar*/
                --secondary-color: #8B0000;
                --background-light: #f4f4f4;
                --white: #ffffff;
            }
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            body {
                font-family: 'Arial', sans-serif;
                background-color: var(--background-light);
                display: flex;
                height: 100vh;
            }
            .sidebar {
                width: 250px;
                background-color: var(--primary-color);
                padding: 20px;
                display: flex;
                flex-direction: column;                
            }
            .sidebar-logo{
                color:var(--white);
                text-align: center;
                margin-bottom: 30px;
                font-size: 24px;
            }
            .sidebar-menu{
                list-style: none;
            }
            .sidebar-menu li {
                margin-bottom: 10px;
            }
            .sidebar-menu li a {
                color: var(--white);
                text-decoration: none;
                display: block;
                padding: 10px 15px;
                border-radius: 5px;
                transition: background-color 0.3s;
            }
            .sidebar-menu li a:hover, .sidebar-menu li a.active{
                background-color: var(--secondary-color);
            }
        </style>
    </head>
<body>
<div class="sidebar">
<div class="sidebar-logo">Blood Bank</div>
        <ul class="sidebar-menu">
            <li><a href="dash.php" data-section="dashboard" >Dashboard</a></li>
            <li><a href="donors.php" data-section="donors" class="active">Donors</a></li>
            <li><a href="" data-section="inventory">Stock of blood</a></li>
            <li><a href="Blood Requirement.php" data-section="requests">Blood Requirement</a></li>
            <li><a href="reports.php" data-section="reports">Reports</a></li>
        </ul>
        </div>
        <div class="container">
            <p>Welcome to Stock of blood</p>
        </div>
    </body>
</html>