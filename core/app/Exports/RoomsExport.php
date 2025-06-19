<?php

namespace App\Exports;

use App\Models\Room;
use App\Models\RoomType;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Table;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;


use Maatwebsite\Excel\Concerns\WithMapping;

class RoomsExport implements FromCollection, WithMapping, WithHeadings, WithEvents

{
    public function collection()
    {
        $rooms  =  Room::with('roomType:id,id,name') // load quan hệ để dùng tên
            ->select('room_number', 'code', 'room_type_id', 'status', 'description')
            ->get();
        return $rooms;
    }


    public function headings(): array
    {
        return [
            'Tên phòng',
            'Mã phòng',
            'Mã Loại phòng',
            'Trạng thái',
            'Mô tả',
        ];
    }
    public function map($room): array
    {
        return [
            $room->room_number,
            $room->code,
            optional($room->roomType)->name,
            $room->status,
            $room->description,
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $range = 'A1:' . $highestColumn . $highestRow;

                // ✅ Border + căn giữa
                $sheet->getStyle($range)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => Color::COLOR_BLACK],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical'   => 'center',
                    ],
                ]);

                // ✅ Header màu xanh + chữ trắng
                $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF4472C4'],
                    ],
                ]);

                // ✅ Tạo bảng có filter như table Excel
                $table = new Table($range);
                $table->setShowHeaderRow(true)
                    ->setStyle(new TableStyle()); // mặc định = TableStyleMedium9
                $sheet->addTable($table);
            },
        ];
    }
}
