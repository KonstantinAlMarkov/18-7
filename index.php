<?php
require 'config.php';
//var_dump($_FILES);
//получить список имеющихся изображений
$fileList=scandir(UPLOAD_DIR);
$fileList = array_filter($fileList, function ($file) {
    return !in_array($file, ['.', '..', '.gitkeep']);});
//var_dump($fileList);
if (!empty($_FILES)){
    foreach($_FILES as $file){
        $uploadedName=$file['tmp_name'][0];
        $fileDir=UPLOAD_DIR.'/'.$file['name'][0];
        move_uploaded_file($uploadedName,$fileDir);
    };
    //скидываем значение
    unset($_FILES);
}

if (!empty($_POST['name'])){
    $filePath=UPLOAD_DIR.'/'.$_POST['name'];
    $commentPath=COMMENT_DIR.'/'.$_POST['name'].'.txt';
    if (file_exists($filePath)) {
        unlink($filePath);
    } 
    if (file_exists($commentPath)) {
        unlink($commentPath);
    } 
    //скидываем значение
    unset($_POST['name']);
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
    <title>Галерея изображений</title>
</head>
<body>
    <div class="container">
        <div class="row header justify-content-center">
            <div class="col-4  headerCol">
                <h1>Галерея изображений</h1>
            </div>
        </div>
        <div class="row fileListH ">
            <div class="col-12 justify-content-center">
                 <h2>Список файлов:</h2>
                 <hr>
            <div>
        </div>
        <div class="row no-gutters imgRow">
            <!-- генерация галереи из фалов -->
            <?php if (!count($fileList)): ?>
                <h3>Картинок нет</h3>
            <?php endif; ?>
            <?php if (count($fileList)): ?> 
                <?php foreach($fileList as $file):?>    
                    <div class="col-4  d-flex justify-content-center my-auto imgCol">
                        <a href="<?php echo URL."file.php?fileName=".$file;?>" title="Перейти к изображению">
                            <img src="<?php echo URL.UPLOAD_DIR. '/' .$file;?>" class="img-thumbnail" alt="<?php echo URL.UPLOAD_DIR. '/' .$file;?>">
                        </a>
                        <form method="POST">
                            <input type="hidden" name="name" value="<?php echo $file; ?>">
                            <button type="submit" class="close" aria-label="Удалить" id="deleteImg">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </form>
                    </div> 
                <?php endforeach; ?>
            <?php endif;?>            
        </div>
        <div class="row addFileForm">
            <div class="col-12"><hr>
                <form method="POST" enctype="multipart/form-data">        
                    <div class="row justify-content-center">
                        <div class="col-4">
                            <p>Загрузите вашу картинку:</p>
                        </div>
                        <div class="col-4">
                            <input type="file" class="form-control-file" id="addNewImage" name="files[]">
                        </div>   
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary">Загрузить</button>
                        </div>                      
                    </div>    
                </form>
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