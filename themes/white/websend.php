<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        <title>Websend/WebSender tester</title>    
    </head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include("./themes/white/components/navbar.php");?>
    
    <center>
    <form method="POST" class="w-50 zrooter-forms">
        <legend>Websend/Websender Tester</legend>
        <div class="mb-3">
            <label for="sunucuAdresi" class="form-label">Websend/er adresi</label>
            <input type="text" id="sunucuAdresi" name="sunucuAdresi" class="zrooter-inputs form-control" placeholder="127.0.0.1">
        </div>
        <div class="mb-3">
            <label for="şifre" class="form-label">Websend/er Şifre</label>
            <input type="text" id="şifre" name="şifre" class="zrooter-inputs form-control" placeholder="123qwe">
        </div>
        <div class="mb-3">
            <label for="port" class="form-label">Websend/er Port</label>
            <input type="text" id="port" name="port" class="zrooter-inputs form-control" placeholder="9876">
        </div>
        <div class="mb-3">
        <label for="eklenti" class="form-label">Test edilcek eklenti</label>
        <select id="eklenti" name="eklenti" class="form-select zrooter-inputs">
            <option>WebSend</option>
            <option>WebSender</option>
        </select>
        </div>
        <div class="mb-3">
        </div>
        <button type="submit" class="btn btn-primary">Gönder</button>
        <div id="powered" class="form-text">
            Powered by zRooter & Oculeth
        </div>
    </form>
    </center>
    <?php include("./themes/white/components/footer.php");?>
</html>
<?php
    if($_POST) {
        
        $Adres = $_POST['sunucuAdresi'];
        $Şifre = $_POST['şifre'];
        $Port = $_POST['port'];
        $Eklenti = $_POST['eklenti'];
        if(!empty($Adres) && !empty($Şifre) && !empty($Port)) {
            if($Eklenti == "WebSend") {
                require_once("./class/WebsendAPI.php");
                $ws = new Websend("$Adres");
                $ws->password = "$Şifre";
                $ws->port = "$Port";
                if($ws->connect()) {
                    $ws->doCommandAsConsole("broadcast WebSend bağlantısı başarılı.");
                    echo "<script>Swal.fire({
                        title: 'Başarılı!',
                        text: 'WebSend testi başarıyla gerçekleştirildi.',
                        icon: 'success',
                        confirmButtonText: 'Tamam'
                    })</script>";
                } else {
                    echo "<script>Swal.fire({
                        title: 'Hata!',
                        text: 'WebSend testi başarısız.',
                        icon: 'error',
                        confirmButtonText: 'Tamam'
                    })</script>";
                }
            } else if($Eklenti == "WebSender") {
                require_once("./class/WebsenderAPI.php");
                $ws = new WebsenderAPI("$Adres", "$Şifre", "$Port");
                if($ws->connect()) {
                    $ws->sendCommand("broadcast WebSender bağlantısı başarılı.");
                    echo "<script>Swal.fire({
                        title: 'Başarılı!',
                        text: 'WebSender testi başarıyla gerçekleştirildi.',
                        icon: 'success',
                        confirmButtonText: 'Tamam'
                    })</script>";
                } else {
                    echo "<script>Swal.fire({
                        title: 'Hata!',
                        text: 'WebSender testi başarısız.',
                        icon: 'error',
                        confirmButtonText: 'Tamam'
                    })</script>";
                }
            }
        } else {
            echo "<script>Swal.fire({
                title: 'Bildiri!',
                text: 'Lütfen boş alan bırakmayın.',
                icon: 'info',
                confirmButtonText: 'Tamam'
            })</script>";
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
        margin-top: 5%;
        margin-bottom: 5%;
        border: 0.2px solid #c7c7c7;
        padding: 10px;
        border-radius: 10px
    }

</style>