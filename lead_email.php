<?php

// Include TCPDF
include '../../../../tcpdf/tcpdf.php';

 

// Extend TCPDF to define custom header and footer
class CustomPDF extends TCPDF { 
    public function Header() {
        global $pdf;

        // Background image (always)
        $img_file = '../files/background.jpg';
        $pdf->Image($img_file, 0, 0, 210, 240, '', '', '', false, 300, '', false, false, 0);
        if ($pdf->page > 1) {
            
            global $V_COMP_FULL_ADDRESS, $V_DB_COMP_LOGO_WIDTH, $v_page_top_margin, $v_page_right_margin, $v_page_left_margin, $v_din_a4_widht,  $V_DB_COMP_FULL_ADDRESS, $V_DB_COMP_NAME, $V_DB_COMP_TELEPHONE, $v_font_text_style,  $v_total_inner_width,
             $v_global_border, $v_font_size, $v_line_enter, $v_font_size_left_box, $v_header_top_margin; 

            include '../08_table_header_identity.php';

            // Company Address 
            $pdf->SetFont('dejavusans', 'B', 11); // Increase font size
            $pdf->SetTextColor(30, 30, 30);
            $pdf->SetTopMargin($v_header_top_margin );
        }
    
    }
                
    //footer public function
   public function Footer() {
        global $V_DB_COMP_NAME, $V_DB_COMP_STREET_NO, $V_DB_COMP_POST_CODE, $V_DB_COMP_CITY, $V_DB_COMP_BANK_NAME_1, $V_DB_COMP_IBAN_1, $V_DB_COMP_BIC_1, $V_DB_COMP_TAX_NUMBER, $V_DB_COMP_MANAGER,$v_total_inner_width, $pdf,  $v_font_text_style, $v_page_bottom_margin 
        , $v_page_left_margin, $v_page_top_margin, $v_page_right_margin
        ;
        global $pdf;
        global $V_DB_COMP_BIC, $V_DB_SEMA_INVOICE_NR; // or whatever variable holds the invoice number
        global $v_page_left_margin, $v_page_right_margin, $v_font_text_style, $v_total_inner_half_width, $v_font_size_left_box, $v_global_border, $v_start_right_box_pos, 
        $v_start_y_position, $v_footer_bottom_margin, $V_DB_LEAD_ID  ;

        $pdf->SetY($v_footer_bottom_margin); // position 25mm from bottom
        $pdf->SetFont($v_font_text_style, '', 9);
        $pdf->SetTextColor(0, 0, 0);

        // Horizontal line
        $pdf->SetDrawColor(160, 160, 160); // soft grey
        $pdf->SetLineWidth(0.3);
        $pdf->Line($v_page_left_margin, $pdf->GetY(), $pdf->getPageWidth() - $v_page_right_margin, $pdf->GetY());

        // Move cursor below line
        $pdf->Ln(1.5);

        // Invoice number (italic, left)
        $pdf->SetFont($v_font_text_style, 'I', 7);
        $pdf->SetTextColor(80, 80, 80); //grey color
        $pdf->SetXY($v_page_left_margin, $pdf->GetY());
        $pdf->SetMargins($v_page_left_margin, $v_page_top_margin, $v_page_right_margin);

        $pdf->MultiCell($v_total_inner_half_width, $v_font_size_left_box, 'Lead-'.$V_DB_LEAD_ID , $v_global_border, 'L', false);


        // Page number (italic, right)
        $pdf->SetFont($v_font_text_style, '', 7);
        $pdf->SetTextColor(80, 80, 80);
        // Page number (right-aligned)
        //$pdf->SetXY($v_start_right_box_pos+6,$v_start_y_position);
        $pdf->SetXY($v_start_right_box_pos+9, $pdf->GetY()-$v_font_size_left_box); 
        $pdf->SetMargins($v_page_left_margin, $v_page_top_margin, $v_page_right_margin);
        $v_current_page_number = $pdf->getPage();
        $v_total_total_number = $pdf->getAliasNbPages();

        $pdf->MultiCell($v_total_inner_half_width, $v_font_size_left_box, 'Seite '.  $v_current_page_number  . ' von ' . $v_total_total_number , $v_global_border, 'R', false);
    
       include '../14_company_footer_info.php';

      

    }

