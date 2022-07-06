<?php
require('./ayarlar.php');
?>
<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        <title>Subdomain manager</title>    
    </head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include("./themes/white/components/navbar.php");?>
    <form method="POST" class="w-50 zrooter-forms ust-form">
        <center>
            <legend>Alt alan adı Oluşturucu</legend>
            <div class="mb-3 zrooter-inputs">
                <label for="name" class="form-label">Alt alan adı</label>
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <input type="name" name="name" class="form-control" id="name" aria-describedby="name">
                    </div>
                    <div class="col-auto">
                        <span id="nameInline">
                            .<?php echo $MainDomain; ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="mb-3 zrooter-inputs">
                <label for="ip" class="form-label">IP Adresi</label>
                <input type="ip" name="ip" class="form-control" id="ip">
            </div>
            <button type="submit" class="btn btn-primary">Gönder</button>
            <div id="or" class="form-text">
                veya
            </div>
        </center>
    </form>
    <form method="POST" class="w-50 zrooter-forms alt-form">
        <center>
            <legend>Alt alan adı düzenleyici</legend>
         
            <div class="mb-3 zrooter-inputs">
                <label for="token" class="form-label">Token</label>
                <input type="token" name="token" class="form-control" id="token">
            </div>
            <button type="submit" class="btn btn-primary">Gönder</button>
            <div id="powered" class="form-text">
                Powered by zRooter & Oculeth
            </div>
        </center>
    </form>

    <?php include("./themes/white/components/footer.php");?>
</html>
<?php
require('./database.php');
error_reporting(0);
$url = "https://api.cloudflare.com/client/v4/zones/$ZONE_ID/dns_records";
if($_POST) {
    $token = $_POST['token'] || "";
    if(!empty($token)) {
        // header("Location: ./duzenle.php?token=".$_POST['token']);
        echo "<script>window.location = './domain-duzenle?token=".$_POST['token']."'</script>";
    }
    else if(!empty($_POST['name']) || !empty($_POST['ip'])){
        // $proxied = $_POST['proxyStatus'] == "Proxied" ? true : false;
        // echo $proxied;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $headers = array(
            "X-Auth-Email: $XAuthEmail",
            "X-Auth-Key: $XAuthKey",
            "Content-Type: application/json"
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER,  $headers);

        $data = json_encode(array(
            "type" => "A",
            "name" => "".$_POST['name']."",
            "content" => "".$_POST['ip']."",
            "ttl" => 1,
            "proxied" => false
        ));

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        $respDecode = json_decode($resp);
        
        curl_close($curl);
        if(count($respDecode->errors) >= 1) {
            $errors = $respDecode->errors;
            if($errors[0]->message == "Record already exists.") {
                echo "<script>Swal.fire({
                    title: 'Hata!',
                    text: 'Böyle bir Alt alan zaten bulunuyor.',
                    icon: 'error',
                    confirmButtonText: 'Tamam'
                })</script>";
                // die();
            } else {
                echo "<script>Swal.fire({
                    title: 'Hata!',
                    text: 'Bilinmeyen hata! ".$errors[0]->message."',
                    icon: 'error',
                    confirmButtonText: 'Tamam'
                })</script>";
                // die();
            }
        } else {
            // $base64Token = base64_encode($_POST['ip']."zrooter_".$_POST['name']);
            // $md5Base64Token = md5($base64Token);
            $hashed_token = md5(base64_encode(random_bytes(16)));
            $hasSub = $db->query("SELECT * FROM subdomains WHERE subdomain = '".$_POST['name']."'");
            $row = $hasSub->fetch();
            if($row) {
                echo "<script>Swal.fire({
                    title: 'Hata!',
                    text: 'Böyle bir Alt alan zaten bulunuyor.',
                    icon: 'error',
                    confirmButtonText: 'Tamam'
                })</script>";
            } else {
                $stmnt = $db->query("INSERT INTO subdomains (subdomain, token) VALUES ('".$_POST['name']."', '".$hashed_token."')");
                echo "<script>Swal.fire({
                    title: 'Başarılı!',
                    html: '<div>Başarıyla alt alan adınız oluşturuldu.</div><div> Token: <u>$hashed_token</u></div>',
                    icon: 'success',
                    confirmButtonText: 'Tamam'
                })</script>";
            }
            // die();
        }
    } else {

        echo "<script>Swal.fire({
            title: 'Bildiri!',
            text: 'Lütfen boş alan bırakmayın.',
            icon: 'info',
            confirmButtonText: 'Tamam'
        })</script>";
        // die();
    }

}
?>

<style>
    .zrooter-inputs {
        width: 40%;
    }
    .zrooter-forms {
        top: 0;
        margin-left: auto;
        margin-right: auto;
        left: 0;
        right: 0;
    }
    .alt-form {
        border: 0.2px solid #c7c7c7;
        border-top: 0px;
        border-radius: 0px 0px 10px 10px; 
        margin-top: -16px;
        margin-bottom: 5%;
    }
    .ust-form {
        margin-top: 5%;
       
        border: 0.2px solid #c7c7c7;
        border-bottom: 0px;
        border-radius: 10px 10px 0px 0px; 
    }
</style>