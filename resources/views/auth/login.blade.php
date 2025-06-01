<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .is-invalid {
            border-color: #dc3545;
        }
    </style>
</head>

<body class="bg-light">
    <!-- Toast Container -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
        <div id="toastBox" class="toast align-items-center text-white bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage">Login successful!</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow p-4 rounded">
                    <h4 class="mb-4 text-center">Login</h4>
                    <form id="loginForm">
                        @csrf
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control">
                            <div class="invalid-feedback" id="emailError"></div>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control">
                            <div class="invalid-feedback" id="passwordError"></div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                        <div id="message" class="mt-3 text-center"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <script>
        $(document).ready(function() {
            $("#loginForm").submit(function(e) {
                e.preventDefault();

                let email = $("input[name='email']");
                let password = $("input[name='password']");

                email.removeClass("is-invalid");
                password.removeClass("is-invalid");
                $("#emailError").text("");
                $("#passwordError").text("");

                $.ajax({
                    url: 'api/login',
                    method: 'POST',
                    dataType: 'json',
                    contentType: "application/json",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    data: JSON.stringify({
                        email: email.val(),
                        password: password.val(),
                    }),
                    success: function(data) {
                        if (data.status) {
                            localStorage.setItem("auth_token", data.token);
                            showToast("Login successful!", "success");
                        } else {
                            showToast(data.message, "danger");
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            if (errors.email) {
                                email.addClass("is-invalid");
                                $("#emailError").text(errors.email[0]);
                            }
                            if (errors.password) {
                                password.addClass("is-invalid");
                                $("#passwordError").text(errors.password[0]);
                            }
                        } else {
                            showToast("Something went wrong!", "danger");
                        }
                    },
                });
            });
        });

        function showToast(message, type = "success") {
            const toastBox = document.getElementById("toastBox");
            const toastMessage = document.getElementById("toastMessage");

            // Set color based on type
            toastBox.className = `toast align-items-center text-white bg-${type} border-0`;

            // Set message
            toastMessage.innerText = message;

            const toast = new bootstrap.Toast(toastBox);
            toast.show();
        }
    </script>
</body>

</html>
