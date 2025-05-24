<?php
session_start();
$hata="";
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $kullaniciadi=trim($_POST["kullaniciadi"]);
    $sifre=trim($_POST["sifre"]);
    $dosya=fopen("user.csv","r");
    $dogrugiris=false;
    while(($satir=fgetcsv($dosya,1000,"|"))!==false){
        if($satir[0]==$kullaniciadi && $satir[1]==$sifre){
            $dogrugiris=true;
            break;
        }
    }
    fclose($dosya);
    if($dogrugiris){
        $_SESSION["kullaniciadi"]=$kullaniciadi;
        header("Location: sayfa2.php");
    }
    else{
        $hata="kullanıcı adı veya şifre yanlış";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sayfa1</title>
    <link rel="stylesheet" href="sayfa.css">
</head>
<body>
    <div class="logo"><h1>VİDEO ADMİN</h1></div>
    <div class=" kutu">
    <form method="post">
        <h2>GİRİŞ</h2>
        <label for="kullaniciadi">kullanıcı adı:</label>
        <input type="text" name="kullaniciadi" id="kullaniciadi"><br><br><br>
        <label for="sifre">şifre:</label>
        <input type="password" name="sifre" id="sifre">
        <br><br><br>
        <button type="submit">Giriş Yap</button>
    </form>

    <?php if ($hata): ?>
        <p  class="hata" style="color:red;"><?php echo $hata; ?></p>
    <?php endif; ?>
    </div>
</body>
</html>