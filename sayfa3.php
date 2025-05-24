<?php 
session_start();

if(isset($_POST["vazgec"])){
    header("location: sayfa2.php");
    exit();
}

if(isset($_POST["kaydet"])){
    $link= trim($_POST["link"]);
    $tanim= trim($_POST["tanim"]);
    
    $parsedUrl = parse_url($link);
    $id = null;

    if (isset($parsedUrl['host'])) {
        if (strpos($parsedUrl['host'], 'youtube.com') !== false) {
            parse_str($parsedUrl['query'] ?? '', $urlparams);
            $id = $urlparams['v'] ?? null;
        } elseif (strpos($parsedUrl['host'], 'youtu.be') !== false) {
            $id = ltrim($parsedUrl['path'], '/');
        } else {
            $id = null;
        }
    }

    if($id && $link && $tanim){
        $tarih= date("Y-m-d H:i:s");
        $yenisatir= "$id|$link|$tanim|$tarih|0\n";
        file_put_contents("video.csv", $yenisatir, FILE_APPEND | LOCK_EX);

        header("location: sayfa2.php");
        exit();
    } else {
        $hata="Hatalı video linki veya tanım boş!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sayfa3</title>
    <link rel="stylesheet" href="sayfa.css">
</head>
<body>
    <div class="logo"><h1>VİDEO ADMİN</h1></div>
    <div class="kutu">
        <form method="post"  style="position: absolute; top: 10px; right: 10px;">
             <button class="iptal" type="submit" name="vazgec">x</button>
        </form>
        <h2>Yeni Video Ekle</h2>
        
        <form  method="post">
       
             <label>Video Linki:</label>
            <input type="text" name="link"  required>
             <label>Video adı:</label>
            <input type="text" name="tanim" required>
            <button class="kaydet" type="submit" name="kaydet">kaydet</button> <br><br>
           
        </form>
    </div>
    
        