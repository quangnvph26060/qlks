<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;

class TestController extends Controller
{


    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
            'sheet_name' => 'required',
        ]);

        $file = $request->file('file');
        $sheetName = $request->sheet_name;

        // Load file
        $spreadsheet = IOFactory::load($file);
        $sheetNames = $spreadsheet->getSheetNames();

        // Tìm index của sheet theo tên
        $sheetIndex = array_search($sheetName, $sheetNames);

        if ($sheetIndex === false) {
            return response()->json(['message' => 'Không tìm thấy sheet: ' . $sheetName], 404);
        }

        // Lấy dữ liệu sheet
        $sheets = \Maatwebsite\Excel\Facades\Excel::toArray([], $file);
        $sheet = $sheets[$sheetIndex] ?? [];

        if (empty($sheet)) {
            return response()->json(['message' => 'Sheet không có dữ liệu'], 400);
        }

        // Chuẩn hóa header
        $headers = array_map(function ($header) {
            $ascii = Str::ascii($header);
            $slug = Str::slug($ascii, ' ');
            return Str::snake($slug);
        }, $sheet[0]);

        // Bắt đầu từ dòng 2
        $rows = array_slice($sheet, 1);
        $data = [];

        foreach ($rows as $row) {
            if (count($headers) === count($row)) {
                $data[] = array_combine($headers, $row);
            }
        }

        return response()->json([
            'headers' => $headers,
            'data' => $data,
        ]);
    }
}
