<?php

require_once __DIR__ . '/vendor/autoload.php';

$client = new Google_Client();
$client->setApplicationName('Google Sheets and PHP');
$client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
$client->setAccessType('offline');
$client->setAuthConfig(__DIR__ . '/credentials.json');
$sheet_service = new Google_Service_Sheets($client);

$spreadsheetIds = [
    'CC' => "1tC58nEQP0pl2DTJep6WDYQyaQ8h7vZ5L0PTUiwwScxo",
    'CN' => "1cpUPGZ6ttIDzAt4wYuO45QG0ECEK6FWhZS3MPAZY8Nc",
    'CS' => "1mc5vQrUBZ1-lEn2Q7lflyk2VZc8t_bYEhZo8as49W-Q",
    'USJ' => "189XllsQOjAAyOerFviy8p6_47GVqwln-7xd1-fqlzuk",
    'Kandy' => "1K0PGKsazbryyUu-dU0AXl6CKaXvVaCpdTU7Exk7vmog",
    'Ruhuna' => "16ZAknZg6_9k2tQ7-XvK3W7E07cTKslElPEruQmUt3uY",
    'SLIIT' => "1C2hp8HK-dGJ3gIBbVIPrcoPytehwnAzli98lB7JwEIs",
    'NSBM' => "1ZtCUyYHtdg2NSnsdIK1raC2MHI9mHG9bK870vvobZhc"
];

// Other
$spreadsheetId = "1EP5geS3_l_ASMt6A0kgW_9MilmsEs_gJNTdWdDKCHSc";

function append($values, $entity){

    global $sheet_service;
    global $spreadsheetId;
    global $spreadsheetIds;

    if (array_key_exists($entity, $spreadsheetIds)){
        $spreadsheetId = $spreadsheetIds[$entity];
    }

    $body = new Google_Service_Sheets_ValueRange([
        'values' => $values
    ]);

    $all_values = $values;
    array_splice( $all_values[0], 1, 0, [$entity] ); // splice in at position 3
    $all_body = new Google_Service_Sheets_ValueRange([
        'values' => $all_values
    ]);

    $params = [
        'valueInputOption' => 'USER_ENTERED'
    ];

    $range = 'Responses';

    //Append to all sheet
    $result = $sheet_service->spreadsheets_values->append("1QiAy1Npxp7tBzzhE64WmzB-KWsqwGcO_PtuGcSxSd-8",
        $range, $all_body, $params);

    //Append to entity sheet (or other)
    $result = $sheet_service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
    if($result->getUpdates()->getUpdatedCells() == 9) {
        return true;
    }

    return false;

}



?>
