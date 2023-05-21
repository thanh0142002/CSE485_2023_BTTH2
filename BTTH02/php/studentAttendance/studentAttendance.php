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
    
    //echo $userName."+".$userPass;

    

    // Connect to database
    try {
        $conn = new PDO("mysql:host=localhost;dbname=btth02", 'root', '');
    } catch (PDOException $pe) {
        die("Could not connect to the database $dbname :" . $pe->getMessage());
    }

    $stmt_nameSV = $conn->prepare('SELECT id_sv,name from students where id_user in (SELECT id_user from users where username = :username)');
    $stmt_nameSV -> bindValue(':username',$userName,PDO::PARAM_STR);
    $stmt_nameSV->execute();
    // Lấy danh sách kết quả
    $names = $stmt_nameSV->fetchAll();


   $sql_classes =  $conn->prepare('SELECT classname from classes');
   $sql_classes->execute();
   $classes = $sql_classes->fetchAll();
    //query
    //$sql = "SELECT * FROM users WHERE username == $username AND password == $password"

    //query classes
    //$sql
    //Notification điểm danh thành công
    $Noti ="";
// Add status "attend" to database
if($_SERVER['REQUEST_METHOD']=='POST' ){
    $id_sv = $_POST['id_sv'];
    $classname = $_POST['classname'];
   
    //Get id class
    $sql = "select id_class from classes where classname = :classname";
    $stmtIDClass = $conn -> prepare($sql);
    $stmtIDClass -> bindValue(':classname',$classname,PDO::PARAM_STR);
    $stmtIDClass ->execute();
    $IDclass = $stmtIDClass ->fetch();

    $sql = "select id_attendance from attendance ORDER BY id_attendance DESC LIMIT 1";
    $stmtIDatt = $conn -> prepare($sql);
    $stmtIDatt ->execute();
    $IDatt = $stmtIDatt ->fetch();
    

    $currentDateTime = date('Y-m-d');
    // Add status "attend" to db
    $insert_sql = "insert into attendance (id_attendance, day,id_class,id_sv,status) values(?,?,?,?,?)";
    $insert_attendance = $conn -> prepare($insert_sql);
    $insert_attendance -> bindValue(1,$IDatt[0]+1,PDO::PARAM_STR);
    $insert_attendance -> bindValue(2,$currentDateTime,PDO::PARAM_STR);
    $insert_attendance-> bindValue(3,$IDclass[0],PDO::PARAM_STR);
    $insert_attendance -> bindValue(4,$id_sv,PDO::PARAM_STR);
    $insert_attendance -> bindValue(5,'attend',PDO::PARAM_STR);
    if($insert_attendance ->execute() === TRUE){
        $Noti = "Điểm danh thành công";
    }
        }
   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Điểm danh sinh viên</title>
    <!--Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

</head>
<body>
    <!--Title-->
    <h3 class="text-center text-uppercase text-primary">Điểm Danh Sinh Viên</h3>

    <!--Attendance-->
    <div class="container">
  
    <form action="./studentAttendance.php" method="POST">
    <div class="mt-3 form-group">
        <label>Mã Sinh Viên</label>
            <input class="form-control" name="id_sv" value="<?= $names[0][0]; ?>" type="hidden" >
            <input class="form-control" name="" type="text" placeholder="<?= $names[0][0]; ?>" disabled = 'disabled'>
        </div>

    <div class="mt-3 form-group">
        <label>Tên Sinh Viên</label>
            <input class="form-control" name="name" type="hidden" value="<?= $names[0][1]; ?>">
            <input class="form-control" name="name" type="text" placeholder="<?= $names[0][1]; ?>" disabled = 'disabled'>
    </div>

    <div class="mt-3 form-group">
        <label for="exampleFormControlSelect1">Chọn lớp điểm danh</label>
  
            <select name="classname" class="form-control" id="exampleFormControlSelect1">
           
           <?php
        foreach ($classes as $class){
?>
                
                <option><?=$class[0]?></option>
                <?php }?>
            </select>
        
    </div>
   
    <button type="submit" class="mt-3 btn btn-primary">Submit</button>
    <p class= "text-danger"><?=$Noti?></p>
    </form>
    </div>


    <!--Navigate to the showInformationAttendance.php-->
    <div class="container mt-5">
        <div class="text-center">
            <a href="showInformationAttendance.php">Xem thông tin điểm danh</a>
        </div>
    </div>

    <!--Script bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>