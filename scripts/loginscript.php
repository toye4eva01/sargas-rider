<?php
require '../includes/url.php';
//echo $url;
if (isset($_POST['username']) && isset($_POST['password'])){

    $phone = trim($_POST['username']);
    $password = trim($_POST['password']);




    $fields = array(
        'phone'=>$phone,
        'password'=>$password,
    );
//     print_r($fields);




        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url."riderlogin",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>json_encode($fields),
        CURLOPT_HTTPHEADER => array(
            "Accept: application/json",
            "Content-Type: application/json",
//            "APIKEY: ".$apikey,
//            "Authorization: Bearer ".$token
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $response_array = json_decode($response, true);

//    print_r($response_array);

//    if ($response_array['status'] == 1001){
//
//        session_name('sargasrider');
//        session_start();
////        $_SESSION['token'] = $response['token'];
//        $_SESSION['first_name'] = $response_array['first_name'];
//        $_SESSION['last_name'] = $response_array['data']['last_name'];
//        $_SESSION['rider_id'] = $response['rider_id'];
//        $_SESSION['branch_id'] = $response['sb_id'];
//    }



}else{

    $response_array['status'] == 1005;
    $response_array['message'] == 'Blank Data';



}

echo json_encode($response_array);