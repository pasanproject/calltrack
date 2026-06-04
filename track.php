<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location Verification</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 50px; background: #f4f4f9; }
        .btn { background: #28a745; color: white; padding: 15px 25px; border: none; border-radius: 5px; cursor: pointer; font-size: 18px; }
    </style>
</head>
<body>
    <h2>ස්ථානය තහවුරු කිරීම</h2>
    <p>කරුණාකර පහත බොත්තම ඔබා ඔබගේ ස්ථානය තහවුරු කරන්න.</p>
    <button class="btn" onclick="getLocation()">Verify My Location</button>

    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(sendPosition);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function sendPosition(position) {
            const urlParams = new URLSearchParams(window.location.search);
            const id = urlParams.get('id');
            
            const formData = new FormData();
            formData.append('id', id);
            formData.append('lat', position.coords.latitude);
            formData.append('lng', position.coords.longitude);

            fetch('update_location.php', {
                method: 'POST',
                body: formData
            }).then(() => {
                alert("ඔබේ ස්ථානය සාර්ථකව තහවුරු කරන ලදී!");
                window.close();
            });
        }
    </script>
</body>
</html>