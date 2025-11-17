<?php

namespace App\Services;

use TCPDF;

/**
 * PDF Service
 * Generate PDF documents, especially for Halal Certificates
 * 
 * Installation: composer require tecnickcom/tcpdf
 */
class PDFService
{
    private $pdf;
    private $certificateTemplate;
    
    public function __construct()
    {
        if (!class_exists('TCPDF')) {
            throw new \Exception('TCPDF library not installed. Run: composer require tecnickcom/tcpdf');
        }
        
        // Initialize TCPDF
        $this->pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $this->pdf->SetCreator('Website Magang Kemenag UI');
        $this->pdf->SetAuthor('Kementerian Agama RI');
        $this->pdf->SetTitle('Sertifikat Halal');
        
        // Remove default header/footer
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        
        // Set margins
        $this->pdf->SetMargins(15, 15, 15);
        $this->pdf->SetAutoPageBreak(TRUE, 15);
        
        // Set font
        $this->pdf->SetFont('helvetica', '', 12);
    }
    
    /**
     * Generate Halal Certificate
     */
    public function generateHalalCertificate($certificateData)
    {
        // Add a page
        $this->pdf->AddPage();
        
        // Certificate Number (top right)
        $this->pdf->SetFont('helvetica', 'B', 10);
        $this->pdf->SetXY(140, 20);
        $this->pdf->Cell(50, 10, 'No: ' . $certificateData['certificate_number'], 0, 1, 'R');
        
        // Logo (if exists)
        $logoPath = PUBLIC_PATH . 'assets/images/logo-kemenag.png';
        if (file_exists($logoPath)) {
            $this->pdf->Image($logoPath, 85, 25, 40, 0, 'PNG');
        }
        
        // Title
        $this->pdf->SetFont('helvetica', 'B', 24);
        $this->pdf->SetY(70);
        $this->pdf->Cell(0, 15, 'SERTIFIKAT HALAL', 0, 1, 'C');
        
        // Subtitle
        $this->pdf->SetFont('helvetica', 'I', 12);
        $this->pdf->Cell(0, 10, 'Kementerian Agama Republik Indonesia', 0, 1, 'C');
        
        // Add some spacing
        $this->pdf->Ln(15);
        
        // Certificate content
        $this->pdf->SetFont('helvetica', '', 12);
        
        $html = '
        <div style="text-align: justify; line-height: 1.8;">
            <p>Dengan ini menyatakan bahwa:</p>
            
            <table cellpadding="5" style="margin: 20px 0;">
                <tr>
                    <td width="150"><strong>Nama Perusahaan</strong></td>
                    <td width="10">:</td>
                    <td>' . htmlspecialchars($certificateData['company_name']) . '</td>
                </tr>
                <tr>
                    <td><strong>Alamat</strong></td>
                    <td>:</td>
                    <td>' . htmlspecialchars($certificateData['company_address']) . '</td>
                </tr>
                <tr>
                    <td><strong>Produk</strong></td>
                    <td>:</td>
                    <td>' . htmlspecialchars($certificateData['product_name']) . '</td>
                </tr>
                <tr>
                    <td><strong>Kategori Produk</strong></td>
                    <td>:</td>
                    <td>' . htmlspecialchars($certificateData['product_category']) . '</td>
                </tr>
            </table>
            
            <p>Telah memenuhi persyaratan sertifikasi halal sesuai dengan peraturan perundang-undangan 
            yang berlaku dan telah diperiksa oleh auditor halal yang kompeten.</p>
            
            <p><strong>Sertifikat ini berlaku sampai dengan: ' . date('d F Y', strtotime($certificateData['valid_until'])) . '</strong></p>
        </div>
        ';
        
        $this->pdf->writeHTML($html, true, false, true, false, '');
        
        // Add some spacing before signature
        $this->pdf->Ln(20);
        
        // Signature section
        $this->pdf->SetFont('helvetica', '', 11);
        
        // Date and place
        $this->pdf->Cell(0, 10, 'Jakarta, ' . date('d F Y', strtotime($certificateData['issued_date'])), 0, 1, 'R');
        
        // Signature title
        $this->pdf->SetFont('helvetica', 'B', 11);
        $this->pdf->Cell(0, 10, 'Kepala Bidang Sertifikasi Halal', 0, 1, 'R');
        
        // Space for signature
        $this->pdf->Ln(20);
        
        // Signer name
        $this->pdf->SetFont('helvetica', 'BU', 11);
        $this->pdf->Cell(0, 10, $certificateData['signer_name'] ?? 'Dr. H. Muhammad Ali, M.Ag', 0, 1, 'R');
        
        // Signer NIP
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->Cell(0, 10, 'NIP. ' . ($certificateData['signer_nip'] ?? '197001011995031001'), 0, 1, 'R');
        
        // Border decoration
        $this->pdf->SetLineStyle(['width' => 2, 'color' => [0, 100, 0]]);
        $this->pdf->Rect(10, 10, 190, 277, 'D');
        
        // QR Code for verification (if available)
        if (!empty($certificateData['verification_url'])) {
            $qrStyle = [
                'border' => 0,
                'padding' => 0,
                'fgcolor' => [0, 0, 0],
                'bgcolor' => [255, 255, 255]
            ];
            $this->pdf->write2DBarcode(
                $certificateData['verification_url'], 
                'QRCODE,L', 
                15, 
                255, 
                30, 
                30, 
                $qrStyle, 
                'N'
            );
            
            $this->pdf->SetFont('helvetica', '', 8);
            $this->pdf->SetXY(15, 286);
            $this->pdf->Cell(30, 5, 'Verifikasi', 0, 0, 'C');
        }
        
        return $this->pdf;
    }
    
