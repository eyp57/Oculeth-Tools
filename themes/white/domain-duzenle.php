
<html>
    <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        
    </head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include("./themes/white/components/navbar.php");?>
<?php

require("./database.php");
require("./ayarlar.php");
$url = "https://api.cloudflare.com/client/v4/zones/$ZONE_ID/dns_records";
if($_GET) {
    if(!empty($_GET['token'])) {
        $token = $_GET['token'];
        $stmnt = $db->query("SELECT * FROM subdomains WHERE token = '$token'");
        $row = $stmnt->fetch();
        if($row) {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            
            $headers = array(
                "X-Auth-Email: $XAuthEmail",
                "X-Auth-Key: $XAuthKey",
                "Content-Type: application/json"
            );
            curl_setopt($curl, CURLOPT_HTTPHEADER,  $headers);
            $resp = curl_exec($curl);
            $respDecode = json_decode($resp);
            foreach($respDecode->result as $i => $value) {
                $result = $respDecode->result[$i];
                if($result->name == $row['subdomain'].".$MainDomain") {
                    $ip = $result->content;
                }
                // echo json_encode($result);
            }
            echo '
<form method="POST" class="w-50 zrooter-forms">
    <center>
        <legend>Alt alan adı düzenleyici</legend>
        
        <div class="mb-3 zrooter-inputs">
            <label for="token" class="form-label">Token</label>
            <input readonly="readonly" value="'.$token.'" type="token" name="token"  class="form-control" id="token">
        </div> 
        <div class="mb-3 zrooter-inputs">
            <label for="subdomain" class="form-label">Subdomain</label>
            <input readonly="readonly" type="subdomain" value="'.$row['subdomain'].'.'.$MainDomain.'" name="subdomain" class="form-control" id="subdomain">
        </div>
        <div class="mb-3 zrooter-inputs">
            <label for="ip" class="form-label">IP Adresi</label>
            <input type="ip" value="'.$ip.'" name="ip" class="form-control" id="subdomain">
        </div>
        <button type="submit" class="btn btn-primary">Gönder</button>
        <div id="powered" class="form-text">
            Powered by zRooter & Oculeth
        </div>
    </center>
</form>
            ';
        } else {
            echo "<script>window.location = './index.php'</script>";
            die();
        }
    } else {
        echo "<script>window.location = './index.php'</script>";
        die();
    }
} else {
    echo "<script>window.location = './index.php'</script>";
    die();
}

if($_POST) {
    $token = $_POST['token'];
    $subdomain = $_POST['subdomain'];
    $ip = $_POST['ip'];
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    $headers = array(
        "X-Auth-Email: $XAuthEmail",
        "X-Auth-Key: $XAuthKey",
        "Content-Type: application/json"
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER,  $headers);
    $resp = curl_exec($curl);
    $respDecode = json_decode($resp);
    foreach($respDecode->result as $i => $value) {
        $result = $respDecode->result[$i];
        if($result->name == $subdomain) {
            $ipOnCF = $result->content;
            $resultRecordId = "".$result->id."";
        }
    }
    if($ipOnCF == $ip) {
        echo "<script>Swal.fire({
            title: 'Hata!',
            text: 'Yeni IP adresi öncekiyle aynı olamaz!',
            icon: 'info',
            confirmButtonText: 'Tamam'
        })</script>";
    } else {
        // echo $url."/".$resultRecordId;
        $curl = curl_init($url."/".$resultRecordId);
        curl_setopt($curl, CURLOPT_URL, $url."/".$resultRecordId);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $headers = array(
            "X-Auth-Email: $XAuthEmail",
            "X-Auth-Key: $XAuthKey",
            "Content-Type: application/json"
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = json_encode(array(
            "type" => "A",
            "name" => "".str_replace(".$MainDomain", "", $subdomain)."",
            "content" => "".$ip."",
            "ttl" => 1,
            "proxied" => false
        ));

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $resp = curl_exec($curl);
        $respDecode = json_decode($resp);
        
        curl_close($curl);
        // echo json_encode($respDecode);

        
        echo "<script>Swal.fire({
            title: 'Başarılı!',
            html: '<div>Başarıyla alt alan adınız güncellendi.</div><div> Token: <u>$token</u></div>',
            icon: 'success',
            confirmButtonText: 'Tamam'
        })</script>";
    
    }

    // echo json_encode($_POST);
}
?>
<?php include("./themes/white/components/footer.php");?>
<style>
    .zrooter-inputs {
        width: 40%;
    }
    .zrooter-forms {
        margin-top: 5%;
        margin-bottom: 5%;
        margin-left: auto;
        margin-right: auto;
        left: 0;
        border: 0.2px solid #c7c7c7;
        border-radius:10px; 
        right: 0;
    }
</style>

</html>