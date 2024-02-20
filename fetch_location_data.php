<?php
if(isset($_POST['pincode'])) {
    $pincode = $_POST['pincode'];

    // Here, you can fetch location data from your database or any other source based on the pincode
    
    // For demonstration purposes, let's assume you have a function to fetch data from a database
    $locationData = getLocationDataFromDatabase($pincode);

    // Return location data as JSON
    echo json_encode($locationData);
}

function getLocationDataFromDatabase($pincode) {
    // Call the Pincode API to get location data
    $api_url = "https://api.postalpincode.in/pincode/{$pincode}";

    // Initialize cURL session
    $curl = curl_init();

    // Set cURL options
    curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
    ));

    // Execute cURL request
    $response = curl_exec($curl);

    // Close cURL session
    curl_close($curl);

    // Decode the JSON response
    $data = json_decode($response, true);

    // Check if data is valid and contains location details
    if (is_array($data) && isset($data[0]['PostOffice'][0]['District']) && isset($data[0]['PostOffice'][0]['State'])) {
        // Extract district and state from the response
        $district = $data[0]['PostOffice'][0]['District'];
        $state = $data[0]['PostOffice'][0]['State'];

        // Return location data
        return array(
            'district' => $district,
            'state' => $state,
            'country' => 'India' // Assuming the country is always India
        );
    } else {
        // Return an error if location data is not found
        return array('error' => 'Location data not found');
    }
}
?>
