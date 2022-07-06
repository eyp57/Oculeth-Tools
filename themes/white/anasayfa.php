<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        <title>Oculeth Tools</title>    
    </head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <?php include("./themes/white/components/navbar.php");?>
    <div class="container px-4 py-5" id="featured-3">
      <h2 class="pb-2 border-bottom">Özellikler</h2>
      <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
        <div class="feature col">
          <div class="feature-icon d-inline-flex align-items-center justify-content-center bg-primary bg-gradient text-white fs-2 mb-3">
              <i class="bi bi-server"></i>
          </div>
          <h2>WebSend ve WebSender</h2>
          <p>Sitenizle uğraşmadan hızlıca WebSend ve WebSender bağlantınızı test edebilmenizi mümkünleştiriyoruz.</p>
          <i class="bi bi-link"></i>
          <a href="./websend.php" class="icon-link d-inline-flex align-items-center">
            WebSend/er Tester
          </a>
        </div>
        <div class="feature col">
          <div class="feature-icon d-inline-flex align-items-center justify-content-center bg-primary bg-gradient text-white fs-2 mb-3">
              <i class="bi bi-subtract"></i>
          </div>
          <h2>SubDomain Manager</h2>
          <p>Sunucunuz için ücretsiz bir şekilde subdomain oluşturabilmeniz amacıyla yapılmıştır. Ana domain: denilince.biz</p>
          <i class="bi bi-link"></i>
          <a href="./domain.php" class="icon-link d-inline-flex align-items-center">
            SubDomain Manager
          </a>
        </div>
        <div class="feature col">
          <div class="feature-icon d-inline-flex align-items-center justify-content-center bg-primary bg-gradient text-white fs-2 mb-3">
              <i class="bi bi-bar-chart-fill"></i>
          </div>
          <h2>Votifier</h2>
          <p>Herhangibi bir Vote sitesiyle uğraşmadan hızlıca Votifier bağlantınızı test edebilmenizi mümkünleştiriyoruz.</p>
          <i class="bi bi-link"></i>
          <a href="./votifier.php" class="icon-link d-inline-flex align-items-center">
            Votifier Tester
          </a>
        </div>
      </div>
      <h2 class="pb-2 border-bottom">Son oluşturulan domainler</h2>
      <center>
      <table class="table w-50 ">
        <thead>
          <tr>
            <th scope="col" class="w-50">#</th>
            <th scope="col" class="w-100">Alt alan adı</th>
          </tr>
        </thead>
        <tbody>
          <?php
            require("./database.php");
            require("./ayarlar.php");
            $dbSubDomains = $db->query("SELECT * FROM subdomains ORDER BY id DESC");
            foreach($dbSubDomains as $row) {
              echo "
              <tr>
                <td>".$row['id']."</td>
                <td>".$row['subdomain'].".".$MainDomain."</td>
              </tr>
              ";
            }
          ?>
        </tbody>
    </table>
    </center>
  </div> 
  <?php include("./themes/white/components/footer.php");?>
</html>
<style>
    .feature-icon {
        width: 4rem;
        height: 4rem;
        border-radius: .75rem;
    }
</style>