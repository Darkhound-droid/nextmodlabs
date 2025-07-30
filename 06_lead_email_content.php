<?php 

// fillup variable with db value
$CUST_EMAIL_CONTENT           = $V_DB_CUST_EMAIL_CONTENT;

// output the HTML content
$pdf->writeHTML($CUST_EMAIL_CONTENT, true, false, true, false, '');


//Add vertical space before next section
$pdf->Ln($v_line_enter);