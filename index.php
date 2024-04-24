<?php
$localhost = "localhost";
$dbname = "edebisozler";
$username = "root";
$password = "";
$previousPoemId = null;

try {
    $pdo = new PDO("mysql:host=$localhost;dbname=$dbname", $username, $password);

    do {
        $sorgusiir = "SELECT * FROM siirler ORDER BY RAND() LIMIT 1";
        $stmt = $pdo->prepare($sorgusiir);
        $stmt->execute();
        $siir = $stmt->fetch(PDO::FETCH_ASSOC);

        // SONRAKİ ŞİİRİ ÇEKMEK İÇİN

        if (($sonraki = $siir["id"] + 1) > count($siir)) {
            $sonraki = 1;
        }
        else {
            $sonraki = $siir["id"] + 1;
        }

        $sorguSonrakiSiir = "SELECT * FROM siirler where id = $sonraki";
        $stmt = $pdo->prepare($sorguSonrakiSiir);
        $stmt->execute();
        $sonrakiSiir = $stmt->fetch(PDO::FETCH_ASSOC);

        // ÖNCEKİ ŞİİRİ ÇEKMEK İÇİN

        if (($onceki = $siir["id"] - 1) < 1) {
            $onceki = count($siir);
        }
        else {
            $onceki = $siir["id"] - 1;
        }

        $sorguOncekiSiir = "SELECT * FROM siirler where id = $onceki";
        $stmt = $pdo->prepare($sorguOncekiSiir);
        $stmt->execute();
        $oncekiSiir = $stmt->fetch(PDO::FETCH_ASSOC);

    } while ($previousPoemId !== null && $siir['id'] == $previousPoemId);

    $previousPoemId = $siir['id']; 

} catch (PDOException $e) {
    error_log("PDO Hatası: " . $e->getMessage(), 3, "error.log");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şiir</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <div class="form">
        <h2 style="text-align: center">Veritabanından rastgele şiirleri çekip bu şiirleri gösterme</h2>
        <div class="content">
            <h2><?php  echo $siir['baslik']. " / ". $siir['yazar']?></h2>
            <br><br>
            <div class="icerik">
                <?php  
                $kitalar = explode("\n", $siir['icerik']);
                foreach ($kitalar as $kita) {
                    echo "$kita<br>";
                }

                ?>
            </div>
            <hr>
            <div class="bilgiler">
            <?php  
                $bilgiler = explode("\n", $siir['bilgi']);
                foreach ($bilgiler as $bilgi) {
                    echo "$bilgi<br>";
                }
                
            ?>
            </div>
            <div class="footer">
                <div class="sol">
                    <h1>Mert Yılmaz</h1>
                </div>
                <hr class="dik">
                <div class="sag">
                    <h3><b>Bir Önceki Eser: <?php echo $oncekiSiir["baslik"] . " / ". $oncekiSiir["yazar"] ?></b></h3>
                    <hr>
                    <a href=""><<< -- Rastgele Bir Eser -- >>></a>
                    <hr>
                    <h3>Bir Sonraki Eser: <?php echo $sonrakiSiir["baslik"] . " / ". $sonrakiSiir["yazar"]  ?></h3>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="app.js"></script>
</html>

