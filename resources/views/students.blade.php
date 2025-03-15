

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Data</title>
    
    <!-- Bootstrap CSS -->
    <head>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Toastr CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <!-- SweetAlert2 CSS & JS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    

    <style> 
        .container {
            margin-top: 40px;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        table {
            border-radius: 10px;
            overflow: hidden;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
        }
        img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        #suggection {
    display: none;
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #ccc;
    background-color: white;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
}
.suggestion-item {
    padding: 8px;
    cursor: pointer;
}
.suggestion-item:hover {
    background-color: #f1f1f1;
}

    </style>
</head>
<body>

<div class="container">
    <div class="card p-4">
        <h2 class="text-center text-primary mb-4">Student Data</h2>

        <div class="input-group mb-3 position-relative">
            <input type="text" class="form-control" placeholder="Search student by name" id="search">
            <button class="btn btn-primary" id="search-btn">Search</button>
            
            <!-- Suggestion Box Directly Below Input -->
            <div id="suggection" class="list-group position-absolute w-100" style="top: 100%; z-index: 1000;"></div>
        </div>
        
        <div class="table-responsive">
            <table id="table" class="table table-hover table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>


<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(document).ready(function () {
    $.ajax({
        url: '{{ route('get-students') }}',  // Laravel route to fetch students
        type: 'GET',
        success: function (response) {
            console.log(response);
            if (response.students.length > 0) {
                response.students.forEach(function (student) {
                    $('tbody').append(`
                        <tr>
                            <td>${student.id}</td>
                            <td>${student.name}</td>
                            <td>${student.email}</td>
                            <td><img src="{{ asset('uploads') }}/${student.image}" width="50" height="50" class="rounded-circle" /></td>
                            <td>
                                <a href="/students/edit/${student.id}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${student.id}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    `);
                });
            }
        },
        error: function (e) {
            console.log(e.responseText);
        }
    });



});

$(document).on('click', '.delete-btn', function () {
    var studentId = $(this).data('id');

    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/students/delete/" + studentId,
                type: "DELETE",
                success: function (response) {
                    Swal.fire("Deleted!", response.res, "success");
                    location.reload();
                },
                error: function () {
                    Swal.fire("Error!", "Something went wrong!", "error");
                }
            });
        }
    });
});

$(document).ready(function(){
    $('#search').keyup(function(){
        let query = $(this).val();
        if(query.length > 1){
            $.ajax({
                url: "/search",  // Laravel ka route
                method: "GET",
                data: { query: query },
                success: function(response){
                    let suggestionBox = $('#suggection'); // ID correct kiya
                    suggestionBox.html(""); 
                    if(response.length > 0){
                        suggestionBox.show(); // Suggestions box ko show karna
                        response.forEach(function(data){
                            suggestionBox.append("<div class='suggestion-item p-2 border'>" + data.name + "</div>");
                        });
                    } else {
                        suggestionBox.append('<p class="list-group-item list-group-item-action">No data found</p>');
                    }
                }
            });
        } else {
            $('#suggection').hide(); 
        }
    });

    // Click event for selecting suggestion
    $(document).on("click", ".suggestion-item", function() {
        $("#search").val($(this).text());
        $("#suggection").hide();
    });
});



</script>
