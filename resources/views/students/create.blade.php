<!doctype html>
<html>
<head>
    <title>Add Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-light" style="background-color: #e68663; padding: 30px;">

<div class="container mt-5">
<div class="rounded-shadow-lg p-4" style="background-color: #26baf5; padding: 20px; border-radius: 10px;">
    <h2 class="text-center">Add Student</h2>
 
    <form id="studentForm">
        <label for="name">Name:</label>
        <input type="text" id="name" class="form-control mb-2" placeholder="Name">

        <label for="email">Email:</label>
        <input type="text" id="email" class="form-control mb-2" placeholder="Email">

        <label for="phone">Phone:</label>
        <input type="text" id="phone" class="form-control mb-2" placeholder="Phone">

        <button type="submit" class="btn btn-success">Save</button>

    </form>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function(){

    $("#studentForm").submit(function(e){
        e.preventDefault();

        $.ajax({
            url: "/students/store",
            type: "POST",
            data: {
                name: $("#name").val(),
                email: $("#email").val(),
                phone: $("#phone").val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response){
                alert("Student Added Successfully");
                window.location.href = "/students";
                $("#studentForm")[0].reset();
            }
        });

    });

});
</script>

</body>
</html>