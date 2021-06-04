<?php
require_once "config.php";

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST"){

    // kullanıcı adı boşsa verilecek uyarı.
    if(empty(trim($_POST["username"]))){
        $username_err = "Lütfen kullanıcı adınızı giriniz.";
    }
    else{
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // veritabanında tanımladığım ismi, burada tanımladığım değişkene atıyorum.
            $param_username = trim($_POST['username']);

            // execute ediyoruz.
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1)   // 1 ya da 0 döndürecek 1 se zaten kayıtlı olduğunu söyleyecektir.
                {
                    $username_err = "Bu Kullanıcı Adı zaten kayıtlı."; 
                }
                else{
                    $username = trim($_POST['username']); 
                }
            }
            else{
                echo "Sistem çöktü.";
            }
        }
    }
    mysqli_stmt_close($stmt);


// Şifre işlemlerini kontrol ediyoruz.

if(empty(trim($_POST['password']))){
    $password_err = "Lütfen şifrenizi giriniz.";
}
elseif(strlen(trim($_POST['password'])) < 5){    // strlen ile sayıyoruz.
    $password_err = "Şifrenizde yeterli karakter bulunmuyor.";
}
else{
    $password = trim($_POST['password']);
}

// Şifreler eşleşmiyorsa;

if(trim($_POST['password']) !=  trim($_POST['confirm_password'])){
    $password_err = "Şifreniz eşleşmiyor.";
}


// hata yoksa veritabanı bağlantısı yapılır.
if(empty($username_err) && empty($password_err) && empty($confirm_password_err))
{
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt)
    {
        mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

        // Set these parameters
        $param_username = $username;
        $param_password = password_hash($password, PASSWORD_DEFAULT);

        // Try to execute the query
        if (mysqli_stmt_execute($stmt))
        {
            header("location: login.php");
        }
        else{
            echo "Sistem çöktü.";
        }
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
}

?>




<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Yunus Özbucak Login Sistemi</title>
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">Php Login System</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
  <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="#">Ana Sayfa <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="register.php">Kayıt Ol</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="login.php">Giriş Yap</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Çıkış Yap</a>
      </li>

      
     
    </ul>
  </div>
</nav>

<div class="container mt-4">
<h3>Aşağıdan Kayıt Olun:</h3>
<hr>
<form action="" method="post">
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputEmail4">Kullanıcı Adı</label>
      <input type="text" class="form-control" name="username" id="inputEmail4" placeholder="Kullanıcı Adı">
    </div>
    <div class="form-group col-md-6">
      <label for="inputPassword4">Şifre</label>
      <input type="password" class="form-control" name ="password" id="inputPassword4" placeholder="Şifre">
    </div>
  </div>
  <div class="form-group">
      <label for="inputPassword4">Şifre Doğrula</label>
      <input type="password" class="form-control" name ="confirm_password" id="inputPassword" placeholder="Şifre Doğrula">
    </div>
  <div class="form-group">
    <label for="inputAddress2">Adres</label>
    <input type="text" class="form-control" id="inputAddress2" placeholder="Apartman, Mahalle, Semt vs">
  </div>
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputCity">Şehir</label>
      <input type="text" class="form-control" id="inputCity">
    </div>
    <div class="form-group col-md-4">
      <label for="inputState">İlçe</label>
      <select id="inputState" class="form-control">
        <option selected>İstanbul</option>
        <option>Ankara</option>
        <option>İzmir</option>
        <option>Balıkesir</option>
        <option>Konya</option>
        <option>İstanbul</option>
        <option>Rize</option>
        <option>Diyarbakır</option>
        <option>Kocaeli</option>
        
      </select>
    </div>
    <div class="form-group col-md-2">
      <label for="inputZip">Posta Kodu</label>
      <input type="text" class="form-control" id="inputZip">
    </div>
  </div>
  <div class="form-group">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gridCheck">
      <label class="form-check-label" for="gridCheck">
        Beni Hatırla
      </label>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Kayıt Ol</button>
</form>
</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