    /**
     * Save PDF to file
     */
    public function savePDF($filename, $destination = 'F')
    {
        $filepath = STORAGE_PATH . 'uploads/certificates/' . $filename;
        
        // Ensure directory exists
        $dir = dirname($filepath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        // Output PDF
        $this->pdf->Output($filepath, $destination);
        
        return 'uploads/certificates/' . $filename;
    }
    
    /**
     * Output PDF to browser
     */
    public function outputPDF($filename)
    {
        $this->pdf->Output($filename, 'I');
    }
    
    /**
     * Download PDF
     */
    public function downloadPDF($filename)
    {
        $this->pdf->Output($filename, 'D');
    }
    
    /**
     * Generate Certificate Application Receipt
     */
    public function generateApplicationReceipt($applicationData)
    {
        $this->pdf->AddPage();
        
        // Header
        $this->pdf->SetFont('helvetica', 'B', 18);
        $this->pdf->Cell(0, 15, 'TANDA TERIMA PENGAJUAN', 0, 1, 'C');
        $this->pdf->Cell(0, 10, 'SERTIFIKAT HALAL', 0, 1, 'C');
        
        $this->pdf->Ln(10);
        
        // Ticket Number
        $this->pdf->SetFont('helvetica', 'B', 14);
        $this->pdf->Cell(0, 10, 'Nomor Tiket: ' . $applicationData['ticket_number'], 0, 1, 'C');
        
        $this->pdf->Ln(10);
        
        // Application details
        $this->pdf->SetFont('helvetica', '', 12);
        
        $html = '
        <table cellpadding="6" border="1">
            <tr>
                <td width="150" bgcolor="#f0f0f0"><strong>Tanggal Pengajuan</strong></td>
                <td>' . date('d F Y H:i', strtotime($applicationData['submitted_at'])) . '</td>
            </tr>
            <tr>
                <td bgcolor="#f0f0f0"><strong>Nama Perusahaan</strong></td>
                <td>' . htmlspecialchars($applicationData['company_name']) . '</td>
            </tr>
            <tr>
                <td bgcolor="#f0f0f0"><strong>Email</strong></td>
                <td>' . htmlspecialchars($applicationData['email']) . '</td>
            </tr>
            <tr>
                <td bgcolor="#f0f0f0"><strong>Telepon</strong></td>
                <td>' . htmlspecialchars($applicationData['phone']) . '</td>
            </tr>
            <tr>
                <td bgcolor="#f0f0f0"><strong>Nama Produk</strong></td>
                <td>' . htmlspecialchars($applicationData['product_name']) . '</td>
            </tr>
            <tr>
                <td bgcolor="#f0f0f0"><strong>Kategori Produk</strong></td>
                <td>' . htmlspecialchars($applicationData['product_category']) . '</td>
            </tr>
            <tr>
                <td bgcolor="#f0f0f0"><strong>Status</strong></td>
                <td><strong>' . strtoupper($applicationData['status']) . '</strong></td>
            </tr>
        </table>
        ';
        
        $this->pdf->writeHTML($html, true, false, true, false, '');
        
        $this->pdf->Ln(15);
        
        // Instructions
        $this->pdf->SetFont('helvetica', 'B', 12);
        $this->pdf->Cell(0, 10, 'PETUNJUK:', 0, 1);
        
        $this->pdf->SetFont('helvetica', '', 11);
        $instructions = '
        <ol>
            <li>Simpan tanda terima ini sebagai bukti pengajuan Anda</li>
            <li>Gunakan nomor tiket untuk melacak status pengajuan</li>
            <li>Anda dapat login ke website untuk melihat progress pengajuan</li>
            <li>Kami akan mengirimkan notifikasi melalui email dan dalam sistem</li>
            <li>Proses verifikasi memakan waktu 3-7 hari kerja</li>
            <li>Untuk pertanyaan, hubungi: ' . CONTACT_EMAIL . '</li>
        </ol>
        ';
        
        $this->pdf->writeHTML($instructions, true, false, true, false, '');
        
        return $this->pdf;
    }
    
    /**
     * Generate Report (Generic)
     */
    public function generateReport($title, $data, $columns)
    {
        $this->pdf->AddPage();
        
        // Title
        $this->pdf->SetFont('helvetica', 'B', 16);
        $this->pdf->Cell(0, 15, $title, 0, 1, 'C');
        $this->pdf->Ln(5);
        
        // Date
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->Cell(0, 10, 'Generated: ' . date('d F Y H:i'), 0, 1, 'R');
        $this->pdf->Ln(5);
        
        // Table header
        $this->pdf->SetFont('helvetica', 'B', 10);
        $this->pdf->SetFillColor(200, 200, 200);
        
        foreach ($columns as $column) {
            $this->pdf->Cell($column['width'], 10, $column['label'], 1, 0, 'C', true);
        }
        $this->pdf->Ln();
        
        // Table rows
        $this->pdf->SetFont('helvetica', '', 9);
        foreach ($data as $row) {
            foreach ($columns as $column) {
                $value = $row[$column['field']] ?? '-';
                $this->pdf->Cell($column['width'], 10, $value, 1, 0, $column['align'] ?? 'L');
            }
            $this->pdf->Ln();
        }
        
        return $this->pdf;
    }
}
