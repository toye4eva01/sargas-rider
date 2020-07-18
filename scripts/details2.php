<?php

require '../includes/url.php';


if (isset($_POST['dispatch_id'])) {

    $dispatch_id = trim($_POST['dispatch_id']);
//    $password = trim($_POST['password']);


    $fields = array(
        'dispatch_id' => $dispatch_id
    );
//     print_r($fields);


    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url . "dispatch_details",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($fields),
        CURLOPT_HTTPHEADER => array(
            "Accept: application/json",
            "Content-Type: application/json",
//            "APIKEY: ".$apikey,
//            "Authorization: Bearer ".$token
        ),
    ));

    $response = curl_exec($curl);
//print_r($response);
    curl_close($curl);
    $response_array = json_decode($response, true);


}

//else {
//
//    $response_array['status'] == 1005;
//    $response_array['message'] == 'Blank Data';
//
//
//}

echo json_encode($response_array);