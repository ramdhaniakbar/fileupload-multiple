<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Multiple Files dengan Progress Bar</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }

        .upload-container {
            width: 400px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        input[type="file"] {
            margin-bottom: 10px;
        }

        button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        .progress-container {
            width: 100%;
            background-color: #ddd;
            border-radius: 5px;
            margin-top: 10px;
            overflow: hidden;
        }

        .progress-bar {
            height: 20px;
            width: 0%;
            background-color: #4caf50;
            text-align: center;
            color: white;
            border-radius: 5px;
            line-height: 20px;
            font-weight: bold;
        }

        .progress-item {
            margin-bottom: 10px;
        }

        .progress-text {
            font-size: 14px;
            margin-top: 5px;
            font-weight: bold;
            color: #d9534f;
        }
    </style>
</head>

<body>

    <div class="upload-container">
        <input type="file" id="fileInput" multiple>
        <button id="uploadBtn">UPLOAD</button>
        <div id="progress-list"></div>
        <p id="remaining-size">Sisa Limit: 1024 MB</p>
    </div>

    <script>
        let MAX_SIZE = 1024 * 1024 * 1024; // 1GB
        let usedSize = 0;

        $("#fileInput").on("change", function () {
            let files = this.files;
            if (!files.length) return;

            let totalSize = 0;
            for (let file of files) {
                totalSize += file.size;
            }

            if (totalSize > MAX_SIZE - usedSize) {
                alert("Total ukuran file melebihi sisa limit: " + ((MAX_SIZE - usedSize) / (1024 * 1024)).toFixed(2) + " MB");
                $("#fileInput").val("");
                return;
            }
        });

        $("#uploadBtn").on("click", function () {
            let files = $("#fileInput")[0].files;
            if (!files.length) {
                alert("Pilih setidaknya satu file!");
                return;
            }

            let formData = new FormData();
            for (let file of files) {
                formData.append("files[]", file);
            }

            $("#progress-list").html(""); // Reset progress list

            let uploadedSize = 0;

            for (let file of files) {
                let progressItem = $('<div class="progress-item"><p class="progress-text">' + file.name + '</p><div class="progress-container"><div class="progress-bar" id="progress-' + file.name + '">0%</div></div></div>');
                $("#progress-list").append(progressItem);

                let xhr = new XMLHttpRequest();
                xhr.open("POST", "upload.php", true);

                xhr.upload.onprogress = function (event) {
                    
                };

                xhr.onload = function () {
                    if (xhr.status == 200) {
                        uploadedSize += file.size;
                        usedSize += file.size;
                        let remainingSize = MAX_SIZE - usedSize;
                        let progress = (usedSize / MAX_SIZE) * 100;

                        // Update tampilan sisa limit
                        $("#remaining-size").text("Sisa Limit: " + (remainingSize / (1024 * 1024)).toFixed(2) + " MB");
                    } else {
                        alert("Upload gagal untuk file: " + file.name);
                    }
                };

                let singleFileData = new FormData();
                singleFileData.append("file", file);
                xhr.send(singleFileData);
            }
        });
    </script>

</body>

</html>
