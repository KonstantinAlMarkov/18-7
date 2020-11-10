<?php
require 'config.php';
$fileName = $_GET['fileName'];
$commentsFile = COMMENT_DIR.'/'.$fileName.'.txt';
setlocale(LC_TIME, 'ru-Latn');

$errors = [];
$messages = [];

//Если дали комментарий
if (!empty($_POST['comment'])){
    //обработка комментария
    $comment = $_POST['comment'];
    //Валидация коммента
    if($comment === '') {
        $errors[] = 'Вы не ввели текст комментария';
    } else {
        $symbToReplace = array("\r\n","\r","\n","\\r","\\n","\\r\\n");
        $comment = str_replace($symbToReplace, "", $comment);
        $comment =  date("D M j G:i:s T Y").":".$comment;
        //запись комментария
        file_put_contents($commentsFile,$comment."\n",FILE_APPEND);
        array_push($messages, 'Комментарий был добавлен');
        var_dump($messages);
    }

}
//Удаление комментария
if (!empty($_POST['commentToDelete'])){
    if (file_exists($commentsFile)) 
    {
        $toDelete = trim($_POST['commentToDelete']);
        //считываем файл в массив   
        $file=file($commentsFile);
        //удаляем все переносы строк в значениях массива
        $symbToReplace = array("\r\n","\r","\n","\\r","\\n","\\r\\n");
        $newFile = str_replace($symbToReplace, "", $file);   
        //ищем подходящее значение в массиве   
        if (($key = array_search($toDelete, $newFile, FALSE)) !== false) {
            //если нашли, то удаляем ключ
            unset($newFile[$key]);       
            //пишем в файл, ставим перенос строки после каждой записи в массиве и ставя дополнительный перенос после последней записи
            $newContent=implode("\n", $newFile);
            $newContent = $newContent."\n";
            file_put_contents($commentsFile, $newContent);   
            array_push($messages, 'Комментарий был удалён'); 
        } else echo $errors[] = 'Комментарий не найден';
    }
    else {
        $errors[] = 'Файл не найден';
    }
    unset($_POST['comment']);
}

?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Изображение:<?php echo $fileName?></title>
</head>
<body>
    <div class="container">
        <div class="row header justify-content-center">
            <div class="col-4  headerCol">
                <h1><a href="<?php echo URL; ?>">Галерея изображений</a></h1>
            </div>
        </div>   
        <div class="row fileListH">
            <div class="col-12">
                <h2>Изображение:<?php echo $fileName ?></h2>
                <hr>
            </div>           
        </div>
        <div class="row justify-content-center imgRow">
            <img src="<?php echo URL.UPLOAD_DIR. '/' .$fileName;?>" class="img-thumbnail" alt="<?php echo URL.UPLOAD_DIR. '/' .$fileName;?>">
        </div>
        <div class="row comments">
            <div class="col-12"><hr>
                <div class="row commentsList">
                    <div class="col-md-12">
                        <div class="row">
                            <h2>Комментарии:</h2><br>
                        </div>

                        <?php if (!file_exists($commentsFile)||!count(file($commentsFile))): ?>
                            <h3>Комментариев пока что нет</h3>
                        <?php endif; ?>
                        <?php if (file_exists($commentsFile) && count(file($commentsFile))): ?> 
                            <ul>                       
                                <?php 
                                    foreach(file($commentsFile) as $string):?>    
                                    <li>
                                        <form method="POST">
                                            <input type="hidden" name="commentToDelete" value="<?php echo $string;?>">
                                            <button type="submit" class="close" aria-label="Удалить" id="deleteComment">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </form>   
                                        <?php echo $string; ?> 
                                    </li>    
                                <?php endforeach; ?> 
                            </ul> 
                        <?php endif;?>        

                    <div>
                </div>
                <div class="row comment form">
                    <div class="col-md-12">
                        <form method="POST">
                            <div class="form-group">
                                <label for="commentField">Дайте свой комментарий:</label>
                                <textarea class="form-control" id="FormControlTextarea" rows="3" name="comment"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Комментрировать</button>
                        </form>
                    </div>
                <div> 
            </div>
        </div>
        <!-- Вывод сообщений об успехе/ошибке -->
        <div class="row log">
            <div class="col-12">
            <hr>
                <div class= "row">
                    <h2>Лог:</h2>            
                </div>           
                <div class= "row">
                    <?php foreach ($errors as $error): ?>
                        <div class="col-4">
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>  
                <div class= "row">
                    <?php foreach ($messages as $message): ?>
                        <div class="col-4">                            
                            <div class="alert alert-success"><?php echo $message; ?></div>
                        </div>                       
                    <?php endforeach; ?>            
                </div>                  
            </div>
        </div>
        <div class="row footer">
            <div class="col-12"><hr>
                    <h3>Константин Марков</h3>
                    <p>&#169 2020. Все права защищены</p>
            </div>
        <div> 
    </div>
</body>
</html>