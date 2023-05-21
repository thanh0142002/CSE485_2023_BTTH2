<?php 
session_start();
$alert='';
 if($_SERVER['REQUEST_METHOD']=='POST'){
try
{
     $userName = $_POST['username'];
     $userPass = $_POST['password'];
 
    $_SESSION['username'] = $userName;
    $_SESSION['password'] = $userPass;

   
    $conn = new PDO("mysql:host=localhost;dbname=btth02", 'root', '');
    $sql = "select * from users where username = :username and password = :password";
    
    $stmt = $conn -> prepare($sql);
    $stmt -> bindValue(':username',$userName,PDO::PARAM_STR);
    $stmt -> bindValue(':password',$userPass,PDO::PARAM_STR);
    $stmt ->execute();
    $member = $stmt ->fetchAll();
    
    if($stmt -> rowCount()==1 and end($member[0])==1){
        header('Location: php/studentAttendance/studentAttendance.php');
    }
    else if($stmt -> rowCount()==1 and end($member[0])==0)
    {
      header('Location: php/AttendanceTeacher/AttendanceTeacher.php'); 
    }
    else {
       $alert = 'Vui lòng nhập đúng tài khoản hoặc mật khẩu!';
    }
} catch(PDOException $e){
    echo 'Error: ' .$e->getMessage();
}
 }?>
<!DOCTYPE html>
<html>
<head>
  <title>Đăng nhập</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f8f9fa;
    }
    
    .container {
      max-width: 400px;
      margin-top: 100px;
      border: 1px solid #ced4da;
      border-radius: 5px;
      padding: 20px;
      background-color: #fff;
    }
    
    .container h2 {
      margin-bottom: 20px;
      text-align: center;
    }
    
    .form-group label {
      font-weight: bold;
    }
    
    .btn-primary {
      width: 100%;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Đăng nhập</h2>
    <form action="./index.php" method="post">
      <div class="form-group">
        <input type="text" class="form-control" name="username" placeholder="Tên đăng nhập">
      </div>
      <div class="form-group">
        <input type="password" class="form-control" name="password" placeholder="Nhập mật khẩu">
        
      </div>
      <p style="color: red;"><?php echo $alert ?> </p>
      <button type="submit" class="btn btn-primary">Đăng nhập</button>
    </form>
  </div>
</body>
</html>
