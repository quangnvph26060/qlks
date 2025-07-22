<?php

namespace App\Exports;

use App\Models\WarehouseEntry;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Table;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;
use Carbon\Carbon;

class WarehouseEntryExport  implements FromCollection, WithHeadings, WithEvents
{
   public function collection()
{
    $rows = new Collection();

    $entries = WarehouseEntry::with(['entries.product', 'entries.warehouse', 'supplier'])->get();

    foreach ($entries as $item) {
        foreach ($item->entries as $entry) {
            $rows->push([
                $item->reference_code,
                Carbon::parse($item->created_time)->format('d/m/Y'),
                $entry->product->sku ?? '',
                $entry->quantity,
                $item->supplier->supplier_id,
                $entry->warehouse->code ?? '',
                $item->note,
            ]);
        }
    }

    return $rows;
}

    public function headings(): array
    {
        return [
            'Mã phiếu',
            'Ngày nhập',
            'Mã sản phẩm',
            'Số lượng',
            'Mã nhà cung cấp',
            'Mã kho',
            'Ghi chú',
        ];
    }
    // public function map($item): array
    // {
    //     return [
    //         $item->reference_code,
    //         \Carbon\Carbon::parse($item->created_time)->format('d/m/Y'),
    //         optional($item->entries)->product_id,
    //         optional($item->entries)->quantity,
    //         $item->supplier_id,
    //         optional($item->entries)->warehouse_id,
    //         $item->note,
    //     ];
    // }
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
