<?php

function mobileorder ($dispatch_id, $pm_id){

    require("../Connections/connect.php");

    mysqli_begin_transaction($connect, MYSQLI_TRANS_START_READ_WRITE);
    mysqli_autocommit($connect, FALSE);


    $pm_id  = mysqli_real_escape_string($connect, $pm_id);
    $dispatch_id  = mysqli_real_escape_string($connect, $dispatch_id);

    $getdis = "SELECT order_id, rider_id from dispatch where dispatch_id = '$dispatch_id' and status = 1";


    $getdisq = mysqli_query($connect, $getdis);
    $getdisc = mysqli_num_rows($getdisq);
    $getdisr = mysqli_fetch_array($getdisq);

    $order_id = $getdisr['order_id'];
    $rider_id = $getdisr['rider_id'];

    $tdate = date("Y-m-d H:i:s");


    //get assigned cylinder
    $ass= "SELECT cylinder_id from dispatched_orders where order_id = '$order_id' and dispatch_id = '$dispatch_id'";
    $assq = mysqli_query($connect, $ass);
    $getass = mysqli_num_rows($assq);

    $getassr = mysqli_fetch_array($getass);

    $cylinder_id = $getassr['cylinder_id'];



    $sql0 = "INSERT INTO payment (pm_id) VALUES ('$pm_id')";
//                     echo $sql0;
    if ($query0 = mysqli_query($connect, $sql0)) {
        $payment_id = mysqli_insert_id($connect);

//                            $sqlorder = "INSERT INTO orders (customer_id,  scheduled_status, scheduled_date, scheduled_time, amount_paid, order_status_id, payment_id, campaign_id, sb_id ) VALUES ('$customer_id', '$scheduled_status', '$scheduled_date', '$scheduled_time', '$price', '1', '$payment_id', '$campaign_id', '$branch')";
////             echo $sqlorder;
//                            if (mysqli_query($connect, $sqlorder)) {
//                                $order_id = mysqli_insert_id($connect);

        $sqlu = "UPDATE payment SET order_id = '$order_id', status = '1' WHERE payment_id ='$payment_id' ";
        if (mysqli_query($connect, $sqlu)) {

//                                    $sql = "insert into dispatch (order_id, rider_id, dispatch_date, status) values ('$order_id', '$rider_id', '$tdate', 1)";
//
//                                    if (mysqli_query($connect, $sql)) {
//
//                                        $dispatch_id = mysqli_insert_id($connect);

            $sqlu1 = "UPDATE orders SET payment_id = '$payment_id', order_status_id = '4', cylinder_id = '$cylinder_id' WHERE order_id = '$order_id'";
            if (mysqli_query($connect, $sqlu1)) {

//                                            $sql2 = "insert into dispatched_orders (dispatch_id, order_id, cylinder_id, rider_id, pickup_date, delivery_date, status) values ('$dispatch_id','$order_id', '$cylinder_id', '$rider_id', '', '', 1)";
////                                         echo $sql2;
//                                            if (mysqli_query($connect, $sql2)) {

//                                                $sqlu2 = "update orders set dispatch_id = '$dispatch_id', order_status_id = '3' where order_id = '$order_id'";
//                                                if (mysqli_query($connect, $sqlu2)) {

//                                                    $insert = "Insert into customer_cylinder (customer_id, cylinder_id, order_id, status) values ('$customer_id', '$cylinder_id', '$order_id',  1)";
//                                                    if (mysqli_query($connect, $insert)) {

                $sqlu3 = "UPDATE dispatch SET delivery_date = '$tdate', delivered_flag = 1 where dispatch_id = '$dispatch_id'";
                if (mysqli_query($connect, $sqlu3)) {

                    $sqlu4 = "UPDATE dispatched_orders SET delivery_date ='$tdate', status = 1 where dispatch_id = '$dispatch_id'";
                    if (mysqli_query($connect, $sqlu4)) {

//                                                                $sqlu5 = "UPDATE orders SET order_status_id ='4', cylinder_id = '$cylinder_id' where order_id = '$order_id'";

                        //confirm this
//                                                                if (mysqli_query($connect, $sqlu5)) {
//                                                                    $sqlu6 = "UPDATE cylinder_branch SET available_flag ='0' where cylinder_id = '$cylinder_id' and status = 1";
//                                                                    if (mysqli_query($connect, $sqlu6)) {

                        //check existing and retrive

                        $ret= "select customer_id, dispatch_id from orders where order_id = '$order_id'";
                        $retq = mysqli_query($connect, $ret);
                        $getret = mysqli_num_rows($retq);
                        $getretr = mysqli_fetch_array($getret);

                        $ordercustomer_id = $getretr['customer_id'];

                        //select all orders

                        $ord= "select cylinder_id, order_id, dispatch_id from orders where customer_id = '$ordercustomer_id' and order_id != '$order_id' order by order_id desc limit 1 ";
                        $ordq = mysqli_query($connect, $ord);
                        $getord = mysqli_num_rows($ordq);

                        if ($getord > 0) {
                            $getordr = mysqli_fetch_array($getord);

                            $retrivecylinder = $getordr['cylinder_id'];

                            //check last order before this and check if the customer has to retrieve
                        }

                            if ($retrivecylinder != null || $retrivecylinder != "") {
                                //check if cylinder code is valid and has not been picked up yet

                                //  $sqlc = "select cylinder_id, orders.dispatch_id, orders.order_id, return_flag from dispatch  ";
                                //  $sqlc .= " LEFT JOIN orders ON orders.order_id = dispatch.dispatch_id ";
                                //  $sqlc .= " LEFT JOIN cylinder ON cylinder.id = orders.cylinder_id";

                                $sqlc = "select cylinder_id, customer_id, orders.dispatch_id, orders.order_id, delivered_flag, return_flag from orders  ";

                                $sqlc .= " LEFT JOIN dispatch ON dispatch.dispatch_id = orders.dispatch_id ";

                                $sqlc .= " LEFT JOIN cylinder ON cylinder.id = orders.cylinder_id ";
                                $sqlc .= " WHERE orders.order_id = '$retrivecylinder' and dispatch.return_flag = 0";

                                // echo $sqlc;
                                $qc = mysqli_query($connect, $sqlc);
                                $c = mysqli_num_rows($qc);

                                if ($c > 0) {

                                                                        $sqlc1 = "select rider_id from dispatch where order_id ='$retrivecylinder'";
                                                                        $qc1 = mysqli_query($connect, $sqlc1);
                                                                        $r1 = mysqli_fetch_array($qc1);

                                                                        $rider_id2 = $r1['rider_id'];


                                    $r = mysqli_fetch_array($qc);
                                    $cylinder_id2 = $r['cylinder_id'];
                                    $dispatch_id2 = $r['dispatch_id'];
                                    $customer_id = $r['customer_id'];
                                    $order_id2 = $r['order_id'];
//        $rider_id = $r['rider_id'];

                                    $su = "UPDATE dispatch SET return_rider_id = '$rider_id', return_date = '$tdate', return_flag = 1, status = 2 where dispatch_id ='$dispatch_id2'";
                                    if ($qu = mysqli_query($connect, $su)) {

                                        $s0 = "UPDATE customer_cylinder SET status = '2' where customer_id = '$customer_id' and cylinder_id = '$cylinder_id2' and status = 1";

                                        if (mysqli_query($connect, $s0)) {

                                            $s2 = "UPDATE cylinder_branch SET available_flag = '1' , status = 2 where cylinder_id = '$cylinder_id2'";
                                            if (mysqli_query($connect, $s2)) {

                                                $s1 = "INSERT INTO dispatched_orders (dispatch_id, order_id, cylinder_id, rider_id, pickup_date, status) VALUES ('$dispatch_id2', '$order_id2', '$cylinder_id2', '$rider_id', '$tdate', 0)";
                                                if ($q1 = mysqli_query($connect, $s1)) {
//
//
                                                    mysqli_commit($connect);
                                                    $response_array['status'] = 1001;
                                                    $response_array['message'] = "Order Successfully Delivered";
                                                    $response_array['order_id'] = $order_id;
                                                    $response_array['payment_id'] = $payment_id;
//


                                                } else {

                                                    mysqli_rollback($connect);
                                                    $response_array['status'] = 2001;
                                                    $response_array['message'] = "Could Not add dispatched orders for retrieve cylinder";
                                                }

                                            } else {

                                                mysqli_rollback($connect);
                                                $response_array['status'] = 2001;
                                                $response_array['message'] = "Could Not Update old cylinder branch";
                                            }
                                        } else {

                                            mysqli_rollback($connect);
                                            $response_array['status'] = 2001;
                                            $response_array['message'] = "Could Not Update the old customer cylinder ";
                                        }

                                    } else {

                                        mysqli_rollback($connect);
                                        $response_array['status'] = 2001;
                                        $response_array['message'] = "Could Not Update the old dispatch";
                                    }


                                } else {

                                    mysqli_rollback($connect);
                                    $response_array['status'] = 2001;
                                    $response_array['message'] = "Could Not get retrieved cylinder details";
                                }


                            } else {

                                mysqli_commit($connect);
                                $response_array['status'] = 1001;
                                $response_array['message'] = "Order Successfully Delivered";
                                $response_array['order_id'] = $order_id;
                                $response_array['payment_id'] = $payment_id;
                            }

                    } else {

                        mysqli_rollback($connect);
                        $response_array['status'] = 2001;
                        $response_array['message'] = "Could Not Update cylinder branch";
                    }
                } else {

                    mysqli_rollback($connect);
                    $response_array['status'] = 2001;
                    $response_array['message'] = "Could Not Update Order";

                }

            } else {
                mysqli_rollback($connect);
                $response_array['status'] = 2001;
                $response_array['message'] = "Could Not Update Dispatched Orders";
            }
        } else {
            mysqli_rollback($connect);
            $response_array['status'] = 2001;
            $response_array['message'] = "Could Not Update Dispatch";
        }
    } else {
        mysqli_rollback($connect);
        $response_array['status'] = 2001;
        $response_array['message'] = "Could Not insert Customer cylinder ";
    }


    return $response_array;


}