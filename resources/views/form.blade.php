<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJAX Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            border-radius: 8px;
        }
        .btn-primary {
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s ease-in-out;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.02);
        }
        .file-input {
            border: 2px dashed #ccc;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s ease-in-out;
        }
        .file-input:hover {
            border-color: #007bff;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4" style="max-width: 450px; width: 100%;">
            <h3 class="text-center mb-4">Submit Your Details</h3>
            <form id="myForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Enter your name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                </div>
                <div class="text-center">
                    <label class="form-label">Selected Image</label>
                    <div>
                        <img id="previewImage" src="https://imgs.search.brave.com/Dwe3RRvsmgqv_lNOqfaBWV6Xg4H0PePpXZeQEGReeJM/rs:fit:500:0:0:0/g:ce/aHR0cHM6Ly90My5m/dGNkbi5uZXQvanBn/LzAzLzQ1LzA1Lzky/LzM2MF9GXzM0NTA1/OTIzMl9DUGllVDhS/SVdPVWs0SnFCa2tX/a0lFVFlBa216MmI3/NS5qcGc" 
                        class="img-thumbnail" style="width: 90px; height: 90px; object-fit: cover; border-radius:50%;">
                    </div>
                </div>
    
                <div class="mb-3">
                    <label class="form-label">Upload File</label>
                    <div class="file-input" onclick="document.getElementById('file').click()">Click to Upload</div>
                    <input type="file" class="form-control d-none" id="file" name="file" required>
                </div>
                <button type="submit" class="btn btn-primary w-100" id="btnSubmit">Submit</button>
            </form>
            <div id="responseMessage" class="mt-3 text-center"></div>
        </div>
    </div>
    
    <script>
        document.getElementById("file").addEventListener("change", function(event) {
            let reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById("previewImage").src = e.target.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        });
    </script>
    

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
$(document).ready(function(){
    $('#myForm').submit(function(e){
        e.preventDefault();
        var formData = new FormData(this);

        var $btnSubmit = $(this).find('button[type="submit"]');
        $btnSubmit.prop('disabled', true).text('Submitting...');

        $.ajax({
            url: '{{ route('add-student') }}',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response){
                toastr.success(response.res, "Success");
                $("#myForm")[0].reset();
                $btnSubmit.prop('disabled', false).text('Submit');
                window.location.href = "/students"; 
            },
            error: function(e){
                console.log(e.responseText);
                toastr.error("Something went wrong. Please try again!", "Error");
                $btnSubmit.prop('disabled', false).text('Submit');
            }
        });
    });
});


</script>
</body>
</html>
