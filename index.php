<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.2/css/bootstrap.min.css" integrity="sha512-CpIKUSyh9QX2+zSdfGP+eWLx23C8Dj9/XmHjZY2uDtfkdLGo0uY12jgcnkX9vXOgYajEKb/jiw67EYm+kBf+6g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>PHP e MYSQL</title>
</head>
<body>
    <?php

        define('DB_SERVERNAME', 'localhost:3306');
        define('DB_USERNAME', 'root');
        define('DB_PASSWORD', 'root');
        define('DB_NAME', 'db-university');

        $conn = new mysqli(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);

        if($conn && $conn->connect_error){
            echo('Impossibile accedere... riprova più tardi');
            die();
        }

        echo('connection OK!');

        if ( isset($_GET['detail']) ){
            $detail_id = $_GET['detail'];

            $sql = "SELECT `students`.`name`,`students`.`surname`,`students`.`email`, `exam_student`.`vote`, `courses`.`name` AS `course_name`, `courses`.`year`, `courses`.`cfu` FROM `exam_student` INNER JOIN `courses` ON `courses`.`id` = `exam_student`.`exam_id` INNER JOIN `students` ON `students`.`id` = `exam_student`.`student_id` WHERE `student_id` = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $detail_id);
            $stmt->execute();


            $detail_result = $stmt->get_result();

            if ($detail_result && $detail_result->num_rows>0){
                echo('<div class="container">');
                ?>
                    <div class="row">
                        <div class="col">Surname</div>
                        <div class="col">Name</div>
                        <div class="col">email</div>
                        <div class="col">Course Name</div>
                        <div class="col">Year</div>
                        <div class="col">CFU</div>
                        <div class="col">Vote</div>
                    </div>
                    <?php
                while( $vote_detail = $detail_result->fetch_assoc() ){
                    ?>
                    <div class="row">
                        <div class="col"><?= $vote_detail['surname'] ?></div>
                        <div class="col"><?= $vote_detail['name'] ?></div>
                        <div class="col"><?= $vote_detail['email'] ?></div>
                        <div class="col"><?= $vote_detail['course_name'] ?></div>
                        <div class="col"><?= $vote_detail['year'] ?></div>
                        <div class="col"><?= $vote_detail['cfu'] ?></div>
                        <div class="col"><?= $vote_detail['vote'] ?></div>
                    </div>
                    <?php
                }
                echo('</div>');
             }elseif($detail_result){
                ?>
                <div>Non ci sono voti per questo studente</div>
                <?php
            }
            ?>
                <div>
                      <a href="./index.php">back</a>
                </div>
            
            <?php
            


            die();
        }



        $sql = 'SELECT `id`, `name`, `surname`, `email` FROM `students` ORDER BY `surname`, `name` LIMIT 50';

        $students_result = $conn->query($sql);

        if($students_result && $students_result->num_rows > 0){
            echo('<div class="container">');
            while( $student = $students_result->fetch_assoc() ){
                ?>
                <div class="row">
                    <div class="col"><?= $student['surname'] ?></div>
                    <div class="col"><?= $student['name'] ?></div>
                    <div class="col"><?= $student['email'] ?></div>
                    <div class="col"><a href="./index.php?detail=<?=$student['id'] ?>">Vedi voti</a></div>
                </div>
                <?php
            }
            echo('</div>');
        }elseif($students_result){
            ?>
            <div>Non ci sono studenti iscritti al momento...</div>
            <?php
        }
        else{
            echo('error...');
            die();
        }
    ?>
    
</body>
</html>