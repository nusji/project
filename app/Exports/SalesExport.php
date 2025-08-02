<?php
// app/Exports/SalesExport.php
namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class SalesExport implements FromCollection, WithHeadings, WithEvents
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Sale::with('employee', 'saleDetails.menu');

        // Apply filters
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where('id', 'like', "%{$search}%")
                  ->orWhereHas('employee', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        if (!empty($this->filters['start_date'])) {
            $query->whereDate('sale_date', '>=', $this->filters['start_date']);
        }

        if (!empty($this->filters['end_date'])) {
            $query->whereDate('sale_date', '<=', $this->filters['end_date']);
        }

        // Sorting
        if (!empty($this->filters['sort_by'])) {
            $sortOrder = $this->filters['sort_order'] ?? 'asc';
            $query->orderBy($this->filters['sort_by'], $sortOrder);
        }

        return $query->get()->map(function($sale) {
            return [
                'รหัสการขาย' => $sale->id,
                'วันที่เวลาขาย' => $sale->sale_date,
                'พนักงานขาย' => $sale->employee->name,
                'จ่ายด้วย' => ucfirst($sale->payment_type),
                'ยอดรวม' => number_format($sale->saleDetails->sum(function ($detail) {
                    return $detail->menu ? $detail->menu->menu_price * $detail->quantity : 0;
                }), 2),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'รหัสการขาย',
            'วันที่เวลาขาย',
            'พนักงานขาย',
            'จ่ายด้วย',
            'ยอดรวม',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // เพิ่ม BOM ที่ส่วนหัวของไฟล์ CSV
                $event->sheet->prependRow(array_map(function($heading) {
                    return "\xEF\xBB\xBF" . $heading;
                }, $this->headings()));
            },
        ];
    }
}
