<!DOCTYPE html>
<html>
<head>
    <title>Test Server Kantin Bahagia</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        button { padding: 10px 20px; margin: 10px; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 5px; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Test Server PHP Kantin Bahagia</h1>
    
    <button onclick="testPHP()">Test Koneksi PHP</button>
    <button onclick="testMySQL()">Test Koneksi MySQL</button>
    <button onclick="testTransaction()">Test Simpan Transaksi</button>
    <button onclick="viewLocalStorage()">Lihat LocalStorage</button>
    <button onclick="clearLocalStorage()">Bersihkan Data</button>
    
    <div id="result"></div>
    
    <script>
        async function testPHP() {
            const resultDiv = document.getElementById('result');
            resultDiv.innerHTML = '<p>Testing PHP connection...</p>';
            
            try {
                const response = await fetch('save_transaction.php');
                const text = await response.text();
                
                resultDiv.innerHTML = `
                    <h3 class="success">✓ PHP Berjalan!</h3>
                    <p>Status: ${response.status}</p>
                    <pre>${text}</pre>
                `;
            } catch (error) {
                resultDiv.innerHTML = `
                    <h3 class="error">✗ PHP Error</h3>
                    <p>${error.message}</p>
                    <p>Pastikan:</p>
                    <ol>
                        <li>File save_transaction.php ada di folder yang sama</li>
                        <li>Server PHP berjalan (XAMPP/LAMPP)</li>
                        <li>Akses via http://localhost/ bukan file:///</li>
                    </ol>
                `;
            }
        }
        
        async function testMySQL() {
            const resultDiv = document.getElementById('result');
            resultDiv.innerHTML = '<p>Testing MySQL connection...</p>';
            
            const testData = {
                items: [{id: 1, name: "Test", price: 1000, qty: 1}],
                total: 1000
            };
            
            try {
                const response = await fetch('save_transaction.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(testData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    resultDiv.innerHTML = `
                        <h3 class="success">✓ MySQL Berhasil!</h3>
                        <p>Mode: ${result.mode || 'unknown'}</p>
                        <pre>${JSON.stringify(result, null, 2)}</pre>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <h3 class="error">✗ MySQL Error</h3>
                        <pre>${JSON.stringify(result, null, 2)}</pre>
                    `;
                }
            } catch (error) {
                resultDiv.innerHTML = `<h3 class="error">✗ Error: ${error.message}</h3>`;
            }
        }
        
        function viewLocalStorage() {
            const transactions = JSON.parse(localStorage.getItem('kantin_transactions') || '[]');
            const resultDiv = document.getElementById('result');
            
            resultDiv.innerHTML = `
                <h3>Data LocalStorage (${transactions.length} transaksi)</h3>
                <pre>${JSON.stringify(transactions, null, 2)}</pre>
            `;
        }
        
        function clearLocalStorage() {
            localStorage.removeItem('kantin_transactions');
            document.getElementById('result').innerHTML = '<p class="success">LocalStorage dibersihkan!</p>';
        }
    </script>
</body>
</html>