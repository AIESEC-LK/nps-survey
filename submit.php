<?php

    header('Access-Control-Allow-Origin: *');

    include_once "config.php";
    include_once "sheets.php";

    if (!isset($_POST['company']) ||
        !isset($_POST['type']) ||
        !isset($_POST['rad']) ||
        !isset($_POST['reason']) ||
        !isset($_POST['improve']) ||
        !isset($_POST['g-recaptcha-response'])){
        $output = json_encode(array('type' => 'fail', 'text' => "Incomplete form"));
        die($output);
    }

    if (!isset($_POST['contact'])){
        $_POST['contact'] = "";
    }

    if (!isset($_POST['name'])){
        $_POST['name'] = "";
    }


    if (!isset($_POST['email'])){
        $_POST['email'] = "";
    }

    if (!isset($_POST['entity'])){
        $_POST['entity'] = "Others";
    }

    if (!isset($_POST['track'])){
        $_POST['track'] = "";
    }

    $company = $_POST['company'];
    $type = $_POST['type'];
    $rad = $_POST['rad'];
    $reason = $_POST['reason'];
    $improve = $_POST['improve'];
    $contact = $_POST['contact'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    $entity = $_POST['entity'];
    $track = $_POST['track'];

    $gcaptcha = $_POST['g-recaptcha-response'];

    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $gcaptcha_private,
        'response' => $gcaptcha,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    );

    $curlConfig = array(
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => $data
    );

    $ch = curl_init();
    curl_setopt_array($ch, $curlConfig);
    $response = curl_exec($ch);
    curl_close($ch);

    $jsonResponse = json_decode($response);

    /*
    if (!$jsonResponse || !$jsonResponse->success === true) {
        $output = json_encode(array('type' => 'fail', 'text' => "Captcha invalid"));
        die($output);
    }
    */

    $date = new DateTime("now", new DateTimeZone('Asia/Colombo') );
    $timestamp = $date->format('Y-m-d H:i:s');

    $res = append([[$timestamp, $company, $type, $rad, $reason, $improve, $contact, $name, $email]], $entity);

    if ($res) {
        $output = json_encode(array('type' => 'success', 'text' => "Details successfully submitted."));
        die($output);
    } else{
        $output = json_encode(array('type' => 'fail', 'text' => "An unknown error occurred."));
        die($output);
    }



?>
