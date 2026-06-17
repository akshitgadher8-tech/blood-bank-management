<html>
    <body>
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
     </body>

</html>