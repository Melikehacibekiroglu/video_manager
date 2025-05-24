<?php
session_start();

$dosya = "video.csv";
$hata = "";
$id = $_GET['id'] ?? null;
$link = "";
$tanim = "";

// YouTube video ID'sini hem youtube.com hem youtu.be linklerinden alır
function getYoutubeVideoId($url) {
    $parsed = parse_url($url);
    if (isset($parsed['host'])) {
        if (strpos($parsed['host'], 'youtu.be') !== false) {
            return ltrim($parsed['path'], '/');
        } elseif (strpos($parsed['host'], 'youtube.com') !== false) {
            parse_str($parsed['query'] ?? '', $query);
            return $query['v'] ?? null;
        }
    }
    return null;
}

if (!$id) {
    header("Location: sayfa2.php");
    exit();
}

$satirlar = file($dosya, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$videoSatiri = null;

foreach ($satirlar as $satir) {
    $parcala = explode("|", $satir);
    if ($parcala[0] == $id) {
        $videoSatiri = $parcala;
        break;
    }
}

if (!$videoSatiri) {
    header("Location: sayfa2.php");
    exit();
}

if (isset($_POST['guncelle'])) {
    $linkYeni = trim($_POST['link']);
    $tanimYeni = trim($_POST['tanim']);

    $idYeni = getYoutubeVideoId($linkYeni);

    if ($idYeni && $linkYeni && $tanimYeni) {
        foreach ($satirlar as $index => $satir) {
            $parcala = explode("|", $satir);
            if ($parcala[0] == $id) {
                $tarih = date("Y-m-d H:i:s");
                $satirlar[$index] = "$idYeni|$linkYeni|$tanimYeni|$tarih|0";
                break;
            }
        }
        file_put_contents($dosya, implode("\n", $satirlar) . "\n", LOCK_EX);

        header("Location: sayfa2.php");
        exit();
    } else {
        $hata = "Geçersiz video linki veya tanım boş!";
    }
} else {
    $link = $videoSatiri[1];
    $tanim = $videoSatiri[2];
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8" />
    <title>Video Güncelle</title>
    <link rel="stylesheet" href="sayfa.css" />
</head>

<body>
    <div class="logo">
        <h1>VİDEO ADMİN</h1>
    </div>
    <div class="kutu">
        <h2>Video Güncelle</h2>
        <?php if ($hata): ?>
            <div class="hata"><?= htmlspecialchars($hata) ?></div>
        <?php endif; ?>
        <form method="post">
            <label>Video Linki:</label>
            <input type="text" name="link" value="<?= htmlspecialchars($link) ?>" required />
            <label>Video Adı:</label>
            <input type="text" name="tanim" value="<?= htmlspecialchars($tanim) ?>" required />
            <button type="submit" name="guncelle" class="kaydet">Güncelle</button>
        </form>
    </div>
</body>

</html>
