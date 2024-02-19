<?php
session_start();
include("../dbcon.php");
include("../includes/header.php");
include("../includes/admin_sidebar.php");
include("../includes/topbar.php");
include("../message.php");

if (isset($_POST['activate_user_btn'])) {
    $user_id = $_POST['user_id'];
    $activate_query = "UPDATE tbl_user SET user_status = 1 WHERE user_email = '$user_id'";
    mysqli_query($con, $activate_query);
}

if (isset($_POST['deactivate_user_btn'])) {
    $user_id = $_POST['user_id'];
    $deactivate_query = "UPDATE tbl_user SET user_status = 0 WHERE user_email = '$user_id'";
    mysqli_query($con, $deactivate_query);
}
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h3 class="m-0 font-weight-bold text-primary">Customer</h3>
            <form action="author.php">
                <button type="submit" class="close" style="margin-top: 1;">
                    <div class="btn btn-secondary">Add Customer</div>
                </button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <?php
        include("../message.php");
        ?>
        <div class="table-responsive">
            <?php
            $query = "SELECT * FROM tbl_user WHERE role_id = 0";
            $query_run = mysqli_query($con, $query);
            $row_number = 1;
            ?>

            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>SI No.</th>
                        <th>Email</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($query_run) > 0) {
                        while ($row = mysqli_fetch_assoc($query_run)) {
                            ?>
                            <tr>
                                <td><?php echo $row_number; ?></td>
                                <td><?php echo $row['user_email']; ?></td>
                                <td>
                                    <form action="#" method="post">
                                        <input type="hidden" name="user_id" value="<?php echo $row['user_email']; ?>">
                                        <?php if ($row['user_status'] == 1): ?>
                                            <button type="submit" name="deactivate_user_btn" class="btn btn-danger">Deactivate</button>
                                        <?php else: ?>
                                            <button type="submit" name="activate_user_btn" class="btn btn-success">Activate</button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                            </tr>
                            <?php
                            $row_number++;
                        }
                    } else {
                        echo "No Record Found";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include("../includes/script.php");
include("../includes/footer.php");
?>