    public function GetMultiCellHeight($w, $h, $txt, $border = 0, $align = 'J', $maxh = 0) {
        global $pdf;
        // Store current position and page
        
        $pageStart = $pdf->getPage();         // Aktuelle Seite
        $xStart    = $pdf->GetX();            // X-Start
        $yStart    = $pdf->GetY();            // Y-Start
    
        if (empty($txt)) {
            return 0;                          // Kein Inhalt → keine Höhe
        }
    
        $pdf->startTransaction();             // Start virtuellem Schreiben
        $pdf->MultiCell(
            $w,                                 // Zellenbreite
            $h,                                 // Zellenhöhe pro Zeile
            $txt,                               // Inhalt
            $border,                            // Rahmen
            $align,                             // Ausrichtung
            false,                              // Kein Hintergrund
            1,                                  // Nächste Zeile
            $xStart,                            // Start-X
            $yStart,                            // Start-Y
            true,                               // Reset Height
            0,                                  // Kein Stretch
            false,                              // Kein HTML
            true,                               // Auto Padding
            $maxh,                              // Optional: max Höhe
            $align == 'C' ? 'M' : 'T'           // Vertikale Ausrichtung
        );
    
        $heightUsed = $pdf->GetY() - $yStart; // Differenz berechnen
    
        $pdf->rollbackTransaction(true);      // Alles rückgängig machen
    
        return $heightUsed;                    // Höhe zurückgeben
    }
   
}






// Create new PDF using the custom class
$pdf = new CustomPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Document metadata
$pdf->SetCreator('Seniocon CRM');
$pdf->SetAuthor('Seniocon');
$pdf->SetTitle('Lead E-Mail');
$pdf->SetSubject('Lead E-Mail');
$pdf->SetKeywords('Lead E-Mail, PDF, TCPDF');
 

// Layout settings
$v_page_left_margin         = 25;
$v_page_top_margin          = 25;
$v_page_right_margin        = 20;
$v_page_bottom_margin       = -20;
$v_footer_bottom_margin     = -25;
$v_din_a4_widht             = $pdf->getPageWidth();
$v_din_a4_height            = $pdf->getPageHeight();
$v_din_a4_height_max_footer = $pdf->getPageHeight() - (50);
$v_total_inner_width        = $v_din_a4_widht - ($v_page_left_margin + $v_page_right_margin);
$v_total_inner_half_width   = $v_total_inner_width / 2;
$v_global_border            = 0;
$v_line_enter               = 5;
$v_footer_auto_break        = 45; 
$v_header_top_margin        = 45;       
$v_text_hight               = 10;
$v_font_size                = 10;
$v_font_text_style          = "dejavusans";
$v_table_header_border      = 'LTRB';

// set the footer margin
$pdf->SetAutoPageBreak(true, $v_footer_auto_break);
// Add a new page to the PDF
$pdf->AddPage('P', 'A4');
$pdf->SetMargins($v_page_left_margin, $v_page_top_margin, $v_page_right_margin);
$pdf->SetFont($v_font_text_style, '', $v_text_hight);

// database connection 
require '../../../../database_configuration.php';
require '../../01_CURRENT_SERVER_IP.php';
/*----------------------------------------------------
-- PARAMETER VALUE OVER URL
------------------------------------------------------*/ 
$V_WHERE_CONDITION_VALUE = $_GET['encryptedvalue'] ?? null;
$GET_URL_LANG_CODE       = $_GET['language'] ?? 'de'; // or your default

if (!$V_WHERE_CONDITION_VALUE) {
    die("Missing required parameter: encryptedvalue");
}

// Decode 5x only if it's a string
for ($i = 0; $i < 5; $i++) {
    $V_WHERE_CONDITION_VALUE = base64_decode($V_WHERE_CONDITION_VALUE, true);
    if ($V_WHERE_CONDITION_VALUE === false) {
        die("Invalid V_WHERE_CONDITION_VALUE encoding.");
    }
} 

include '../01_stored_function.php';
// === Define filter column and value ===
$primary_column         = 'SEMA_ID';
$where_condition_value  = $V_WHERE_CONDITION_VALUE;

// === Define which view to query ===
$view_name = 'v_pdf_lead_company_data';

include '../02_collect_all_data.php';
include '../03_company_logo.php';
include '../04_header_company_info.php';
include '05_lead_email_subject.php';
include '06_lead_email_content.php';


$pdf->Output('lead_email.pdf', 'I');