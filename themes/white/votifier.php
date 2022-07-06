<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        <title>NuVotifier tester</title>    
    </head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include("./themes/white/components/navbar.php");?>
    
    <center>
    <form method="POST" class="w-50 zrooter-forms">
        <legend>NuVotifier Tester</legend>
        <div class="mb-3">
            <label for="sunucuAdresi" class="form-label">Kullanıcı adı</label>
            <input type="text" id="username" name="username" class="zrooter-inputs form-control" placeholder="zRooter">
        </div>
        <div class="mb-3">
            <label for="sunucuAdresi" class="form-label">NuVotifier adresi</label>
            <input type="text" id="sunucuAdresi" name="sunucuAdresi" class="zrooter-inputs form-control" placeholder="127.0.0.1">
        </div>
        <div class="mb-3">
            <label for="publicKey" class="form-label">NuVotifier Public Key</label>
            <textarea style="resize: none" rows="3" type="text" id="publicKey" name="publicKey" class="zrooter-inputs form-control" placeholder="12341234qwe"></textarea>
        </div>
        <div class="mb-3">
            <label for="port" class="form-label">NuVotifier Port</label>
            <input type="text" id="port" name="port" class="zrooter-inputs form-control" placeholder="8192">
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
        $PublicKey = $_POST['publicKey'];
        $Username = $_POST['username'];
        $Port = $_POST['port'];
        if(!empty($Adres) && !empty($PublicKey) && !empty($Port) && !empty($Username)) {
            require_once("./class/Server.php");
            require_once("./class/Vote.php");
            date_default_timezone_set('Europe/Istanbul');
            $vote = new Vote("Oculeth Tester", $Username, $Adres, date('m/d/Y h:i:s a', time()));
            $server = new Server($Adres, $Port, $PublicKey);
            $server->sendVote($vote);
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