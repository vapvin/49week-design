<?php



include "db.php";

$target_dir = "./uploads/";
$target_file = $target_dir . basename($_FILES["design"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["design"]["tmp_name"]);
    if($check !== false) {
        // echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "이미지 파일이 아닙니다.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "이미 존재하는 이미지입니다.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["design"]["size"] > 50000000000000000000) {
    echo "파일크기가 초과되었습니다.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "JPG, JPEG, PNG, GIF 파일만 업로드 할 수 있습니다.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "업로드에 실패했습니다.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["design"]["tmp_name"], $target_file)) {
        $title = $_POST["title"];
        $company = $_POST["company"];
        $type = $_POST["type"];

	
        $filename = $_FILES["design"]["name"];
        $imgurl = "https://d-sian.kr/uploads/". $_FILES["design"]["name"];
        $size = $_FILES["fileToUpload"]["size"];

       
        //images 테이블에 이미지정보를 저장하자.
        // $sql = "insert into images(filename, imgurl, size) values('$filename','$imgurl','$size')";
        // mysqli_query($conn,$sql);
        $sql = mysqli_query($conn,"INSERT INTO images (filename, image_url, size, title, company, type) values('".$filename."', '".$imgurl."', '".$size."', '".$title."', '".$company."', '".$type."')");
        echo '<script>alert("추가되었습니다."); history.back();</script>';

    } else {
        echo "<p>업로드중 에러가 발생했습니다.</p>";
        // echo "<br><button type='button' onclick='history.back()'>돌아가기</button>";
    }
}

?>


