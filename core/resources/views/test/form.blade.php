<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Excel - Chọn Sheet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card shadow-lg rounded-4">
                    <div class="card-body">
                        <h4 class="card-title text-center mb-4">📥 Import Excel - Chọn Sheet</h4>

                        <form id="importForm" action="{{ route('test.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="excelFile" class="form-label">Chọn file Excel:</label>
                                <input type="file" name="file" id="excelFile" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="sheetName" class="form-label">Nhập tên Sheet:</label>
                                <input type="text" name="sheet_name" id="sheetName" class="form-control" placeholder="Ví dụ: DanhSachPhongBan" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-upload"></i> Import
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap Icons (tùy chọn) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>
