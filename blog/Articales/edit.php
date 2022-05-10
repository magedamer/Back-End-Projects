<?php

require '../helpers/dbConnection.php';
require '../helpers/functions.php';

# Get Data related to id ......
$id = $_GET['id'];

$sql = "select * from blog where id = $id";
$op = mysqli_query($con, $sql);

if (mysqli_num_rows($op) == 1) {
    $data = mysqli_fetch_assoc($op);
} else {
    $_SESSION['Message'] = ['Message' => 'Access Denied'];
    header('Location: ' . url('Articales/index.php'));
}

# Fetch Role Data .....
$sql = 'select * from blog';
$articaleData = mysqli_query($con, $sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CODE ......
    $title   = Clean($_POST['title']);
    $content = Clean($_POST['content']);
    //$password = Clean($_POST['password']);
    $date    = Clean($_POST['date']);
    $cat_id  = $_POST['cat_id'];


    # Validation ......
    $errors = [];
    # Validate Name
    if (!validate($title, 1)) {
        $errors['title'] = 'Field Required';
    } elseif (!validate($name, 7)) {
        $errors['title'] = 'Invalid String';
    }

    # Validate Email
    if (!validate($content, 1)) {
        $errors['content'] = 'Field Required';
    } elseif (!validate($content, 2 ,10)) {
        $errors['content'] = 'length must be >= 10';
    }

    # Validate role_id
    if (!validate($cat_id, 4)) {
        $errors['cat_id'] = 'Invalid Role';
    }

    # Validate phone
    if (!validate($date, 1)) {
        $errors['date'] = 'Field Required';
    }

    # Validate image
    if (validate($_FILES['image']['name'], 1)) {
        $tmpPath = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        $imageSize = $_FILES['image']['size'];
        $imageType = $_FILES['image']['type'];

        $exArray = explode('.', $imageName);
        $extension = end($exArray);

        $FinalName = rand() . time() . '.' . $extension;

        $allowedExtension = ['png', 'jpg'];

        if (!validate($extension, 5)) {
            $errors['Image'] = 'Error In Extension';
        }
    }

    if (count($errors) > 0) {
        $_SESSION['Message'] = $errors;
    } else {
        // db ..........

        // old Image
        $OldImage = $data['image'];

        if (validate($_FILES['image']['name'], 1)) {
            $desPath = './uploads/' . $FinalName;

            if (move_uploaded_file($tmpPath, $desPath)) {
                unlink('./uploads/' . $OldImage);
            }
        } else {
            $FinalName = $OldImage;
        }

        $sql = "update blog set title = '$title' , content = '$content',image = '$FinalName' , cat_id = $cat_id , date = '$date' where id = $id";
        $op = mysqli_query($con, $sql);

        if ($op) {
            $_SESSION['Message'] = ['message' => 'Raw Updated'];

            header('Location: ' . url('Articales/index.php'));
          //  exit();
        } else {
            $_SESSION['Message'] = ['message' => 'Error Try Again'];
        }
    }
}

require '../layouts/header.php';
require '../layouts/nav.php';
require '../layouts/sidNav.php';
?>


<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">




            <h1 class="mt-4">Dashboard</h1>
            <ol class="breadcrumb mb-4">

                <?php

                              if(isset($_SESSION['Message'])){
                                foreach($_SESSION['Message'] as $key => $val){
                                echo '* '.$key.' : '.$val;
                                }
                                unset($_SESSION['Message']);
                            }else{

                            ?>

                <li class="breadcrumb-item active">Dashboard/Edit Articale</li>

                <?php } ?>
            </ol>



            <div class="card-body">


                <div class="container">



                    <form action="edit.php?id=<?php echo $data['id']; ?>" method="post" enctype="multipart/form-data">




                        <div class="form-group">
                            <label for="exampleInputName">Title</label>
                            <input type="text" class="form-control" id="exampleInputName" name="title"
                                value="<?php echo $data['title']; ?>" aria-describedby="" placeholder="Enter title">
                        </div>


                        <div class="form-group">
                            <label for="exampleInputEmail">Content</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" name="content"
                                value="<?php echo $data['content']; ?>" aria-describedby="emailHelp" placeholder="Enter content">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputPassword"> date</label>
                            <input type="text" class="form-control" value="<?php echo $data['date']; ?>"
                                id="exampleInputPassword1" name="date" placeholder="phone">
                        </div>


                        <div class="form-group">
                            <label for="exampleInputPassword">Category</label>

                            <select class="form-control" name="cat_id">
                                <?php
                               while($cat_details = mysqli_fetch_assoc($articaleData)){
                                ?>
                                <option value="<?php echo $cat_details['id']; ?>" <?php if ($data['cat_id'] == $cat_details['id']) {
                                    echo 'selected';
                                 } ?>><?php echo $cat_details['title']; ?></option>
                                <?php } ?>
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="exampleInputPassword">Image</label><br>
                            <input type="file" name="image">
                        </div>

                        <img src="./uploads/<?php echo $data['image']; ?>" height="80" width="80"><br>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>


                </div>
            </div>


        </div>
    </main>


    <?php

    require '../layouts/footer.php';
    ?>
