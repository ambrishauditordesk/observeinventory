<?php 
   include 'dbconnection.php';
   include 'moneyFormatterFPDF.php';
   require "vendor/fpdf183/fpdf.php";

   $type = 'BS';
   $mid = 65;
   global $type, $mid;

   class PDF extends FPDF{
      // Page header
      function Header(){
         include 'dbconnection.php';
         
         $wid = $_SESSION['workspace_id'];
         $clientId = $_SESSION['client_id'];

         $this->SetFont('Arial','B',15);
         $this->SetX($GLOBALS['mid']);
         $this->Cell(80,10,$con->query("SELECT concat(name, ' ', const) details FROM `client` inner join industry on client.industry_id = industry.id inner join constitution on client.const_id = constitution.id where client.id = $clientId")->fetch_assoc()['details'],0,1,'C');
         $this->SetFont('Arial','I',10);
         $this->SetX($GLOBALS['mid']);
         $this->Cell(80,10,$con->query("SELECT concat(address,', ', city, ', ', state, '-', pincode,', ',country) address FROM `client` inner join industry on client.industry_id = industry.id inner join constitution on client.const_id = constitution.id where client.id = $clientId")->fetch_assoc()['address'],0,1,'C');
         $this->SetFont('Arial','I',10);
         $this->SetX($GLOBALS['mid']);

         if($GLOBALS['type'] == 'BS'){  
            $this->Cell(80,10,$con->query("SELECT concat('Balance Sheet as on ', dateto) workspaceDate from workspace where client_id = $clientId")->fetch_assoc()['workspaceDate'],0,1,'C');
         }
         else{
            $this->Cell(80,10,$con->query("SELECT concat('Profit and Loss as on ', dateto) workspaceDate from workspace where client_id = $clientId")->fetch_assoc()['workspaceDate'],0,1,'C');
         }
         $columnNames = [' ','Particulars', 'As on '.$con->query("SELECT dateto from workspace where id = $wid")->fetch_assoc()['dateto'], 'As on '.$con->query("SELECT datefrom from workspace where id = $wid")->fetch_assoc()['datefrom']];
         //column width
         $colWidth = array(15, 85, 45, 45);
         $this->Ln(2.5);
         $this->SetTextColor(255);
         $this->SetFillColor(58, 148, 222);
         $this->SetFont('Arial','B',10);
         for($i=0;$i<count($columnNames);$i++)
         {   
            $this->Cell($colWidth[$i],7,$columnNames[$i],1,0,'C',true);
         }
         $this->Ln(6);
      }

      // Page footer
      function Footer(){
         $this->SetFont('Arial','I',8);
         $this->SetY(-75);

         $this->Ln(10);
         $this->Rect($this->GetX(), $this->GetY(), 0, 0,190,60);
         $this->Cell(30,12, "Place:______________________" ,0,0);//left
         $this->Cell(115);
         $this->Cell(30,12, "______________________" ,0,0);//right
         $this->Ln(5);
         $this->Cell(30,12, "Date: ".date('d/m/Y') ,0,0);//unix date format-left
         $this->Cell(115);
         $this->Cell(30,12, "______________________" ,0,0);//right
         $this->Ln(5);
         $this->Cell(30,12, "UDIN:______________________" ,0,0);
         $this->Cell(115);
         $this->Cell(30,12, "______________________" ,0,0);//right
         
         
         $this->Ln(40);
         $this->Cell(15);
         $this->Cell(30,12, "______________________" ,0,0);//right
         $this->Cell(30);
         $this->Cell(30,12, "______________________" ,0,0);//right
         $this->Cell(30);
         $this->Cell(30,12, "______________________" ,0,0);//right
      }
   }

   if(!isset($_SESSION)){
       session_start();
    }

   $wid = $_SESSION['workspace_id'];
   $clientId = $_SESSION['client_id'];
   
   $mid = 65;
   $pdf = new PDF();
   $pdf->AddPage();
   $pdf->SetFont('Arial','B',16);
   $pdf->SetAutoPageBreak(true);
   $pdf->SetTitle("Unaudited Financial Statement");
   //table footer
   $colWidth = array(15, 85, 45, 45);
   $pdf->SetTextColor(0,0,0);

   $accountTypeResult = $con->query("SELECT DISTINCT accounts_type, accountTypeSeqNumber from tb_performance_map where workspace_id='$wid' and ( accounts_type not like '%Expense%' and accounts_type not like '%Revenue%' ) order by accountTypeSeqNumber");
   $typeCounter = 'A';

    while($accountTypeRow = $accountTypeResult->fetch_assoc()){
        $begBalTotal = $auditedTotal = 0;
        
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell($colWidth[0],6,'('.$typeCounter++.')', "RL",0,'C');
        $pdf->Cell($colWidth[1],6,strtoupper($accountTypeRow['accounts_type']),'R',0,'L');
        $pdf->Cell($colWidth[2],6,' ','R',0,'R');
        $pdf->Cell($colWidth[3],6,' ','R',0,'R');
        $pdf->Ln(6);

        $accountClassResult = $con->query("SELECT accounts_class from tb_performance_map where accounts_type ='".$accountTypeRow['accounts_type']."' and workspace_id='".$wid."' group by accounts_class");
        $accountClassCounter = 1;
        while($accountClassRow = $accountClassResult->fetch_assoc()){
            $pdf->SetFont('Arial','B',10);
            
            $wordsArray = explode(' ',$accountClassRow['accounts_class']);
            $wordsCount = sizeof($wordsArray);
            $newWord = '';
            $firstLine = 1;
            
            $pdf->Cell($colWidth[0],6,$accountClassCounter++, "RL",0,'C');
            for($lines = 0; $lines < $wordsCount; $lines++){
                if( (strlen($newWord) + strlen($wordsArray[$lines]) + 1) <= 38 ){
                    $newWord .= $wordsArray[$lines].' ';
                }
                else{
                    $firstLine = 0;
                    $pdf->Cell($colWidth[1],6,strtoupper($newWord),"R",0,'L');
                    $pdf->Cell($colWidth[2],6,' ',"R",0,'R');
                    $pdf->Cell($colWidth[3],6,' ',"R",0,'R');
                    $pdf->Ln(6);
                    $newWord = $wordsArray[$lines];
                }
            }
            if(strlen($newWord) <= 38 && !$firstLine){
                $pdf->Cell($colWidth[0],6,' ', "RL",0,'C');
            }
            $pdf->Cell($colWidth[1],6,strtoupper($newWord),"R",0,'L');
            $pdf->Cell($colWidth[2],6,' ',"R",0,'R');
            $pdf->Cell($colWidth[3],6,' ',"R",0,'R');
            $pdf->Ln(6);


            $financialStatementResult = $con->query("SELECT accounts_name, sum(tb_performance_map.amount) unaudited, sum(tb_performance_map.beg_amount) beg_bal from tb_performance_map where workspace_id = $wid and accounts_type = '".$accountTypeRow['accounts_type']."' and accounts_class = '".$accountClassRow['accounts_class']."' GROUP BY accounts_name");
            $financialStatementCounter = 'a';
            $pdf->SetFont('Arial','',10);
            while($financialStatementRow = $financialStatementResult->fetch_assoc()){
                $adjustment = $con->query("SELECT summery_of_misstatements_log.account, sum(summery_of_misstatements_log.amount) adj from summery_of_misstatements_log INNER join summery_of_misstatements on summery_of_misstatements_log.summery_of_misstatements_id=summery_of_misstatements.id where summery_of_misstatements.workspace_id = $wid and summery_of_misstatements_log.account = '".$financialStatementRow['accounts_name']."' GROUP BY summery_of_misstatements_log.account");
                $adjustment = $adjustment->num_rows > 0 ? $adjustment->fetch_assoc()['adj'] : 0;
                $audited = $financialStatementRow['unaudited']+$adjustment;
                $auditedTotal += $financialStatementRow['unaudited']+$adjustment;
                $begBalTotal += $financialStatementRow['beg_bal'];

                if ($pdf->GetY() > 220) {
                    $pdf->Cell(array_sum($colWidth),0,'','T');
                    $pdf->Ln(0);
                    $pdf->AddPage();
                }
                
                $pdf->Cell($colWidth[0],6,' ', "RL",0,'C');
                $pdf->Cell($colWidth[1],6,'('.$financialStatementCounter++.') '.$financialStatementRow['accounts_name'],"R",0,'L');
                $pdf->Cell($colWidth[2],6, numberToCurrency($audited),"R",0,'R');
                $pdf->Cell($colWidth[3],6, numberToCurrency($financialStatementRow['beg_bal']),"R",0,'R');
                $pdf->Ln(6);
            }
        }
        $pdf->Cell(array_sum($colWidth),0,'','T');
        $pdf->Ln(0);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell($colWidth[0],6,' ', "RL",0,'C');
        $pdf->Cell($colWidth[1],6,'Total',"R",0,'C');
        $pdf->Cell($colWidth[2],6, numberToCurrency($auditedTotal),"R",0,'R');
        $pdf->Cell($colWidth[3],6, numberToCurrency($begBalTotal),"R",0,'R');
        $pdf->Ln(6);
        
        $pdf->Cell(array_sum($colWidth),0,'','T');
        $pdf->Ln(0);
    }
    $type = 'PL';
    $pdf->AddPage();
    
    $accountTypeResult = $con->query("SELECT DISTINCT accounts_type, accountTypeSeqNumber from tb_performance_map where workspace_id='$wid' and ( accounts_type like '%Expense%' or accounts_type like '%Revenue%' ) order by accountTypeSeqNumber");
    $typeCounter = 'A';

    while($accountTypeRow = $accountTypeResult->fetch_assoc()){
        $begBalTotal = $auditedTotal = 0;
      
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell($colWidth[0],6,'('.$typeCounter++.')', "RL",0,'C');
        $pdf->Cell($colWidth[1],6,strtoupper($accountTypeRow['accounts_type']),'R',0,'L');
        $pdf->Cell($colWidth[2],6,' ','R',0,'R');
        $pdf->Cell($colWidth[3],6,' ','R',0,'R');
        $pdf->Ln(6);

        $accountClassResult = $con->query("SELECT accounts_class from tb_performance_map where accounts_type ='".$accountTypeRow['accounts_type']."' and workspace_id='".$wid."' group by accounts_class");
        $accountClassCounter = 1;
        while($accountClassRow = $accountClassResult->fetch_assoc()){
            $pdf->SetFont('Arial','B',10);
            
            $wordsArray = explode(' ',$accountClassRow['accounts_class']);
            $wordsCount = sizeof($wordsArray);
            $newWord = '';
            $firstLine = 1;
            
            $pdf->Cell($colWidth[0],6,$accountClassCounter++, "RL",0,'C');
            for($lines = 0; $lines < $wordsCount; $lines++){
                if( (strlen($newWord) + strlen($wordsArray[$lines]) + 1) <= 38 ){
                    $newWord .= $wordsArray[$lines].' ';
                }
                else{
                    $firstLine = 0;
                    $pdf->Cell($colWidth[1],6,strtoupper($newWord),"R",0,'L');
                    $pdf->Cell($colWidth[2],6,' ',"R",0,'R');
                    $pdf->Cell($colWidth[3],6,' ',"R",0,'R');
                    $pdf->Ln(6);
                    $newWord = $wordsArray[$lines];
                }
            }
            if(strlen($newWord) <= 38 && !$firstLine){
                $pdf->Cell($colWidth[0],6,' ', "RL",0,'C');
            }
            $pdf->Cell($colWidth[1],6,strtoupper($newWord),"R",0,'L');
            $pdf->Cell($colWidth[2],6,' ',"R",0,'R');
            $pdf->Cell($colWidth[3],6,' ',"R",0,'R');
            $pdf->Ln(6);


            $financialStatementResult = $con->query("SELECT accounts_name, sum(tb_performance_map.amount) unaudited, sum(tb_performance_map.beg_amount) beg_bal from tb_performance_map where workspace_id = $wid and accounts_type = '".$accountTypeRow['accounts_type']."' and accounts_class = '".$accountClassRow['accounts_class']."' GROUP BY accounts_name");
            $financialStatementCounter = 'a';
            $pdf->SetFont('Arial','',10);
            while($financialStatementRow = $financialStatementResult->fetch_assoc()){
                $adjustment = $con->query("SELECT summery_of_misstatements_log.account, sum(summery_of_misstatements_log.amount) adj from summery_of_misstatements_log INNER join summery_of_misstatements on summery_of_misstatements_log.summery_of_misstatements_id=summery_of_misstatements.id where summery_of_misstatements.workspace_id = $wid and summery_of_misstatements_log.account = '".$financialStatementRow['accounts_name']."' GROUP BY summery_of_misstatements_log.account");
                $adjustment = $adjustment->num_rows > 0 ? $adjustment->fetch_assoc()['adj'] : 0;
                $audited = $financialStatementRow['unaudited']+$adjustment;
                $auditedTotal += $financialStatementRow['unaudited']+$adjustment;
                $begBalTotal += $financialStatementRow['beg_bal'];

                if ($pdf->GetY() > 220) {
                    $pdf->Cell(array_sum($colWidth),0,'','T');
                    $pdf->Ln(0);
                    $pdf->AddPage();
                }
                
                $pdf->Cell($colWidth[0],6,' ', "RL",0,'C');
                $pdf->Cell($colWidth[1],6,'('.$financialStatementCounter++.') '.$financialStatementRow['accounts_name'],"R",0,'L');
                $pdf->Cell($colWidth[2],6, numberToCurrency($audited),"R",0,'R');
                $pdf->Cell($colWidth[3],6, numberToCurrency($financialStatementRow['beg_bal']),"R",0,'R');
                $pdf->Ln(6);
            }
        }
        $pdf->Cell(array_sum($colWidth),0,'','T');
        $pdf->Ln(0);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell($colWidth[0],6,' ', "RL",0,'C');
        $pdf->Cell($colWidth[1],6,'Total',"R",0,'C');
        $pdf->Cell($colWidth[2],6, numberToCurrency($auditedTotal),"R",0,'R');
        $pdf->Cell($colWidth[3],6, numberToCurrency($begBalTotal),"R",0,'R');
        $pdf->Ln(6);
        
        $pdf->Cell(array_sum($colWidth),0,'','T');
        $pdf->Ln(0);
    }

    $pdf->Ln(0);
    // $pdf->Output('Unaudited-Financial-Statement.pdf', 'D');
    $pdf->Output();
    $pdf->AliasNbPages();
?>