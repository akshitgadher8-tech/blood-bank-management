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