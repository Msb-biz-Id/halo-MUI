<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

/**
 * Excel Service
 * Handles Excel export using PHPSpreadsheet
 */
class ExcelService
{
    private $spreadsheet;
    private $sheet;
    
    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
    }
    
    /**
     * Export certificate applications to Excel
     * 
     * @param array $data
     * @param string $filename
     * @return string File path
     */
    public function exportCertificateApplications($data, $filename = 'certificate_applications.xlsx')
    {
        // Set document properties
        $this->spreadsheet->getProperties()
            ->setCreator(APP_NAME)
            ->setTitle('Laporan Pengajuan Sertifikat Halal')
            ->setDescription('Laporan pengajuan sertifikat halal');
        
        // Set header
        $headers = [
            'No', 'Nomor Tiket', 'Nama Perusahaan', 'Produk', 
            'Kategori', 'Status', 'Prioritas', 'Tanggal Pengajuan'
        ];
        
        $column = 'A';
        foreach ($headers as $header) {
            $this->sheet->setCellValue($column . '1', $header);
            $column++;
        }
        
        // Style header
        $this->sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
        
        // Fill data
        $row = 2;
        $no = 1;
        foreach ($data as $item) {
            $this->sheet->setCellValue('A' . $row, $no++);
            $this->sheet->setCellValue('B' . $row, $item['ticket_number']);
            $this->sheet->setCellValue('C' . $row, $item['company_name']);
            $this->sheet->setCellValue('D' . $row, $item['product_name']);
            $this->sheet->setCellValue('E' . $row, $item['product_category']);
            $this->sheet->setCellValue('F' . $row, strtoupper($item['status']));
            $this->sheet->setCellValue('G' . $row, strtoupper($item['priority']));
            $this->sheet->setCellValue('H' . $row, date('d/m/Y', strtotime($item['submitted_at'])));
            $row++;
        }
        
        // Auto size columns
        foreach (range('A', 'H') as $col) {
            $this->sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Add borders
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $this->sheet->getStyle('A1:H' . ($row - 1))->applyFromArray($styleArray);
        
        // Save file
        $filepath = STORAGE_PATH . '/exports/' . $filename;
        
        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }
        
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($filepath);
        
        return $filepath;
    }
    
    /**
     * Export audit logs to Excel
     * 
     * @param array $data
     * @param string $filename
     * @return string File path
     */
    public function exportAuditLogs($data, $filename = 'audit_logs.xlsx')
    {
        // Set document properties
        $this->spreadsheet->getProperties()
            ->setCreator(APP_NAME)
            ->setTitle('Audit Log Report')
            ->setDescription('System audit log report');
        
        // Set header
        $headers = ['No', 'User', 'Action', 'Table', 'Record ID', 'IP Address', 'Timestamp'];
        
        $column = 'A';
        foreach ($headers as $header) {
            $this->sheet->setCellValue($column . '1', $header);
            $column++;
        }
        
        // Style header
        $this->sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E74C3C']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
        
        // Fill data
        $row = 2;
        $no = 1;
        foreach ($data as $item) {
            $this->sheet->setCellValue('A' . $row, $no++);
            $this->sheet->setCellValue('B' . $row, $item['username'] ?? 'N/A');
            $this->sheet->setCellValue('C' . $row, strtoupper($item['action']));
            $this->sheet->setCellValue('D' . $row, $item['table_name']);
            $this->sheet->setCellValue('E' . $row, $item['record_id'] ?? '-');
            $this->sheet->setCellValue('F' . $row, $item['ip_address'] ?? '-');
            $this->sheet->setCellValue('G' . $row, date('d/m/Y H:i:s', strtotime($item['timestamp'])));
            $row++;
        }
        
        // Auto size columns
        foreach (range('A', 'G') as $col) {
            $this->sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Save file
        $filepath = STORAGE_PATH . '/exports/' . $filename;
        
        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }
        
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($filepath);
        
        return $filepath;
    }
}
