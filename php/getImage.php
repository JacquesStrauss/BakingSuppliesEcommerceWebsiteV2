<?php
// Simulated database table (memory table)
$areas = array(
    "10001" => array(
        "services" => array("Teeth Cleaning", "Fillings", "Root Canal"),
        "doctors" => array("Dr. Smith", "Dr. Johnson", "Dr. Lee")
    ),
    "20002" => array(
        "services" => array("Teeth Whitening", "Extractions", "Dental Implants"),
        "doctors" => array("Dr. Brown", "Dr. Wilson", "Dr. Davis")
    ),
    "30003" => array(
        "services" => array("Orthodontics", "Periodontics", "Oral Surgery"),
        "doctors" => array("Dr. Taylor", "Dr. Martinez", "Dr. Rodriguez")
    )
);

if(isset($_POST['areaCode'])) {
    $areaCode = $_POST['areaCode'];
    
    if(array_key_exists($areaCode, $areas)) {
        $services = $areas[$areaCode]['services'];
        $doctors = $areas[$areaCode]['doctors'];
        
        echo "<h2>Dental Services in Area Code $areaCode:</h2>";
        echo "<table>";
        echo "<tr><th>Service</th><th>Doctor</th></tr>";
        foreach ($services as $key => $service) {
            $doctor = isset($doctors[$key]) ? $doctors[$key] : "";
            echo "<tr><td>$service</td><td>$doctor</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No dental services found for area code $areaCode</p>";
    }
} else {
    echo "<p>No area code provided</p>";
}
?>
