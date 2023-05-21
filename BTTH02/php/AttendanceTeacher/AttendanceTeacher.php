<?php
try {
    $dsn = "mysql:host=localhost;dbname=btth02";
    $username = "root";
    $password = "";
    
    $connection = new PDO($dsn, $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $query = "SELECT classname FROM classes";
    $result = $connection->query($query);
} catch (PDOException $pe) {
    die("Could not connect to the database $dbname :" . $pe->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Điểm Danh</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
    <h2 class="mt-5 mb-5 text-danger text-center text-uppercase">Thông tin điểm danh 
   
    </h2>
    
    <div class="container my-5">
        <form method="post">
            <div class="d-grid gap-2 d-md-flex justify-content">
                <select class="" name="class" id="class">
                    <?php
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $selected = (isset($_POST['class']) && $_POST['class'] == $row['classname']) ? 'selected' : '';
                        echo "<option value='" . $row['classname'] . "' " . $selected . ">" . $row['classname'] . "</option>";
                    }
                    ?>
                </select>
                <button class="btn btn-danger" name="submit">Submit</button>
                <button  class="btn btn-danger" name="thoat"><a  style="text-decoration: none;color:white;" href="index.php">Thoát</a></button>
            </div>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Mã Lớp</th>
                    <th scope="col">Tên Sinh Viên</th>
                    <th scope="col">Ngày học</th>
                    <th scope="col">Trạng thái điểm danh</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_POST['class'])) {
                    $class = $_POST['class'];

                    try {
                        $query = "SELECT attendance.id_class, students.name AS student_name, attendance.day, attendance.status
                                  FROM attendance
                                  INNER JOIN classes ON attendance.id_class = classes.id_class
                                  INNER JOIN students ON attendance.id_sv = students.id_sv
                                  WHERE classes.classname = :class";
                         $statement = $connection->prepare($query);
                         $statement->bindParam(':class', $class);
                         $statement->execute();

                        // Kiểm tra xem có dữ liệu được trả về hay không
                        if ($statement->rowCount() > 0) {
                            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . $row['id_class'] . "</td>";
                                echo "<td>" . $row['student_name'] . "</td>";
                                echo "<td>" . $row['day'] . "</td>";
                                echo "<td>" . $row['status'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr>";
                            echo "<td colspan='4'>Không có kết quả phù hợp.</td>";
                            echo "</tr>";
                        }
                    } catch (PDOException $e) {
                        die("Kết nối không thành công: " . $e->getMessage());
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
