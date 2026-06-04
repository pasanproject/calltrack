<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Call Tracker (Free Map)</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <!-- Leaflet Map CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        body { 
            background-color: #121212; 
            color: #ffffff; /* සියලුම අකුරු සුදු පැහැය */
        }
        
        #map { 
            height: 80vh; 
            width: 100%; 
            border-radius: 10px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.5); 
            border: 1px solid #333;
        }

        .card {
            background-color: #1e1e1e;
            border: 1px solid #333;
        }

        .card-header {
            background-color: #252525 !important;
            color: #ffffff !important;
            font-weight: bold;
            border-bottom: 1px solid #333;
            padding: 15px;
        }

        .list-group { 
            height: 72vh; 
            overflow-y: auto; 
        }

        /* ඇමතුම් ලැයිස්තුවේ පෙනුම */
        .list-group-item { 
            background-color: #1e1e1e !important; 
            color: #ffffff !important; 
            border: 1px solid #222;
            cursor: pointer;
            border-left: 5px solid transparent;
            transition: 0.3s;
        }

        .list-group-item:hover {
            background-color: #2c2c2c !important;
        }

        .list-group-item.verified { 
            border-left: 5px solid #28a745; 
        }

        .list-group-item.pending { 
            border-left: 5px solid #ffc107; 
        }

        /* කුඩා අකුරු වල වර්ණය පාලනය */
        .text-muted, .text-secondary {
            color: #aaaaaa !important;
        }

        .no-data {
            color: #ffffff;
            text-align: center;
            padding: 40px 20px;
            font-size: 1.1rem;
        }

        /* Scrollbar එක ලස්සන කිරීම */
        ::-webkit-scrollbar {
            width: 5px;
        }
        ::-webkit-scrollbar-track {
            background: #121212;
        }
        ::-webkit-scrollbar-thumb {
            background: #333;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="container-fluid py-4">
    <h3 class="text-center mb-4 text-white">Call Location Tracker Dashboard</h3>
    
    <div class="row">
        <!-- වම් පස ඇමතුම් ලැයිස්තුව -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-telephone-fill me-2"></i> මෑතකදී ලැබුණු ඇමතුම්
                </div>
                <div class="list-group list-group-flush">
                    <?php
                    $result = $conn->query("SELECT * FROM call_trackings ORDER BY id DESC");
                    if ($result->num_rows > 0):
                        while($row = $result->fetch_assoc()): ?>
                        <button onclick="updateMap(<?php echo $row['latitude'] ?? 'null'; ?>, <?php echo $row['longitude'] ?? 'null'; ?>, '<?php echo addslashes($row['contact_name']); ?>')" 
                                class="list-group-item list-group-item-action <?php echo $row['is_verified'] ? 'verified' : 'pending'; ?>">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <h6 class="mb-1 text-white"><?php echo $row['contact_name'] ?: $row['phone_number']; ?></h6>
                                <small class="text-muted"><?php echo date('H:i', strtotime($row['created_at'])); ?></small>
                            </div>
                            <small class="text-secondary d-block"><?php echo $row['phone_number']; ?></small>
                            <?php if($row['is_verified']): ?>
                                <span class="badge bg-success mt-2">Location Verified</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark mt-2">Waiting for Location...</span>
                            <?php endif; ?>
                        </button>
                    <?php endwhile; 
                    else: ?>
                        <div class="no-data">
                            <p>දත්ත කිසිවක් නැත.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- දකුණු පස මැප් එක -->
        <div class="col-md-8">
            <div id="map"></div>
        </div>
    </div>
</div>

<!-- Leaflet Map JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // මැප් එක ආරම්භ කිරීම (Initial Map Setup)
    var map = L.map('map').setView([7.8731, 80.7718], 7); 

    // මැප් එකේ පෙනුම (Dark Mode සිතියමක් අවශ්‍ය නම් මෙහි URL එක වෙනස් කළ හැක)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var currentMarker;

    function updateMap(lat, lng, name) {
        if(lat === null || lng === null) {
            alert("මෙම පුද්ගලයා තවමත් ලොකේෂන් එක ලබා දී නැත.");
            return;
        }

        // පැරණි Marker එක ඉවත් කරයි
        if (currentMarker) {
            map.removeLayer(currentMarker);
        }

        // අලුත් ස්ථානයට Marker එකක් දමයි
        currentMarker = L.marker([lat, lng]).addTo(map)
            .bindPopup("<b style='color:#000;'>" + name + "</b><br><span style='color:#000;'>මෙහි සිටී.</span>")
            .openPopup();

        // එම ස්ථානයට මැප් එක Zoom කරයි
        map.setView([lat, lng], 15);
    }
</script>

</body>
</html>