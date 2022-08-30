<?php
$host   = "localhost";
$user   = "root";
$pass   = "0hMyL1nux$$";
$db     = "setpeople_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    echo "Koneksi gagal" . mysqli_connect_error();
}

$NIK            = "";
$Name           = "";
$Phone_number   = "";
$Email          = "";
$success        = "";
$error          = "";

if (isset($_GET['op'])) { // Untuk Edit
    $op = $_GET['op'];
} else {
    $op = "";
}
if ($op == 'delete') { // Untuk Delete    
    $No     = $_GET['No'];
    $sql1   = "delete from `Data Perorangan` where No = '$No'";
    $q1     = mysqli_query($conn, $sql1);
    if ($q1) {
        $success = "Successfully Deleted Data";
    } else {
        $error   = "Failed to Deleted Data";
    }
}
if ($op == 'edit') {
    $No             = $_GET['No'];
    $sql1           = "SELECT * FROM `Data Perorangan` WHERE No = '$No'";
    $q1             = mysqli_query($conn, $sql1);
    $r1             = mysqli_fetch_array($q1);
    $NIK            = $r1['NIK'];
    $Name           = $r1['Name'];
    $Phone_number   = $r1['Phone_number'];
    $Email          = $r1['Email'];

    if ($NIK == '') {
        $error = "Data tidak ditemukan";
    }
}
if (isset($_POST['save'])) { // Untuk Create
    $NIK          = $_POST['NIK'];
    $Name         = $_POST['Name'];
    $Phone_number = $_POST['Phone_number'];
    $Email        = $_POST['Email'];

    if ($NIK && $Name && $Phone_number && $Email) {
        if ($op == 'edit') { // Untuk Update
            $sql1       = "update `Data Perorangan` set NIK ='$NIK', Name ='$Name', Phone_number ='$Phone_number', Email ='$Email' where No = '$No'";
            $q1         = mysqli_query($conn, $sql1);
            if ($q1) {
                $success = "Successfully Updated";
            } else {
                $error = "Failed to updated";
            }
        } else { // Untuk Insert
            $sql1    = "insert into `Data Perorangan`(NIK,Name,Phone_number,Email) values ($NIK,'$Name','$Phone_number','$Email')";
            $q1      = mysqli_query($conn, $sql1);
            if ($q1) {
                $success = "Successfully entered data";
            } else {
                $error  = "Failed to enter data";
            }
        }
    } else {
        $error = "Please input the data";
    }
}


?>

<!DOCTYPE html>
<html>

<head>
    <title>Search Engine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 1200px
        }

        .card {
            margin-top: 10px;
        }

        .form-group {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <!-- CREATE/EDIT -->
    <div class="mx-auto">
        <form method="POST" action="">
            <div class="card">
                <div class="card-header">
                    Create/Edit
                </div>
                <div class="card-body">
                    <?php
                    if ($error) {
                    ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error ?>
                        </div>
                    <?php
                        header("refresh:3;url=index.php");
                    }
                    ?>
                    <?php
                    if ($success) {
                    ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $success ?>
                        </div>
                    <?php
                        header("refresh:3;url=index.php");
                    }
                    ?>
                    <div class="mb-3 row">
                        <label for="NIK" class="col-sm-2 col-form-label">NIK </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="NIK" value="<?php echo $NIK ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="Name" class="col-sm-2 col-form-label">Name </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="Name" value="<?php echo $Name ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="Phone Number" class="col-sm-2 col-form-label">Phone Number</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="Phone_number" value="<?php echo $Phone_number ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="Email" class="col-sm-2 col-form-label">Email </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="Email" value="<?php echo $Email ?>">
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="save" value="Save Data" class="btn btn-primary">
                    </div>
                </div>
            </div>
        </form>

        <!-- SEARCH -->
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
            <div class="form-group">

                <label for="sel1">Search :</label>
                <?php
                if (isset($_POST['kolom'])) {

                    if ($_POST['kolom'] == "NIK") {
                        $NIK = "selected";
                    } else if ($_POST['kolom'] == "Name") {
                        $Name = "selected";
                    } else if ($_POST['kolom'] == "Phone_number") {
                        $Phone_number = "selected";
                    } else {
                        $Email = "selected";
                    }
                }
                ?>
                <select class="form-control" name="kolom" required>
                    <option value="">Select The Column First</option>
                    <option value="NIK" <?php echo $NIK; ?>>NIK</option>
                    <option value="Name" <?php echo $Name; ?>>Name</option>
                    <option value="Phone_number" <?php echo $Phone_number; ?>>Phone Number</option>
                    <option value="Email" <?php echo $Email; ?>>Email</option>
                </select>
            </div>
            <!-- KEYWORD -->
            <?php
            $keyword = "";
            if (isset($_POST['keyword'])) {
                $keyword = $_POST['keyword'];
            }
            if (isset($_POST['keyword'])) {
                $keyword = trim($_POST['keyword']);

                $kolom = "";
                if ($_POST['kolom'] == "NIK") {
                    $kolom = "NIK";
                } else if ($_POST['kolom'] == "Name") {
                    $kolom = "Name";
                } else if ($_POST['kolom'] == "Phone_number") {
                    $kolom = "Phone_number";
                } else {
                    $kolom = "Email";
                }
            
                
                $sql = "SELECT * FROM `Data Perorangan` WHERE $kolom LIKE '%" . $keyword . "%' ORDER BY `NIK` ASC";
            } else {
               
                $sql = "SELECT * FROM `Data Perorangan` ORDER BY `NIK` ASC";
            }
            ?>
            <div class="form-group">
                <label for="sel1">Keyword :</label>
                <input type="text" name="keyword" value="<?php echo $keyword ?>" class="form-control" required />
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Select">
            </div>
        </form>
        <!-- PEOPLE DATA -->
        <div class="card">
            <div class="card-header text-white bg-secondary">
                People Data
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">NIK</th>
                            <th scope="col">Name</th>
                            <th scope="col">Phone Number</th>
                            <th scope="col">Email</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    <tbody>
                        <?php
                        $q2         = mysqli_query($conn, $sql);
                        $No         = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id             = $r2['No'];
                            $NIK            = $r2['NIK'];
                            $Name           = $r2['Name'];
                            $Phone_number   = $r2['Phone_number'];
                            $Email          = $r2['Email'];

                        ?>
                            <tr>
                                <th scope="row"><?php echo $No++ ?></th>
                                <td scope="row"><?php echo $NIK ?></td>
                                <td scope="row"><?php echo $Name ?></td>
                                <td scope="row"><?php echo $Phone_number ?></td>
                                <td scope="row"><?php echo $Email ?></td>
                                <td scope="row">
                                    <a href="index.php?op=edit&No=<?php echo $id ?>">
                                        <button type="button" class="btn btn-warning">Edit</button>
                                    </a>
                                    <a href="index.php?op=delete&No=<?php echo $id ?>" onclick="return confirm('Are you sure you want to delete data?')">
                                        <button type="button" class="btn btn-danger">Delete</button>
                                    </a>

                                </td>
                            </tr>
                        <?php
                        }


                        ?>
                    </tbody>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</body>

</html>