<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>WhatsApp Status</title>
</head>
<body>
    <h1>WhatsApp Status</h1>
    <h2>Start New Session</h2>
    <form id="startSessionForm">
        <label for="session_id">Session ID:</label>
        <select id="session_id" name="session_id" required>
            <option value="A1">A1</option>
            <option value="B2">B2</option>
        </select>
        <button type="submit">Start Session</button>
    </form>
    <h2>Check Session Status</h2>
    <form id="checkStatusForm">
        <label for="status_session_id">Session ID:</label>
        <select id="status_session_id" name="session_id" required>
            <option value="A1">A1</option>
            <option value="B2">B2</option>
        </select>
        <button type="submit">Check Status</button>
    </form>
    <h2>QR Code</h2>
    <form id="getQRCodeForm">
        <label for="qr_session_id">Session ID:</label>
        <select id="qr_session_id" name="session_id" required>
            <option value="A1">A1</option>
            <option value="B2">B2</option>
        </select>
        <button type="submit">Get QR Code</button>
    </form>
    <div id="qrCodeImage"></div>
    <div id="statusResult"></div>
    <script>
        const apiBaseUrl = 'https://api.clinicadentalnobel.es';

        document.getElementById('startSessionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const sessionId = document.getElementById('session_id').value;
            fetch(`${apiBaseUrl}/session/start/${sessionId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(data => {
                    alert(`Session ${sessionId} started: ${data}`);
                })
                .catch(error => {
                    alert(`Failed to start session ${sessionId}: ${error}`);
                });
        });

        document.getElementById('checkStatusForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const sessionId = document.getElementById('status_session_id').value;
            fetch(`${apiBaseUrl}/session/status/${sessionId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(data => {
                    document.getElementById('statusResult').innerText = `Status for session ${sessionId}: ${data}`;
                })
                .catch(error => {
                    document.getElementById('statusResult').innerText = `Failed to get status for session ${sessionId}: ${error}`;
                });
        });

        document.getElementById('getQRCodeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const sessionId = document.getElementById('qr_session_id').value;
            fetch(`${apiBaseUrl}/session/qr/${sessionId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(data => {
                    document.getElementById('qrCodeImage').innerHTML = `<img src="https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(data)}" alt="QR Code" />`;
                })
                .catch(error => {
                    document.getElementById('qrCodeImage').innerText = `Failed to get QR code for session ${sessionId}: ${error}`;
                });
        });
    </script>
</body>
</html>
