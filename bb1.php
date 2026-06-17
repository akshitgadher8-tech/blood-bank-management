<?php
// SECURITY LOCK: Checks if you came from the login screen
session_start();
if(!isset($_SESSION['admin'])) {
    header("Location: mainLogin.php");
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blood Bank Management System</title>
    <link rel="stylesheet" href="bbb.css">
    
    <style>
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .table th, .table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; }
        .table th { background-color: #f8f9fa; font-weight: 600; color: #333; }
        .table tbody tr:hover { background-color: #f9f9f9; }
        .status-available { color: #28a745; font-weight: bold; }
        .status-low { color: #dc3545; font-weight: bold; }
        .badge { background: #dc3545; color: white; padding: 3px 8px; border-radius: 4px; font-size: 0.9em; }
        
        /* Dashboard Metric Cards */
        .dashboard-metrics { display: flex; gap: 20px; margin-bottom: 25px; flex-wrap: wrap; }
        .metric-card { background: white; padding: 25px; border-radius: 8px; flex: 1; min-width: 200px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-top: 4px solid #8B0000; }
        .metric-title { font-size: 16px; color: #555; margin-bottom: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .metric-value { font-size: 36px; color: #8B0000; font-weight: bold; }
        
        /* Approve Button Style */
        .btn-approve { background-color: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 14px; }
        .btn-approve:hover { background-color: #218838; }
    </style>
</head>
<body>
<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bms";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ---------------------------------------------------------
// 1. REGISTER NEW DONORS (PHP 5.4 COMPATIBLE)
// ---------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nm'])) {
    $name = $conn->real_escape_string($_POST['nm']);
    $mobile = $conn->real_escape_string($_POST['mn']);
    $blood_group = $conn->real_escape_string($_POST['bg']);
    
    if (!empty($name) && !empty($mobile) && !empty($blood_group)) {
        // Matches your exact SQL dump
        $sql = "INSERT INTO newdonor (Name, MobilleNumber, BloodGroup) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("sss", $name, $mobile, $blood_group);
            if($stmt->execute()) {
                echo "<script>alert('Donor registered successfully!'); window.location.href='bb1.php?section=donors';</script>";
            } else {
                echo "<script>alert('Execution Error: " . addslashes($stmt->error) . "');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Database Error: " . addslashes($conn->error) . "');</script>";
        }
    } else {
        echo "<script>alert('All donor fields are required');</script>";
    }
}

// ---------------------------------------------------------
// 2. BLOOD REQUEST SUBMISSION
// ---------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_request'])) {
    $patient_name = $conn->real_escape_string($_POST['patient_name']);
    $blood_group = $conn->real_escape_string($_POST['blood_group']);
    $units_required = $conn->real_escape_string($_POST['units_required']);
    $status = "Pending";

    if(!empty($patient_name) && !empty($blood_group) && !empty($units_required)) {
        $sql = "INSERT INTO blood_requests (patient_name, blood_group, units_required, status) VALUES (?,?,?,?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("ssis", $patient_name, $blood_group, $units_required, $status);
            if($stmt->execute()) {
                echo "<script>alert('Blood request submitted!'); window.location.href='bb1.php?section=requests';</script>";
            }
            $stmt->close();
        }
    }
}

// ---------------------------------------------------------
// 3. APPROVE REQUEST ENGINE
// ---------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['approve_request'])) {
    $req_id = intval($_POST['req_id']); 
    $sql = "UPDATE blood_requests SET status='Approved' WHERE id=$req_id";
    $conn->query($sql);
    echo "<script>alert('Request has been Approved!'); window.location.href='bb1.php?section=requests';</script>";
}

// ---------------------------------------------------------
// 4. FETCH DASHBOARD METRICS
// ---------------------------------------------------------
$totalDonors = 0;
$totalReqs = 0;
$fulfilledReqs = 0;

$res1 = $conn->query("SELECT COUNT(*) as c FROM newdonor");
if ($res1) {
    $row = $res1->fetch_assoc();
    $totalDonors = $row['c'];
}

$res2 = $conn->query("SELECT COUNT(*) as c FROM blood_requests");
if ($res2) {
    $row = $res2->fetch_assoc();
    $totalReqs = $row['c'];
}

$res3 = $conn->query("SELECT COUNT(*) as c FROM blood_requests WHERE status='Approved'");
if ($res3) {
    $row = $res3->fetch_assoc();
    $fulfilledReqs = $row['c'];
}
?>

    <div class="sidebar">
        <div class="sidebar-logo">Blood Bank Management System</div>
        <ul class="sidebar-menu">
            <li><a href="#" data-section="dashboard" class="active">Dashboard</a></li>
            <li><a href="#" data-section="donors">Donors</a></li>
            <li><a href="#" data-section="inventory">Stock of blood</a></li>
            <li><a href="#" data-section="requests">Blood Requirement</a></li>
            <li><a href="#" data-section="reports">Reports</a></li>
            <li><a href="#" id="logoutBtn">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <section id="dashboard" class="section active">
            <h1>Dashboard Overview</h1>
            
            <div class="dashboard-metrics">
                <div class="metric-card">
                    <div class="metric-title">Total Donors</div>
                    <div class="metric-value"><?php echo $totalDonors; ?></div>
                </div>
                <div class="metric-card">
                    <div class="metric-title">Total Requirements</div>
                    <div class="metric-value"><?php echo $totalReqs; ?></div>
                </div>
                <div class="metric-card">
                    <div class="metric-title">Requests Fulfilled</div>
                    <div class="metric-value"><?php echo $fulfilledReqs; ?></div>
                </div>
            </div>

            <div class="card1">
                <h2>Blood Inventory Status</h2>
                <div id="inventoryStatus">
                    <?php
                    $sql = "SELECT * FROM newdonor ORDER BY Donorid DESC LIMIT 10";
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        echo "<table class='table'><thead><tr><th>Name</th><th>Blood Group</th><th>Mobile Number</th></tr></thead><tbody>";
                        while ($row = $result->fetch_assoc()) {
                            
                            $safeName = "Unknown";
                            $safeBg = "Unknown";
                            $safeMobile = "Not Available";

                            foreach($row as $key => $value) {
                                $lowerKey = strtolower(trim($key));
                                if ($lowerKey == 'name') $safeName = htmlspecialchars($value);
                                if (strpos($lowerKey, 'blood') !== false) $safeBg = htmlspecialchars($value);
                                if (strpos($lowerKey, 'mobil') !== false) $safeMobile = htmlspecialchars($value);
                            }

                            echo "<tr>
                                    <td>{$safeName}</td>
                                    <td><span class='badge'>{$safeBg}</span></td>
                                    <td>{$safeMobile}</td>
                                  </tr>";
                        }
                        echo "</tbody></table>";
                    } else { echo "<p>No donors available.</p>"; }
                    ?>
                </div>
            </div>
            
            <div class="card1">
                <h2>Currently Pending Requests</h2>
                <table class='table'>
                    <thead><tr><th>Patient Name</th><th>Blood Group</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM blood_requests WHERE status='Pending' ORDER BY id DESC LIMIT 5";
                        $res = $conn->query($sql);
                        
                        if ($res && $res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $pName = "Unknown"; $bGroup = "Unknown";
                                foreach($row as $key => $value) {
                                    $lk = strtolower(trim($key));
                                    if (strpos($lk, 'patient') !== false || $lk == 'name') $pName = htmlspecialchars($value);
                                    if (strpos($lk, 'blood') !== false) $bGroup = htmlspecialchars($value);
                                }
                                echo "<tr><td>{$pName}</td><td><span class='badge'>{$bGroup}</span></td><td style='color:orange; font-weight:bold;'>Pending</td></tr>";
                            }
                        } else { echo "<tr><td colspan='3'>No pending requests.</td></tr>"; }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="card1">
                <h2>Recently Approved Requests</h2>
                <table class='table'>
                    <thead><tr><th>Patient Name</th><th>Blood Group</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM blood_requests WHERE status='Approved' ORDER BY id DESC LIMIT 5";
                        $res = $conn->query($sql);
                        
                        if ($res && $res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $pName = "Unknown"; $bGroup = "Unknown";
                                foreach($row as $key => $value) {
                                    $lk = strtolower(trim($key));
                                    if (strpos($lk, 'patient') !== false || $lk == 'name') $pName = htmlspecialchars($value);
                                    if (strpos($lk, 'blood') !== false) $bGroup = htmlspecialchars($value);
                                }
                                echo "<tr><td>{$pName}</td><td><span class='badge'>{$bGroup}</span></td><td style='color:green; font-weight:bold;'>Approved</td></tr>";
                            }
                        } else { echo "<tr><td colspan='3'>No approved requests yet.</td></tr>"; }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="donors" class="section">
            <h1>Donor Management</h1>
            <div class="card">
                <h2>Add New Donor</h2>
                <form method="POST">
                    <div class="form-group"><label>Name</label><input type="text" name="nm" pattern="[A-Za-z_ ]+" required></div>
                    <div class="form-group"><label>Mobile Number</label><input type="text" maxlength="10" name="mn" pattern="[0-9]{10}" required></div>
                    <div class="form-group"><label>Blood Group</label>
                        <select name="bg" required>
                            <option>A+</option><option>A-</option><option>B+</option><option>B-</option>
                            <option>AB+</option><option>AB-</option><option>O+</option><option>O-</option>
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
                    <thead><tr><th>Blood Group</th><th>Available Units</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php
                        $sql = "SELECT BloodGroup, COUNT(*) as units FROM newdonor GROUP BY BloodGroup";
                        $result = $conn->query($sql);
                        if(!$result) {
                            $sql = "SELECT blood_group as BloodGroup, COUNT(*) as units FROM newdonor GROUP BY blood_group";
                            $result = $conn->query($sql);
                        }

                        if($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $bloodGroup = "Unknown";
                                foreach($row as $key => $value) {
                                    if (strpos(strtolower(trim($key)), 'blood') !== false) $bloodGroup = htmlspecialchars($value);
                                }
                                $units = isset($row["units"]) ? $row["units"] : 0;
                                $status = $units > 10 ? "Available" : "Low";
                                echo "<tr><td><strong>{$bloodGroup}</strong></td><td>{$units}</td><td><span class='status-" . strtolower($status) . "'>{$status}</span></td></tr>";
                            }
                        } else { echo "<tr><td colspan='3'>No blood inventory data available.</td></tr>"; }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="requests" class="section">
            <h1>Blood Requests</h1>
            <div class="card">
                <h2>New Blood Request</h2>
                <form id="requestForm" method="POST">
                    <div class="form-group"><label>Patient Name</label><input type="text" name="patient_name" pattern="[A-Za-z_ ]+" required></div>
                    <div class="form-group"><label>Required Blood Group</label>
                        <select name="blood_group" required>
                            <option>A+</option><option>A-</option><option>B+</option><option>B-</option>
                            <option>AB+</option><option>AB-</option><option>O+</option><option>O-</option>
                        </select>
                    </div>
                    <div class="form-group"><label>Units Required</label><input type="number" name="units_required" min="1" required></div>
                    <button type="submit" class="btn" name="submit_request">Submit Request</button>
                </form>
            </div>
            
            <div class="card">
                <h2>Pending Requests (Awaiting Approval)</h2>
                <table class="table" id="requestTable">
                    <thead><tr><th>Patient</th><th>Blood Group</th><th>Units</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody id="pendingRequests">
                        <?php
                        $sql = "SELECT * FROM blood_requests WHERE status='Pending' ORDER BY id DESC";
                        $res = $conn->query($sql);
                        
                        if ($res && $res->num_rows > 0) {
                            while ($row = $res->fetch_assoc()) {
                                $req_id = isset($row['id']) ? $row['id'] : 0; 
                                $pName = "Unknown"; $bGroup = "Unknown"; $uReq = "0";
                                foreach($row as $key => $value) {
                                    $lk = strtolower(trim($key));
                                    if (strpos($lk, 'patient') !== false || $lk == 'name') $pName = htmlspecialchars($value);
                                    if (strpos($lk, 'blood') !== false) $bGroup = htmlspecialchars($value);
                                    if (strpos($lk, 'unit') !== false) $uReq = htmlspecialchars($value);
                                }
                                
                                echo "<tr>
                                        <td>{$pName}</td>
                                        <td><span class='badge'>{$bGroup}</span></td>
                                        <td>{$uReq}</td>
                                        <td style='color:orange; font-weight:bold;'>Pending</td>
                                        <td>
                                            <form method='POST' style='margin:0;'>
                                                <input type='hidden' name='req_id' value='{$req_id}'>
                                                <button type='submit' name='approve_request' class='btn-approve'>Approve</button>
                                            </form>
                                        </td>
                                      </tr>";
                            }
                        } else { echo "<tr><td colspan='5'>No pending requests</td></tr>"; }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="reports" class="section">
            <h1>Blood Bank Reports</h1>
            <div class="card">
                <h2>Total Donation Statistics</h2>
                <div id="donationStats">
                    <?php
                    $sql = "SELECT BloodGroup, COUNT(*) as count FROM newdonor GROUP BY BloodGroup";
                    $result = $conn->query($sql);
                    if(!$result) {
                        $sql = "SELECT blood_group as BloodGroup, COUNT(*) as count FROM newdonor GROUP BY blood_group";
                        $result = $conn->query($sql);
                    }

                    if($result && $result->num_rows > 0){
                        echo "<table class='table'><thead><tr><th>Blood Group</th><th>Total Donors</th></tr></thead><tbody>";
                        while ($row = $result->fetch_assoc()) {
                            $bg = "Unknown";
                            foreach($row as $k => $v) if (strpos(strtolower(trim($k)), 'blood') !== false) $bg = htmlspecialchars($v);
                            echo "<tr><td><strong>{$bg}</strong></td><td>" . $row["count"] . "</td></tr>";
                        }
                        echo "</tbody></table>";
                    } else { echo "<p>No donation statistics available.</p>"; }
                    ?>
                </div>
            </div>
            
            <div class="card">
                <h2>Pending Requests by Blood Group</h2>
                <div id="requestStats">
                    <?php
                    $sql = "SELECT blood_group, COUNT(*) as count FROM blood_requests WHERE status='Pending' GROUP BY blood_group";
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0){
                        echo "<table class='table'><thead><tr><th>Blood Group Needed</th><th>Total Pending Requests</th></tr></thead><tbody>";
                        while ($row = $result->fetch_assoc()) {
                            $bg = isset($row['blood_group']) ? htmlspecialchars($row['blood_group']) : "Unknown";
                            echo "<tr><td><span class='badge'>{$bg}</span></td><td style='color:orange; font-weight:bold;'>" . $row["count"] . " Pending</td></tr>";
                        }
                        echo "</tbody></table>";
                    } else { echo "<p>No pending requests.</p>"; }
                    ?>
                </div>
            </div>

            <div class="card">
                <h2>Approved Requests by Blood Group</h2>
                <div>
                    <?php
                    $sql = "SELECT blood_group, COUNT(*) as count FROM blood_requests WHERE status='Approved' GROUP BY blood_group";
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0){
                        echo "<table class='table'><thead><tr><th>Blood Group Approved</th><th>Total Fulfilled Requests</th></tr></thead><tbody>";
                        while ($row = $result->fetch_assoc()) {
                            $bg = isset($row['blood_group']) ? htmlspecialchars($row['blood_group']) : "Unknown";
                            echo "<tr><td><span class='badge' style='background:green;'>{$bg}</span></td><td style='color:green; font-weight:bold;'>" . $row["count"] . " Approved</td></tr>";
                        }
                        echo "</tbody></table>";
                    } else { echo "<p>No approved requests yet.</p>"; }
                    ?>
                </div>
            </div>
        </section>
    </div>
    
    <?php $conn->close(); ?>
    
    <script>
        document.querySelectorAll('.sidebar-menu a').forEach(function(link) {
            if(link.id !== 'logoutBtn') { 
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    var allLinks = document.querySelectorAll('.sidebar-menu a');
                    for (var i = 0; i < allLinks.length; i++) { allLinks[i].classList.remove('active'); }
                    
                    var allSections = document.querySelectorAll('.section');
                    for (var j = 0; j < allSections.length; j++) { allSections[j].classList.remove('active'); }
                    
                    link.classList.add('active');
                    var sectionId = link.getAttribute('data-section');
                    document.getElementById(sectionId).classList.add('active');
                });
            }
        });

        // Safe Logout JavaScript Call
        document.getElementById('logoutBtn').addEventListener('click', function(e) {
            e.preventDefault();
            if(confirm('Are you sure you want to Logout?')){
                window.location.href = 'mainLogin.php?logout=true';
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            var urlParams = new URLSearchParams(window.location.search);
            var section = urlParams.get('section');
            if (section) {
                var allLinks = document.querySelectorAll('.sidebar-menu a');
                for (var i = 0; i < allLinks.length; i++) { allLinks[i].classList.remove('active'); }
                
                var allSections = document.querySelectorAll('.section');
                for (var j = 0; j < allSections.length; j++) { allSections[j].classList.remove('active'); }
                
                var menuItem = document.querySelector('.sidebar-menu a[data-section="' + section + '"]');
                if(menuItem) menuItem.classList.add('active');
                var sectionElement = document.getElementById(section);
                if (sectionElement) sectionElement.classList.add('active');
            }
        });
    </script>
</body>
</html>