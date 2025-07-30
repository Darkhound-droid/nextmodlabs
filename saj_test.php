<?php
require '../../../../tcpdf/tcpdf.php';

/* -------------------------------------------------
   Hilfsfunktionen
   ------------------------------------------------- */
function euro($v){ return number_format($v,2,',','.').' €'; }

function printHeader($pdf,$header,$colW){
    $pdf->SetFont('','B');
    foreach($header as $i=>$txt){
        $pdf->MultiCell($colW[$i],8,$txt,1,'C',0,($i==3?1:0));
    }
    $pdf->Ln(2);
    $pdf->SetFont('','');
}

function printSubtotal($pdf,$colW,$label,$amount){
    $pdf->SetFont('','B');
    $pdf->MultiCell($colW[0]+$colW[1]+$colW[2],6,$label,1,'R',0,0);
    $pdf->MultiCell($colW[3],6,euro($amount),1,'R',0,1);
    $pdf->SetFont('','');
}

/* -------------------------------------------------
   PDF-Basis
   ------------------------------------------------- */
$pdf = new TCPDF(PDF_PAGE_ORIENTATION,PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);
$pdf->SetMargins(PDF_MARGIN_LEFT,PDF_MARGIN_TOP,PDF_MARGIN_RIGHT);
$pdf->SetAutoPageBreak(true,PDF_MARGIN_BOTTOM);
$pdf->SetFont('helvetica','',11);

/* -------------------------------------------------
   Dummy-Daten
   ------------------------------------------------- */
$items=[];
for($i=1;$i<=100;$i++){
    $words = preg_split('/\s+/', str_repeat('Lorem ipsum dolor sit amet ', rand(20,50)));
    $price = rand(500,9000)/100;
    $items[]=[
        'nr'    => sprintf('A%04d',$i),
        'words' => $words,
        'qty'   => rand(1,15),
        'price' => $price
    ];
}


/* -------------------------------------------------
   Layout-Konstanten
   ------------------------------------------------- */
   
$pdf->AddPage();



/* ----------------------------------------------------------------------
   Column layout (percentage-based) – MUST sum to 100 %
   ---------------------------------------------------------------------- */
$colPercent = [10, 65, 10, 15];   // 10 % | 65 % | 10 % | 15 % = 100 %

// ── Calculate the usable page width (page width minus left/right margins)
$usableW = $pdf->getPageWidth()
          - $pdf->getMargins()['left']
          - $pdf->getMargins()['right'];

// ── Convert the percentage array to absolute millimetre widths
$colW = array_map(
    fn ($p) => $usableW * ($p / 100),
    $colPercent
);

// Column titles in the same order as $colW
$header  = ['Art.-Nr.', 'Beschreibung', 'Menge', 'Einzelpreis'];

// Height (in mm) reserved for subtotal / carry-forward rows
$footerH = 50;

printHeader($pdf,$header,$colW);


/* -------------------------------------------------
   Ausgabe
   ------------------------------------------------- */


$pageReserved  = 0.0;       // nicht mehr gebraucht, aber Platzhalter
$cumulativeSum = 0.0;       // laufende Summe über alle Seiten

foreach($items as $row){

    $nr    = $row['nr'];
    $qty   = $row['qty'];
    $price = $row['price'];
    $priceStr = euro($price);
    $words = $row['words'];            // Array der Wörter
    $priceAdded = false;

    while(!empty($words)){

        /* Platz checken: Zeile + Zwischensumme MUSS passen ----------- */
        $space = $pdf->getPageHeight() - $pdf->getBreakMargin() - $pdf->GetY();

        /* Binary Search: größter Chunk der passt */
        $low=1; $high=count($words); $chunkWords=[];
        while($low <= $high){
            $mid=intdiv($low+$high,2);
            $test=implode(' ',array_slice($words,0,$mid));
            $h   =$pdf->getStringHeight($colW[1],$test);
            if($h <= $space - $footerH){   // Reserviere Platz für Footer
                $chunkWords=array_slice($words,0,$mid);
                $low=$mid+1;
            }else{
                $high=$mid-1;
            }
        }

        /* Falls gar kein Wort passt → Seite beenden ------------------ */
        if(!$chunkWords){
            // Zwischensumme (kumulativ) auf alter Seite
            printSubtotal($pdf,$colW,'Zwischensumme',$cumulativeSum);

            // neue Seite + Header + Übertrag
            $pdf->AddPage();
            printHeader($pdf,$header,$colW);
            printSubtotal($pdf,$colW,'Übertrag',$cumulativeSum);

            // weiter mit gleicher Wortliste
            continue;
        }

        /* Split & Höhe bestimmen */
        $words = array_slice($words, count($chunkWords));
        $chunk = implode(' ', $chunkWords);
        $rowH  = max(6, $pdf->getStringHeight($colW[1], $chunk));

        /* Zeile drucken --------------------------------------------- */
        $pdf->MultiCell($colW[0],$rowH,$nr,        1,'L',0,0);
        $pdf->MultiCell($colW[1],$rowH,$chunk,     1,'L',0,0);
        $pdf->MultiCell($colW[2],$rowH,$qty,       1,'C',0,0);
        $pdf->MultiCell($colW[3],$rowH,$priceStr,  1,'R',0,1);

        /* Summe nur in erster Teilzeile addieren */
        if(!$priceAdded){
            $cumulativeSum += $price;
            $priceAdded = true;
        }

        /* Folgezeilen ohne Nr/Menge/Preis */
        $nr=$qty=$priceStr='';
    }

    /* Prüfen: Passt Zwischensumme noch drauf? ----------------------- */
    $space = $pdf->getPageHeight() - $pdf->getBreakMargin() - $pdf->GetY();
    if($space < $footerH){
        // zunächst Seite beenden ohne Zwischensumme
        $pdf->AddPage();
        printHeader($pdf,$header,$colW);
        printSubtotal($pdf,$colW,'Übertrag',$cumulativeSum);
    }
}

/* Schluss-Zwischensumme der letzten Seite */
printSubtotal($pdf,$colW,'Zwischensumme',$cumulativeSum);

/* -------------------------------------------------
   Ausgabe
   ------------------------------------------------- */
$pdf->Output('invoice_running_subtotals.pdf','I');
?>
