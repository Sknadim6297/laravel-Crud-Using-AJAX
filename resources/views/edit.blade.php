<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student</title>
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
        <h3 class="text-center mb-4">Update Your Details</h3>
        <form id="myForm">
            @csrf
            <input type="hidden" id="studentId" name="id" value="{{ $student->id }}">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" value="{{ $student->name }}" name="name" placeholder="Enter your name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="{{ $student->email }}" placeholder="Enter your email" required>
            </div>
            <div class="mb-3 text-center">
                <label class="form-label">Uploaded Image</label>
                <div>
                    <img id="previewImage" src="{{ asset('uploads/' . $student->image) }}" 
                         class="img-thumbnail" style="width: 90px; height: 90px; object-fit: cover; border-radius:50%;">
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Upload New File</label>
                <div class="file-input" onclick="document.getElementById('file').click()">Click to Upload</div>
                <input type="file" class="form-control d-none" id="file" name="file">
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
            
            <button type="submit" class="btn btn-primary w-100" id="btnSubmit">Submit</button>
        </form>
        <div id="responseMessage" class="mt-3 text-center"></div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#myForm').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var studentId = $('#studentId').val();

            $.ajax({
                url: "/update-data/" + studentId,
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    console.log(response); 

                    if (response.status === "success") {
                        toastr.success(response.message);
                        setTimeout(function () {
                            window.location.href = "/students";
                        }, 2000);
                    } else {
                        toastr.error("Something went wrong!");
                    }
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                    toastr.error("Something went wrong. Please try again!", "Error");
                }
            });
        });
    });
</script>
