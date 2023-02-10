<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

</head>

<body>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h4>Users</h4>
                            </div>
                            <div class="col-6 text-end">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                                    Add User
                                </button>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody id="tbody">
                                <!-- <tr>
                                    <td>1</td>
                                    <td>Afaq</td>
                                    <td>Male</td>
                                    <td>Date</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
                                            Edit
                                        </button>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            Delete
                                        </button>
                                    </td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php require_once('./partials/modals.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script>
        const addFormElement = document.getElementById('add-form');
        const addErrorElement = document.getElementById('add-error');
        const addSuccessElement = document.getElementById('add-success');

        addFormElement.addEventListener('submit', function(e) {
            e.preventDefault();

            const addNameElement = document.getElementById('add-name');
            const addEmailElement = document.getElementById('add-email');

            let addNameValue = addNameElement.value;
            let addEmailValue = addEmailElement.value;


            addErrorElement.innerText = '';
            addNameElement.classList.remove('is-invalid');
            addEmailElement.classList.remove('is-invalid');

            if (addNameValue == "" || addNameValue === undefined) {
                addErrorElement.innerText = 'Please provide your Name!';
                addNameElement.classList.add('is-invalid');
            } else if (addEmailValue == "" || addEmailValue === undefined) {
                addErrorElement.innerText = 'Please provide your Email!';
                addEmailElement.classList.add('is-invalid');
            } else {

                const data = {
                    name: addNameValue,
                    email: addEmailValue,
                    submit: 1
                };

                fetch('./add-user.php', {
                        method: 'POST',
                        body: JSON.stringify(data),
                        headers: {
                            'Content-Type': 'application.json'
                        }
                    })
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(result) {
                        if (result.nameError) {
                            addErrorElement.innerText = result.nameError;
                            addNameElement.classList.add('is-invalid');
                        } else if (result.emailError) {
                            addErrorElement.innerText = result.emailError;
                        } else if (result.success) {
                            addSuccessElement.innerText = result.success;
                            addFormElement.reset();
                            showUsers();
                        } else if (result.failed) {
                            addErrorElement.innerText = result.failed;
                        } else {
                            addErrorElement.innerText = 'Something went wrong';
                        }
                    })
            }


        });

        showUsers();

        function showUsers() {
            const tBodyElement = document.getElementById('tbody');

            fetch('./show-users.php', {
                    headers: {
                        'Content-Type': 'application.json'
                    }
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(result) {
                    let sr = 1;
                    let rows = "";
                    result.forEach(element => {
                        rows += `<tr><td>${sr++}</td><td>${element['name']}</td><td>${element['email']}</td><td>${element['created_at']}</td><td><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal" onclick="editStudent(${element['id']})">Edit</button> <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="deleteStudent(${element['id']})">Delete</button></td></tr>`
                    });

                    tBodyElement.innerHTML = rows;
                })
        }

        function editStudent(id) {
            const data = {
                id: id,
                submit: 1
            }

            fetch('./single-user.php', {
                    method: 'POST',
                    body: JSON.stringify(data),
                    headers: {
                        'Content-Type': 'application.json'
                    }
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(result) {
                    const editNameElement = document.getElementById('edit-name');
                    const editEmailElement = document.getElementById('edit-email');

                    editNameElement.setAttribute('value', result['name']);
                    editEmailElement.setAttribute('value', result['email']);
                })

            const editFormElement = document.getElementById('edit-form');
            const editErrorElement = document.getElementById('edit-error');
            const editSuccessElement = document.getElementById('edit-success');

            editFormElement.addEventListener('submit', function(e) {
                e.preventDefault();

                const editNameElement = document.getElementById('edit-name');
                const editEmailElement = document.getElementById('edit-email');

                let editNameValue = editNameElement.value;
                let editEmailValue = editEmailElement.value;

                editErrorElement.innerText = '';
                editSuccessElement.innerText = '';
                editNameElement.classList.remove('is-invalid');
                editEmailElement.classList.remove('is-invalid');

                if (editNameValue == "" || editNameValue === undefined) {
                    editErrorElement.innerText = 'Please provide your Name!';
                    editNameElement.classList.add('is-invalid');
                } else if (editEmailValue == "" || editEmailValue === undefined) {
                    editErrorElement.innerText = 'Please provide your Email!';
                    editEmailElement.classList.add('is-invalid');
                } else {
                    const data = {
                        name: editNameValue,
                        email: editEmailValue,
                        id: id,
                        submit: 1
                    }

                    fetch('./edit-user.php', {
                            method: 'POST',
                            body: JSON.stringify(data),
                            headers: {
                                'Content-Type': 'application.json'
                            }
                        })
                        .then(function(response) {
                            return response.json();
                        })
                        .then(function(result) {
                            if (result.nameError) {
                                editErrorElement.innerText = result.nameError;
                                editNameElement.classList.add('is-invalid');
                            } else if (result.emailError) {
                                editErrorElement.innerText = result.emailError;
                            } else if (result.success) {
                                editSuccessElement.innerText = result.success;
                                showUsers();
                            } else if (result.failed) {
                                editErrorElement.innerText = result.failed;
                            } else {
                                editErrorElement.innerText = 'Something went wrong';
                            }
                        })
                }
            })

        }

        function deleteStudent(id) {
            const deleteFormElement = document.getElementById('delete-form');
            const deleteErrorElement = document.getElementById('delete-error');
            const deleteSuccessElement = document.getElementById('delete-success');

            deleteFormElement.addEventListener('submit', function(e) {
                e.preventDefault();

                const data = {
                    id: id,
                    submit: 1
                }

                fetch('./delete-user.php', {
                        method: 'POST',
                        body: JSON.stringify(data),
                        headers: {
                            'Content-Type': 'application.json'
                        }
                    })
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(result) {
                        if (result.success) {
                            deleteSuccessElement.innerText = result.success;
                            deleteFormElement.style.display = 'none';
                            showUsers();
                        } else if (result.error) {
                            deleteErrorElement.innerText = result.error;
                        } else {
                            deleteErrorElement.innerText = 'Something went wrong';
                        }
                    })
            })
        }
    </script>
</body>

</html>