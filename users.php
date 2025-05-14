<style>
    /* General Styles */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: white;
        color: #212529;

    }

    .card {
        border: 1.2px solidrgb(169, 169, 169);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        background-color: white;
        padding: 15px;
        border-radius: 10px;
        margin-top: 50px;
    }

    .card-header {
        background-color: rgb(130, 213, 174);
        border-bottom: 1px solid #dee2e6;
        font-weight: 600;
        font-size: 1.1rem;
        padding: 1rem 1.25rem;
        border-radius: 10px;
    }

    .card-body {
        padding: 1.25rem;
    }

    label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    input[type="date"],
    .form-control,
    select {
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 0.6rem 0.75rem;
    }

    .btn {
        border-radius: 8px;
        font-size: 0.95rem;
        padding: 0.5rem 1.2rem;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .btn-success {
        background-color: #198754;
        border-color: #198754;
    }

    .btn-outline-primary,
    .btn-outline-danger {
        border-width: 1px;
    }

    .btn:hover {
        opacity: 0.9;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    th,
    td {
        padding: 0.75rem;
        border-bottom: 1px solidrgb(75, 238, 91);

    }

    th {
        background-color: rgb(65, 178, 125);
        font-weight: 600;
        text-align: left;
        color: white;

    }

    .table-responsive {

        overflow-x: auto;
    }

    @media (max-width: 768px) {
        .card-header .btn {
            margin-top: 10px;
            width: 100%;
        }

        .form-group .col-md-4,
        .form-group .col-md-2 {
            width: 100%;
            margin-bottom: 10px;
        }
    }

    input[type=checkbox] {
        /* Double-sized Checkboxes */
        -ms-transform: scale(1.5);
        /* IE */
        -moz-transform: scale(1.5);
        /* FF */
        -webkit-transform: scale(1.5);
        /* Safari and Chrome */
        -o-transform: scale(1.5);
        /* Opera */
        transform: scale(1.5);
        padding: 10px;

        @media (max-width: 768px) {
            .card-header .btn {
                margin-top: 10px;
                width: 100%;
            }

            .form-group .col-md-4,
            .form-group .col-md-2 {
                width: 100%;
                margin-bottom: 10px;
            }

            .card-body {
                overflow-x: auto;
            }

            table {
                min-width: 800px;
            }
        }

    }


    .modal-dialog {
        margin-top: 10vh;
    }

    .modal-content {
        max-height: 80vh;
        overflow-y: auto;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .btn-group {
        width: 100%;
    }

    .btn-group .btn {
        width: 100%;
        margin-bottom: 5px;
    }

    @media (max-width: 767px) {
        .btn-group-vertical .btn {
            width: 100%;
        }

        .btn-group {
            display: block;
        }
    }

    .back-to-top {
        position: fixed;
        display: none;
        right: 15px;
        bottom: 15px;
        z-index: 99999;
    }

    .back-to-top i {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        width: 40px;
        height: 40px;
        border-radius: 50px;
        background: #1977cc;
        color: #fff;
        transition: all 0.4s;
    }

    .back-to-top i:hover {
        background: #1c84e3;
        color: #fff;
    }
</style>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="row mb-4 mt-4">

        </div>
        <div class="row">
            <div class="card col-md-12">
                <div class="card-header">
                    <b>Account List</b>
                    <span class="float:right"><button class="btn btn-primary float-right btn-sm" id="new_user"><i
                                class="fa fa-plus"></i> New
                            user</button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table-striped table-bordered col-md-12">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Username</th>
                                    <th class="text-center">Establishment</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include 'db_connect.php';
                                $est = $conn->query("SELECT * FROM establishments ");
                                $est_name[0] = "Can manage all";
                                while ($row = $est->fetch_assoc()) {
                                    $est_name[$row['id']] = $row['name'];
                                }
                                $users = $conn->query("SELECT * FROM users order by name asc");
                                $i = 1;
                                while ($row = $users->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            <?php echo $i++ ?>
                                        </td>
                                        <td>
                                            <?php echo ucwords($row['name']) ?>
                                        </td>
                                        <td>
                                            <?php echo $row['username'] ?>
                                        </td>
                                        <td>
                                            <?php echo $est_name[$row['establishment_id']] ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary">Action</button>
                                                <button type="button"
                                                    class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item edit_user" href="javascript:void(0)"
                                                        data-id='<?php echo $row['id'] ?>'>Edit</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item delete_user" href="javascript:void(0)"
                                                        data-id='<?php echo $row['id'] ?>'>Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('table').dataTable();

    $('#new_user').click(function () {
        uni_modal('New User', 'manage_user.php')
    })

    $('.edit_user').click(function () {
        uni_modal('Edit User', 'manage_user.php?id=' + $(this).attr('data-id'))
    })

    $('.delete_user').click(function () {
        _conf("Are you sure to delete this user?", "delete_user", [$(this).attr('data-id')])
    })

    function delete_user($id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_user',
            method: 'POST',
            data: { id: $id },
            success: function (resp) {
                if (resp == 1) {
                    alert_toast("Data successfully deleted", 'success')
                    setTimeout(function () {
                        location.reload()
                    }, 1500)
                }
            }
        })
    }
</script>