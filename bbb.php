<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blood Bank Management System</title>
    <link rel="stylesheet" href="bbb.css">
   
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-logo">Blood Bank</div>
        <ul class="sidebar-menu">
            <li><a href="#" data-section="dashboard" class="active">Dashboard</a></li>
            <li><a href="#" data-section="donors">Donors</a></li>
            <li><a href="#" data-section="inventory">Stock of blood</a></li>
            <li><a href="#" data-section="requests">Blood Requirement</a></li>
            <li><a href="#" data-section="reports">Reports</a></li>
        </ul>
    </div>

    <div class="main-content">
        <section id="dashboard" class="section active">
            <h1>Dashboard Overview</h1>
            <div class="card1">
                <h2>Blood Inventory Status</h2>
                <div id="inventoryStatus">
                    <?php
                    $conn = new mysqli("localhost", "root", "", "bms");
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    $sql = "SELECT * FROM newdonor ORDER BY Donorid DESC";
                    $result = $conn->query($sql);
                    if ($result && $result->num_rows > 0) {  // Check if $result is valid
                        echo "<table class='table'><tr><th>Name</th><th>Blood Group</th><th>Mobile Number</th></tr>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr><td>" . htmlspecialchars($row["Name"]) . "</td><td>" . htmlspecialchars($row["BloodGroup"]) . "</td><td>" . htmlspecialchars($row["MobileNumber"]) . "</td></tr>";
                            //echo phpversion();
                        }
                        echo "</table>";
                    } else {
                        echo "No donors available.";
                        
                    }
                    
                    $conn->close();
                    ?>
                </div>
            </div>
        </section>

        <section id="donors" class="section">
            <h1>Donor Management</h1>
            <div class="card">
                <h2>Add New Donor</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="nm" required>
                    </div>
                    <div class="form-group">
                        <label>Mobile Number</label>
                        <input type="text" maxlength="10" name="mn" required>
                    </div>
                    <div class="form-group">
                        <label>Blood Group</label>
                        <select name="bg" required>
                            <option>A+</option>
                            <option>A-</option>
                            <option>B+</option>
                            <option>B-</option>
                            <option>AB+</option>
                            <option>AB-</option>
                            <option>O+</option>
                            <option>O-</option>
                        </select>
                    </div>
                    <button type="submit" class="btn">Register Donor</button>
                </form>
            </div>
        </section>

        <section id="inventory" class="section">
            <h1>Blood Inventory</h1>
            <div class="card1">
                <h2>Current Stock</h2>
                <table class="table" id="inventoryTable">
                    <thead>
                        <tr>
                            <th>Blood Group</th>
                            <th>Available Units</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Connect to the database
                        $conn = new mysqli("localhost", "root", "", "BMS");

                        if($conn->connect_error){
                            die("Connection failed: " . $conn->connect_error);
                        }

                        //Fetch Blood inventory data
                        $sql = "SELECT BloodGroup, COUNT(*) as units FROM newdonor GROUP BY BloodGroup";
                        $result = $conn->query($sql);

                        if($result && $result->num_rows > 0) {
                            // Loop through each result and display the blood group available units
                            while ($row = $result->fetch_assoc()) {
                                $bloodGroup = htmlspecialchars($row["BloodGroup"]);
                                $units = $row["units"];
                                $status = $units > 10 ? "Available" : "Low";

                                echo "<tr>";
                                echo "<td>" . $bloodGroup . "</td>";
                                echo "<td>" . $units . "</td>";
                                echo "<td><span class='status-badge status-" . strtolower($status) . "'>" . $status . "</span></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No blood inventory data available.</td></tr>";
                        }

                        // Close the connection
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="requests" class="section">
            <h1>Blood Requests</h1>
            <div class="card">
                <h2>New Blood Request</h2>
                <form id="requestForm">
                    <div class="form-group">
                        <label>Patient Name</label>
                        <input type="text" required>
                    </div>
                    <div class="form-group">
                        <label>Required Blood Group</label>
                        <select required>
                            <option>A+</option>
                            <option>A-</option>
                            <option>B+</option>
                            <option>B-</option>
                            <option>AB+</option>
                            <option>AB-</option>
                            <option>O+</option>
                            <option>O-</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Units Required</label>
                        <input type="number" required>
                    </div>
                    <button type="submit" class="btn">Submit Request</button>
                </form>
            </div>
            <div class="card">
                <h2>Pending Requests</h2>
                <table class="table" id="requestTable">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Blood Group</th>
                            <th>Units</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </section>

        <section id="reports" class="section">
            <h1>Blood Bank Reports</h1>
            <div class="card">
                <h2>Donation Statistics</h2>
                <div id="donationStats"></div>
            </div>
            <div class="card">
                <h2>Request Fulfillment</h2>
                <div id="requestStats"></div>
            </div>
        </section>
    </div><script>
        document.querySelectorAll('.sidebar-menu a').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Remove active classes
                document.querySelectorAll('.sidebar-menu a').forEach(a => a.classList.remove('active'));
                document.querySelectorAll('.section').forEach(section => section.classList.remove('active'));
                
                // Add active class to clicked link and section
                link.classList.add('active');
                const sectionId = link.getAttribute('data-section');
                document.getElementById(sectionId).classList.add('active');
            });
        });

        const bloodInventory = {
            'A+': { total: 25, status: 'available' },
            'A-': { total: 10, status: 'low' },
            'B+': { total: 30, status: 'available' },
            'B-': { total: 15, status: 'available' },
            'AB+': { total: 20, status: 'available' },
            'AB-': { total: 8, status: 'low' },
            'O+': { total: 40, status: 'available' },
            'O-': { total: 12, status: 'available' }
        };

        const donors = [
            { name: 'John Doe', bloodGroup: 'A+', lastDonation: '2024-01-15' },
            { name: 'Jane Smith', bloodGroup: 'O-', lastDonation: '2024-02-01' }
        ];

        const requests = [
            { patient: 'Alice Johnson', bloodGroup: 'B+', units: 2, status: 'Pending' },
            { patient: 'Bob Williams', bloodGroup: 'A-', units: 1, status: 'Urgent' }
        ];

        function renderInventory() {
            const inventoryTable = document.querySelector('#inventoryTable tbody');
            inventoryTable.innerHTML = '';

            Object.entries(bloodInventory).forEach(([group, data]) => {
                const row = document.createElement('tr');
                row.innerHTML = 
                    <td>${group}</td>
                    <td>${data.total}</td>
                    <td><span class="status-badge status-${data.status.toLowerCase()}">${data.status}</span></td>
                ;
                inventoryTable.appendChild(row);
            });
        }

        function renderDonors() {
            const donorTable = document.querySelector('#donorTable tbody');
            donorTable.innerHTML = '';

            donors.forEach(donor => {
                const row = document.createElement('tr');
                row.innerHTML = 
                    <td>${donor.name}</td>
                    <td>${donor.bloodGroup}</td>
                    <td>${donor.lastDonation}</td>
                ;
                donorTable.appendChild(row);
            });
        }

        function renderRequests() {
            const requestTable = document.querySelector('#requestTable tbody');
            requestTable.innerHTML = '';

            requests.forEach(request => {
                const row = document.createElement('tr');
                row.innerHTML = 
                    <td>${request.patient}</td>
                    <td>${request.bloodGroup}</td>
                    <td>${request.units}</td>
                    <td>${request.status}</td>
                ;
                requestTable.appendChild(row);
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderInventory();
            renderDonors();
            renderRequests();
        });
    </script>
    </div> 
    <script src="bbb.js"></script>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "BMS");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $name = $conn->real_escape_string(trim($_POST['nm']));
    $mobile = $conn->real_escape_string(trim($_POST['mn']));
    $blood_group = $conn->real_escape_string(trim($_POST['bg']));
    if (!preg_match("/^\d{10}$/", $mobile)) {
        die("Invalid mobile number! It must be exactly 10 digits.");
    }
    $sql = "INSERT INTO newdonor (Name,MobileNumber,BloodGroup) VALUES ('$name', '$mobile', '$blood_group')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('New donor added successfully!');window.location.href='';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
    $conn->close();
}
?>
