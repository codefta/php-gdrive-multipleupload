<?php
//set your key path
putenv('GOOGLE_APPLICATION_CREDENTIALS='vendor/google/apiclient-services/[YOUR KEY FILE].json');

//config
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->setApplicationName("Gdrive");
$client->setScopes(['https://www.googleapis.com/auth/drive']);

//multiple file from form upload
for($i = 0; $i < count($_FILES['file']['name']); $i++){
        $nama[$i] = $_FILES['file']['name'][$i];
        $type[$i] = $_FILES['file']['type'][$i];
        $content[$i] = file_get_contents($_FILES['file']['tmp_name'][$i]);
}

//Service Google 
$driveService = new Google_Service_Drive($client);
$rootFolder = '[Your Folder ID]'; //root folder

/* please make root folder first in gdrive and change permission to anyone can view and edit. After that copy the id and assigment to $rootFolder */

//make folder for register account
$folderId = new Google_Service_Drive_DriveFile(array(
  'name' => '[your folder name]',
  'mimeType' => 'application/vnd.google-apps.folder',
  'parents' => array($rootFolder)
));

$folder = $driveService->files->create($folderId, array(
  'fields' => 'id'
));

//uploading multiple file into google drive
for($i = 0; $i < count($_FILES['file']['name']); $i++){
  $file[$i] = new Google_Service_Drive_DriveFile(array(
    'name' => $nama[$i],
    'parents' => array($folder->id)
  ));

  $result[$i] = $driveService->files->create($file[$i], array(
    'data' => $content[$i],
    'mimeType' => $type[$i],
    'uploadType' => 'multipart',
    'fields' => 'id'
  )); 
}
