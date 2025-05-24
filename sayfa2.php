<?php 
session_start();
if(isset($_POST['sil'])) {
    $silinecek_id = $_POST['id']; 
     $satirlar = file("video.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $yeni_satirlar = [];
    foreach($satirlar as $satir) {
        $parcala = explode("|", $satir);
        if($parcala[0] != $silinecek_id) {
            $yeni_satirlar[] = $satir;
        }   
    }
    file_put_contents("video.csv", implode("\n", $yeni_satirlar)."\n", LOCK_EX);
      header("Location: sayfa2.php");
    exit();
}
if(!isset($_SESSION["kullaniciadi"])){
    header("Location: sayfa1.php");
    exit();
}
$video=[];
if(($dosya=fopen("video.csv","r"))!==false){
    while(($satir=fgetcsv($dosya,1000,"|"))!==false){
        if($satir[4]=="0"){
            $video[]= $satir;
        }
    }
    fclose ($dosya);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sayfa2</title>
    <link rel="stylesheet" href="sayfa.css">
</head>
<body>
    <div class="logo"><h1>VİDEO ADMİN</h1></div>
    <div class="video">
        
<a href="sayfa3.php" class="yeniekle">+ Yeni Video Ekle</a>

    <?php foreach ($video as $video): ?>
    <?php
    $id = $video[0];
    $link = $video[1];
    $tanim = $video[2];
    $tarih = $video[3];
    ?>
    <div class="pp" style="display:flex; align-items:center; border:1px solid #ccc; padding:10px; margin-bottom:10px; border-radius:5px;">
        <div class="sol" style="flex: 0 0 auto; margin-right:15px;">
            <a href="<?= htmlspecialchars($link) ?>" target="_blank">
                <img src="https://img.youtube.com/vi/<?= htmlspecialchars($id) ?>/hqdefault.jpg" alt="Video Görseli" style="width:120px; height:auto; display:block;">
            </a>
        </div>

        <div style="flex: 1 1 auto;">
            <div class="video-aciklama" style="margin-bottom:5px;">
                <strong><?= htmlspecialchars($tanim) ?></strong><br>
                <small style="color: gray;"><?= htmlspecialchars($tarih) ?></small>
            </div>
        </div>


        <div style="flex: 0 0 auto; margin-left: 15px; text-align: right; display: flex; 
gap: 5px;">
            <form action="sayfa4.php" method="GET" style="display:inline;">
                <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                <button name="guncelle" type="submit" style="padding:5px 10px; background: #007BFF; color:#fff; border:none; cursor:pointer; border-radius:3px;">Güncelle</button>
            </form>
            <form  method="POST" style="display:inline; margin-left:5px;" onsubmit="return confirm('Bu videoyu silmek istediğinize emin misiniz?');">
                <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                <button name="sil" type="submit" style="padding:5px 10px; background:#e74c3c; color:#fff; border:none; cursor:pointer; border-radius:3px;">×</button>
            </form>
        </div>
    </div>
<?php endforeach; ?>

</body>
</html>