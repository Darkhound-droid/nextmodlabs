<?php
 


// fillup variable with db value
$CUST_EMAIL_SUBJECT               = $V_DB_CUST_EMAIL_SUBJECT; 
$pdf->SetTextColor(80, 80, 80);// Output title text
$pdf->writeHTML($CUST_EMAIL_SUBJECT, true, false, true, false, '');

// Add vertical space before next section
$pdf->Ln($v_line_enter);