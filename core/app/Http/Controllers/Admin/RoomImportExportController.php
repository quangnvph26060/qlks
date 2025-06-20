<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\RoomsImport;
use App\Jobs\ProcessRoomImport;
use App\Models\Room;
use App\Models\RoomDirection;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class RoomImportExportController extends Controller
{

    public function import(Request $request)
    {
        if (!$request->hasFile('file')) {
            return back()->with('error', 'Không có file nào được gửi lên.');
        }

        $file = $request->file('file');

        if (!$file->isValid()) {
            return back()->with('error', 'File không hợp lệ.');
        }

        // Tạo đường dẫn lưu tạm trong storage
        $tempPath = storage_path('app/temp');
        if (!file_exists($tempPath)) {
            mkdir($tempPath, 0777, true);
        }

        // Tạo tên file mới tạm thời
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->move($tempPath, $filename);

        try {
            $data = Excel::toArray([], $filePath->getRealPath());
            // Lấy sheet đầu tiên (thường là $data[0])
            $rawRows = $data[0];

            // Bỏ qua dòng tiêu đề (header)
            $header = array_shift($rawRows);

            // Lọc các dòng không hoàn toàn null
            $filteredRows = array_filter($rawRows, function ($row) {
                return array_filter($row, fn($value) => !is_null($value)) !== [];
            });

            // Nếu muốn chèn lại header vào đầu
            array_unshift($filteredRows, $header);

            // Gán lại vào $data nếu bạn muốn giữ nguyên cấu trúc
            $data[0] = $filteredRows;

            // Kiểm tra kết quả
            $errors = $this->importData($data);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $notify = [];

            if (!empty($errors)) {
                foreach ($errors as $error) {
                    $notify[] = ['error', $error];
                }
            } else {
                $notify[] = ['success', 'Thêm dữ liệu thành công'];
            }

            return back()->withNotify($notify);
        } catch (\Throwable $e) {
            Log::error('[IMPORT ERROR] ' . $e->getMessage(), ['file' => $file?->getClientOriginalName()]);
            return back()->with('error', 'Đã xảy ra lỗi khi xử lý file Excel.');
        }

        // $notify[] = ['success', 'Thêm dữ liệu thành công'];


        // return back()->withNotify($notify);
    }
    protected function importData(array $data)
    {
        $rows = $data[0] ?? [];
        $errors = [];
        if (count($rows) < 2) {
            Log::warning('Import Room: Không có dữ liệu để import');
            return;
        }

        // Lấy dòng đầu tiên làm header
        $header = $rows[0];


        $dataRows = array_slice($rows, 1);

        foreach ($dataRows as $row) {

            if (array_filter($row, fn($v) => !is_null($v)) === []) {
                continue;
            }

            // Gộp header và dữ liệu thành key => value
            $mapped = array_combine($header, $row);

            // Map về đúng tên cột trong DB
            $roomData = [
                'ten_phong'     => $mapped['Tên phòng'] ?? null,
                'ma_phong'      => $mapped['Mã phòng'] ?? null,
                'ma_loai_phong' => $mapped['Mã loại phòng'] ?? null,
                'so_giuong'     => $mapped['Số giường'] ?? null,
                'so_nguoi'      => $mapped['Số người'] ?? null,
                'huong_phong'   => $mapped['Hướng phòng'] ?? null,
                'trang_thai'    => $mapped['Trạng thái'] ?? null,
                'dien_tich'     => $mapped['Diện tích phòng'] ?? null,
                'mo_ta'         => $mapped['Mô tả'] ?? null,
            ];

            try {
                // Tìm RoomType từ mã loại phòng
                $roomType = RoomType::where('code', $roomData['ma_loai_phong'])->first();
                $direction = RoomDirection::where('code', $roomData['huong_phong'])->first();
                if (!$roomType) {
                    $errors[] = "Mã loại phòng không tồn tại ({$roomData['ma_loai_phong']})";
                    continue;
                }
                if (!$direction) {
                    $errors[] = "Mã hướng phòng không tồn tại ({$roomData['huong_phong']})";
                    continue;
                }
                if ($roomData['ma_phong'] == "") {
                    $errors[] = " Mã phòng không được để trống";
                    continue;
                }
                $room = Room::where('code', $roomData['ma_phong'])->first();
                if ($room) {
                    $errors[] = " Mã phòng đã tồn tại ({$roomData['ma_phong']})";
                    continue;
                }
                // Tạo phòng mới
                $room = new Room();
                // Gán dữ liệu từ Excel (hoặc từ request nếu cần)
                // $room->code          = $roomData['ma_phong'] ?? $this->generateRoomCode();
                $room->code          = $roomData['ma_phong'];
                $room->room_type_id  = $roomType->id;
                $room->room_number   = $roomData['ten_phong'];
                $room->status        = $roomData['trang_thai'];
                $room->description   = $roomData['mo_ta'];
                $room->beds          = $roomData['so_giuong'];
                $room->total_adult   = $roomData['so_nguoi'];
                $room->direction_id  = $direction->id;
                $room->area          = $roomData['dien_tich'];
                $room->room_fix      =  0;
                $room->main_image    = 'images/default.png';
                $room->unit_code     = unitCode();
                $room->subdomain     = subdomain();

                // Lưu vào DB
                $room->save();
            } catch (\Throwable $e) {
                Log::error('Import Room Error', [
                    'message'  => $e->getMessage(),
                    'line'     => $e->getLine(),
                    'file'     => $e->getFile(),
                    'row_data' => $roomData,
                ]);
            }
        }
        return $errors;
    }
    protected function generateRoomCode(): string
    {
        do {
            // Tạo mã ngẫu nhiên theo format tuỳ bạn, ví dụ: "RM" + số random
            $code = 'MP' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (
            Room::where('code', $code)
            ->where('unit_code', unitCode())
            ->where('subdomain', subdomain())
            ->exists()
        );

        return $code;
    }
}
