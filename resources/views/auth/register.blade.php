<!doctype html>
<html>
<head>
    <title>Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card p-4">

        <h2 class="text-center mb-4">
            Register
        </h2>

        <form id="registerForm">

            <input type="text"
                   id="name"
                   class="form-control mb-3"
                   placeholder="Enter Name">

            <input type="email"
                   id="email"
                   class="form-control mb-3"
                   placeholder="Enter Email">

            <input type="password"
                   id="password"
                   class="form-control mb-3"
                   placeholder="Enter Password">

            <button type="submit" class="btn btn-success w-100">
                Register
            </button>

        </form>

        <br>

        <a href="/login" class="btn btn-primary w-100">
            Already have an account? Login
        </a>

    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function () {
      console.log("Ready");
    $("#registerForm").submit(function (e) {

        e.preventDefault();
        console.log("Submit Clicked");
        $.ajax({

            url: "/register",

            type: "POST",

            data: {

                name: $("#name").val(),

                email: $("#email").val(),

                password: $("#password").val(),

                _token: $("meta[name='csrf-token']").attr("content")

            },

            success: function (response) {

                alert(response.message);

                $("#registerForm")[0].reset();

                window.location.href = "/login";

            },

            error: function (xhr) {

                let errors = xhr.responseJSON.errors;

                if (errors) {

                    let message = "";

                    $.each(errors, function (key, value) {

                        message += value[0] + "\n";

                    });

                    alert(message);

                }

            }

        });

    });

});
</script>

</body>
</html>