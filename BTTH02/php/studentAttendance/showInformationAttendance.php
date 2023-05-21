<?php
    // //$username = $_POST['username'] ?? '';
    // //$password = $_POST['password'] ?? '';
    // if(isset($_POST['username'])&&isset($_POST['password'])){
    //     echo $username;
    //     
    // 
    //     echo $password;}

    session_start();
   
    $userName = $_SESSION['username']; 
    $userPass = $_SESSION['password'];

    // Connect to database
    try {
        $conn = new PDO("mysql:host=localhost;dbname=btth02", 'root', '');
       
    } catch (PDOException $pe) {
        die("Could not connect to the database $dbname :" . $pe->getMessage());
    }
    //query
    //id_sv
    $stmt_idSV = $conn->prepare('SELECT id_sv from students where id_user in (SELECT id_user from users where username = :username)');
    $stmt_idSV -> bindValue(':username',$userName,PDO::PARAM_STR);
    $stmt_idSV->execute();
    $id_sv = $stmt_idSV->fetchAll();
   
    
   

    $stmt_date = $conn->prepare('SELECT id_class,day, status from attendance where(status = "attend" AND id_sv = :id_sv)');
    $stmt_date->bindValue(':id_sv',$id_sv[0][0],PDO::PARAM_STR);
    $stmt_date->execute();
    // Lấy danh sách kết quả
    $dates = $stmt_date->fetchAll();


    $stmt = $conn->prepare('SELECT id_class,classname from classes');
   // $stmt->bindValue(':id_sv',$id_sv[0][0],PDO::PARAM_STR);
    $stmt->execute();
    // Lấy danh sách kết quả
    $classes = $stmt->fetchAll();


    $stmt_nameSV = $conn->prepare('SELECT name from students where id_sv = :id_sv');
    $stmt_nameSV->bindValue(':id_sv',$id_sv[0][0],PDO::PARAM_STR);
    $stmt_nameSV->execute();
    // Lấy danh sách kết quả
    $names = $stmt_nameSV->fetchAll();

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Điểm Danh</title>
     <!--Bootstrap-->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>
    <h2 class="mt-5 mb-5 text-primary text-center text-uppercase">Kiểm tra thông tin điểm danh</h2>
    <div class="container">
<table class="table">
  <thead>
    <tr>
    <th scope="col">Mã Lớp</th>
      <th scope="col">Tên Lớp</th>
      <th scope="col">Ngày học</th>
      <th scope="col">Tên sinh viên</th>
      <th scope="col">Trạng thái điểm danh</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($dates as $date){
       $count=0;
    ?>
     
            <tr>
                
                <td ><?= $date[0]; ?></td>
                <?php
                foreach($classes as $class){
                    if ($class[0]==$date[0]){
                        $className = $class[1];
                    ?>
                <td><?= $class[1]; ?></td>
                <?php }} ?>
                <td><?= $date[1]; ?></td>
                <td><?= $names[0][0]; ?></td>
                <td><?= $date[2]; ?></td>
               
            </tr>
            <?php
        $count = $count + 1;
        } ?>    
  </tbody>
</table>
</div>
   
     <!--Script bootstrap-->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>