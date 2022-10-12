<?php
require_once("../../config/connectdb.php");
require_once("../../config/config.inc.php");
date_default_timezone_set("Asia/Bangkok");

if (isset($_POST['key']) && $_POST['key'] == 'form-reserve') {
    $value = $_POST['data'];

    $Time_reserve = $value['Time_reserve'];
    $date_reserve = $value['date_reserve'];

    $dateTime_reserve = $date_reserve . ' ' . $Time_reserve;
    $dateTime_reserve_end = $date_reserve . ' ' . $value['Time_reserve_end'];
    $id_hai = $value['id_hai'];
    $id_style = $value['id_style'];
    $id_user = $value['id_user'];

    $sql_insert = "INSERT INTO `reserve` (`id_reserve`, `id_style`, `id_user`, `id_hai`, `dateTime_reserve`, `dateTime_reserve_end`, `status`, `date_create`) VALUES 
                                            (NULL, '$id_style', '$id_user', '$id_hai', '$dateTime_reserve', '$dateTime_reserve_end', '2', current_timestamp());";

    $sql_search_xk = "SELECT * FROM `reserve` WHERE id_user = '$id_user' AND id_hai = '$id_hai' AND (dateTime_reserve <= '$dateTime_reserve' AND dateTime_reserve_end >= '$dateTime_reserve' );";

    $xk_rowdata = Database::query($sql_search_xk, PDO::FETCH_OBJ)->fetch(PDO::FETCH_OBJ);

    $dateN = date('Y-m-d');
    $TimeNP = date('H:i', strtotime('+0 minutes'));
    $TimeEP = date('H:i', strtotime('+100 minutes'));


    if ($xk_rowdata != null) {
        echo "error";
        return;
        exit;
    }





    if (Database::query($sql_insert)) {

        $sql_search = "SELECT *,DATE_FORMAT(re.dateTime_reserve, '%Y-%m-%dT%k:%i:00+07:00') as data_start,
                                DATE_FORMAT(re.dateTime_reserve_end, '%Y-%m-%dT%k:%i:00+07:00') as data_end 
                                FROM `reserve` as re 
                                INNER JOIN hairstyle as sty ON sty.id_style = re.id_style ;";
        $resultArray = array();
        // "start": "2020-09-12T10:30:00-05:00",
        //     "end": "2020-09-12T12:30:00-05:00"
        $json_txt = "";

        if ($show_tebelig = Database::query($sql_search, PDO::FETCH_ASSOC)) {
            foreach ($show_tebelig  as $row) {

                $new_row = [
                    "title" => $row['name_style'],
                    "start" => $row['data_start'],
                    "end" => $row['data_end'],
                ];

                array_push($resultArray, $new_row);
            }
            $json_txt =  json_encode($resultArray);
        } else {
            $json_txt =  json_encode($resultArray);
        }

        $Afile = "events.json";
        $myfile = fopen("../json/" . $Afile, "w") or die("");
        if (fwrite($myfile, $json_txt)) {
            // echo "json_Permission OK";
        }
        fclose($myfile);

        echo "success";
    } else {
        echo "error";
    }
}


if (isset($_POST['key']) && $_POST['key'] == 'delete_reserve') {

    $id = $_POST['id'];


    $sql_dete = "UPDATE `reserve` SET `status` = '3' WHERE `reserve`.`id_reserve` = '$id'";

    if (Database::query($sql_dete)) {
        echo "success";
    } else {
        echo "error";
    }
}


// geser
if (isset($_POST['key']) && $_POST['key'] == 'geser') {
    $id_hai = $_POST['id_hai'];
    // echo $id_code;
    $resultArray = array();
    try {
        $sql = "SELECT * FROM `hairstyle` WHERE `id_hai` = '$id_hai';";
        if ($show_tebelig = Database::query($sql, PDO::FETCH_ASSOC)) {
            foreach ($show_tebelig  as $row) {
                array_push($resultArray, $row);
            }
            echo json_encode($resultArray);
        } else {
            echo json_encode($resultArray);
        }
    } catch (Exception $e) {
        $resultArray = [
            "error" => $e->getMessage()
        ];
        echo json_encode($resultArray);
    }
}
