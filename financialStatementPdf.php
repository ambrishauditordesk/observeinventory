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
            
            $this->Ln(2.5);
            $this->SetTextColor(255);
            $this->SetFillColor(58, 148, 222);
            $this->SetFont('Arial','B',10);
            if($con->query("SELECT const_id FROM client where id = $clientId")->fetch_assoc()['const_id'] == 2){
                $columnNames = [' ','Particulars', 'As on '.$con->query("SELECT dateto from workspace where id = $wid")->fetch_assoc()['dateto'], 'As on '.$con->query("SELECT datefrom from workspace where id = $wid")->fetch_assoc()['datefrom']];
                //column width
                $colWidth = array(15, 85, 45, 45);
                for($i=0;$i<count($columnNames);$i++){   
                    $this->Cell($colWidth[$i],7,$columnNames[$i],1,0,'C',true);
                }
            }
            else{
                //table headers
                $columnNames = ['Particulars','Amount', 'Particulars', 'Amount'];

                //column width
                $colWidth = array(70,25,70,25);

                $pdf->Cell($colWidth[0],10,$columnNames[0],1,0,'C',true);
                //spliting header after liability into two rows
                for($i=1;$i<count($columnNames);$i++){   
                    if($i ==3) {
                        $pdf->Cell($colWidth[$i],5,$columnNames[$i],'LRT',1,'C');
                    }
                    else {
                        $pdf->Cell($colWidth[$i],5,$columnNames[$i],'LRT',0,'C');
                    }
                }
                $pdf->Cell($colWidth[0]);

                $arrayOfDateAndAssetHeaders= [$con->query("SELECT dateto from workspace where id = $wid")->fetch_assoc()['dateto'],'', $con->query("SELECT dateto from workspace where id = $wid")->fetch_assoc()['dateto']];
                for($i=1;$i<count($columnNames);$i++)
                {   
                    if($i==2) {
                        $pdf->Cell($colWidth[$i],5,$arrayOfDateAndAssetHeaders[$i-1],'LRB',0,'C');
                    }
                    else {
                        $pdf->Cell($colWidth[$i],5,$arrayOfDateAndAssetHeaders[$i-1],1,0,'C');
                    }    // $pdf->Cell($colWidth[$i],5,'',1,0,'C');
                    // $pdf->Cell($colWidth[$i],5,$columnNames[$i],1,0,'C');
                }

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

    session_start();

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

    $const = $con->query("SELECT const_id FROM client where id = $clientId")->fetch_assoc()['const_id'];
    if($const == 2){
        $accountTypeResult = $con->query("SELECT DISTINCT account_type, accountTypeSeqNumber from trial_balance where workspace_id='$wid' and ( account_type not like '%Expense%' and account_type not like '%Revenue%' ) order by accountTypeSeqNumber");
        $typeCounter = 'A';

        while($accountTypeRow = $accountTypeResult->fetch_assoc()){
            $cyBegBalTotal = $cyFinalBalTotal = 0;
            
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell($colWidth[0],6,'('.$typeCounter++.')', "RL",0,'C');
            $pdf->Cell($colWidth[1],6,strtoupper($accountTypeRow['account_type']),'R',0,'L');
            $pdf->Cell($colWidth[2],6,' ','R',0,'R');
            $pdf->Cell($colWidth[3],6,' ','R',0,'R');
            $pdf->Ln(6);

            $accountClassResult = $con->query("SELECT account_class from trial_balance where account_type ='".$accountTypeRow['account_type']."' and workspace_id='".$wid."' group by account_class");
            $accountClassCounter = 1;
            while($accountClassRow = $accountClassResult->fetch_assoc()){
                $pdf->SetFont('Arial','B',10);
                
                $wordsArray = explode(' ',$accountClassRow['account_class']);
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


                $financialStatementResult = $con->query("SELECT max(financial_statement) financial_statement, sum(cy_beg_bal) cy_beg_bal, sum(cy_final_bal) cy_final_bal from trial_balance where account_type ='".$accountTypeRow['account_type']."' and account_class ='".$accountClassRow['account_class']."' and workspace_id='".$wid."' group by account_class,account_class,financial_statement order by financial_statement");
                $financialStatementCounter = 'a';
                $pdf->SetFont('Arial','',10);
                while($financialStatementRow = $financialStatementResult->fetch_assoc()){
                    if ($pdf->GetY() > 220) {
                        $pdf->Cell(array_sum($colWidth),0,'','T');
                        $pdf->Ln(0);
                        $pdf->AddPage();
                    }
                    $cyFinalBalTotal += $financialStatementRow['cy_final_bal'];
                    $cyBegBalTotal += $financialStatementRow['cy_beg_bal'];
                    
                    $pdf->Cell($colWidth[0],6,' ', "RL",0,'C');
                    $pdf->Cell($colWidth[1],6,'('.$financialStatementCounter++.') '.$financialStatementRow['financial_statement'],"R",0,'L');
                    $pdf->Cell($colWidth[2],6, numberToCurrency($financialStatementRow['cy_final_bal']),"R",0,'R');
                    $pdf->Cell($colWidth[3],6, numberToCurrency($financialStatementRow['cy_beg_bal']),"R",0,'R');
                    $pdf->Ln(6);
                }
            }
            $pdf->Cell(array_sum($colWidth),0,'','T');
            $pdf->Ln(0);
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell($colWidth[0],6,' ', "RL",0,'C');
            $pdf->Cell($colWidth[1],6,'Total',"R",0,'C');
            $pdf->Cell($colWidth[2],6, numberToCurrency($cyFinalBalTotal),"R",0,'R');
            $pdf->Cell($colWidth[3],6, numberToCurrency($cyBegBalTotal),"R",0,'R');
            $pdf->Ln(6);
            
            $pdf->Cell(array_sum($colWidth),0,'','T');
            $pdf->Ln(0);
        }
        $type = 'PL';
        $pdf->AddPage();
        
        $accountTypeResult = $con->query("SELECT DISTINCT account_type, accountTypeSeqNumber from trial_balance where workspace_id='$wid' and ( account_type like '%Expense%' or account_type like '%Revenue%' ) order by accountTypeSeqNumber");
        $typeCounter = 'A';

        while($accountTypeRow = $accountTypeResult->fetch_assoc()){
            $cyBegBalTotal = $cyFinalBalTotal = 0;
        
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell($colWidth[0],6,'('.$typeCounter++.')', "RL",0,'C');
            $pdf->Cell($colWidth[1],6,strtoupper($accountTypeRow['account_type']),'R',0,'L');
            $pdf->Cell($colWidth[2],6,' ','R',0,'R');
            $pdf->Cell($colWidth[3],6,' ','R',0,'R');
            $pdf->Ln(6);

            $accountClassResult = $con->query("SELECT account_class from trial_balance where account_type ='".$accountTypeRow['account_type']."' and workspace_id='".$wid."' group by account_class");
            $accountClassCounter = 1;
            while($accountClassRow = $accountClassResult->fetch_assoc()){
                $pdf->SetFont('Arial','B',10);
                
                $wordsArray = explode(' ',$accountClassRow['account_class']);
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


                $financialStatementResult = $con->query("SELECT max(financial_statement) financial_statement, sum(cy_beg_bal) cy_beg_bal, sum(cy_final_bal) cy_final_bal from trial_balance where account_type ='".$accountTypeRow['account_type']."' and account_class ='".$accountClassRow['account_class']."' and workspace_id='".$wid."' group by account_class,account_class,financial_statement order by financial_statement");
                $financialStatementCounter = 'a';
                $pdf->SetFont('Arial','',10);
                while($financialStatementRow = $financialStatementResult->fetch_assoc()){
                    if ($pdf->GetY() > 220) {
                        $pdf->Cell(array_sum($colWidth),0,'','T');
                        $pdf->Ln(0);
                        $pdf->AddPage();
                    }
                    $cyFinalBalTotal += $financialStatementRow['cy_final_bal'];
                    $cyBegBalTotal += $financialStatementRow['cy_beg_bal'];
                    
                    $pdf->Cell($colWidth[0],6,' ', "RL",0,'C');
                    $pdf->Cell($colWidth[1],6,'('.$financialStatementCounter++.') '.$financialStatementRow['financial_statement'],"R",0,'L');
                    $pdf->Cell($colWidth[2],6, numberToCurrency($financialStatementRow['cy_final_bal']),"R",0,'R');
                    $pdf->Cell($colWidth[3],6, numberToCurrency($financialStatementRow['cy_beg_bal']),"R",0,'R');
                    $pdf->Ln(6);
                }
            }
            $pdf->Cell(array_sum($colWidth),0,'','T');
            $pdf->Ln(0);
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell($colWidth[0],6,' ', "RL",0,'C');
            $pdf->Cell($colWidth[1],6,'Total',"R",0,'C');
            $pdf->Cell($colWidth[2],6, numberToCurrency($cyFinalBalTotal),"R",0,'R');
            $pdf->Cell($colWidth[3],6, numberToCurrency($cyBegBalTotal),"R",0,'R');
            $pdf->Ln(6);
            
            $pdf->Cell(array_sum($colWidth),0,'','T');
            $pdf->Ln(0);
        }

        $pdf->Ln(0);
    }
    else{

        $arrayOfAssets = $con->query("SELECT distinct account_class from trial_balance where workspace_id = $wid and account_type like '%Asset%'");
        $i = 0;
        $finalArrayOfAssets = []; 
        while($arrayOfAssetsRow = $arrayOfAssets->fetch_assoc()){
            $arrayOfAssetsResult = $con->query("SELECT max(financial_statement) financial_statement, sum(cy_final_bal) total FROM trial_balance where account_class = '".$arrayOfAssetsRow['account_class']."' GROUP BY financial_statment");
            while($row = $arrayOfAssetsResult->fetch_assoc()){
                $finalArrayOfAssets[$i][0] = $row['financial_statement'];
                $finalArrayOfAssets[$i++][1] = $row['total'];
            }
        }
        
        $arrayOfLiabilities = $con->query("SELECT distinct account_class from trial_balance where workspace_id = $wid and account_type like '%Liabilities%'");
        $i = 0;
        $finalArrayOfLiabilities = []; 
        while($arrayOfLiabilitiesRow = $arrayOfLiabilities->fetch_assoc()){
            $arrayOfLiabilitiesResult = $con->query("SELECT max(financial_statement) financial_statement, sum(cy_final_bal) total FROM trial_balance where account_class = '".$arrayOfLiabilitiesRow['account_class']."' GROUP BY financial_statment");
            while($row = $arrayOfLiabilitiesResult->fetch_assoc()){
                $finalArrayOfLiabilities[$i][0] = $row['financial_statement'];
                $finalArrayOfLiabilities[$i++][1] = $row['total'];
            }
        }

        $arrayOfOthers = $con->query("SELECT distinct account_class from trial_balance where workspace_id = $wid and ( account_type not like '%Asset%' and account_type not like '%Liabilities%' and account_type not like '%Expenses%' and account_type not like '%Sales%' and account_type not like '%Income%')");
        while($arrayOfOthersRow = $arrayOfOthers->fetch_assoc()){
            $arrayOfOthersResult = $con->query("SELECT max(financial_statement) financial_statement, sum(cy_final_bal) total FROM trial_balance where account_class = '".$arrayOfSalesRow['account_class']."' GROUP BY financial_statment");
            while($row = $arrayOfOthersResult->fetch_assoc()){
                $finalArrayOfLiabilities[$i][0] = $row['financial_statement'];
                $finalArrayOfLiabilities[$i++][1] = $row['total'];
            }
        }

        $maxIteration = count($arrayOfAssests);
        $totalLiabilities = $totalAssets = 0;
        for($i=0;$i<$maxIteration;$i++) {
            if(isset($finalArrayOfLiabilities[$i])) {
                $liabilityName = $finalArrayOfLiabilities[$i][0];
                $amount = $finalArrayOfLiabilities[$i][1];
                $totalLiabilities += $amount;
            }
            else {
                $liabilityName = '';
                $amount = '';
            }
            $totalAssets += $finalArrayOfAssets[$i][1];
            $pdf->Cell($colWidth[0],14,$liabilityName,'LR',0,'L');
            $pdf->Cell($colWidth[1],14,$amount,'R',0,'R');//libility amount field
            $pdf->Cell($colWidth[2],14,$finalArrayOfAssets[$i][0],'R',0, 'L');
            // $pdf->Cell($colWidth[2],6,$row[2],0,0,'C');
            $pdf->Cell($colWidth[3],14,$finalArrayOfAssets[$i][1],'R',0,'R');
            $pdf->Ln(7);
        }
        
        //closing line
        $pdf->Ln(2);
        $pdf->SetFont('Arial','B',10);
        // $pdf->Line($pdf->GetX(), $pdf->GetY(), 250,71);
        $pdf->Cell(array_sum($colWidth),0,'','T');
        $pdf->Ln(0);
        $pdf->Cell($colWidth[0],6, 'TOTAL', "RLB",0,'L');
        $pdf->Cell($colWidth[1],6,$totalLiabilities,"RB",0,'R');
        $pdf->Cell($colWidth[2],6,'TOTAL',"RB",0,'R');
        $pdf->Cell($colWidth[3],6,$totalAssets,"RB",0,'R');
        
        
        $pdf->Output();
        
        $arrayOfSales = $con->query("SELECT distinct account_class from trial_balance where workspace_id = $wid and account_type like '%Sales%'");
        $i = 0;
        $finalArrayOfSales = []; 
        while($arrayOfSalesRow = $arrayOfSales->fetch_assoc()){
            $arrayOfSalesResult = $con->query("SELECT max(financial_statement) financial_statement, sum(cy_final_bal) total FROM trial_balance where account_class = '".$arrayOfSalesRow['account_class']."' GROUP BY financial_statment");
            while($row = $arrayOfSalesResult->fetch_assoc()){
                $finalArrayOfSales[$i][0] = $row['financial_statement'];
                $finalArrayOfSales[$i++][1] = $row['total'];
            }
        }


        $arrayOfPurchase = $con->query("SELECT distinct account_class from trial_balance where workspace_id = $wid and account_type like '%Purchase%'");
        $i = 0;
        $finalArrayOfPurchase = []; 
        while($arrayOfPurchaseRow = $arrayOfPurchase->fetch_assoc()){
            $arrayOfPurchaseResult = $con->query("SELECT max(financial_statement) financial_statement, sum(cy_final_bal) total FROM trial_balance where account_class = '".$arrayOfSalesRow['account_class']."' GROUP BY financial_statment");
            while($row = $arrayOfPurchaseResult->fetch_assoc()){
                $finalArrayOfPurchase[$i][0] = $row['financial_statement'];
                $finalArrayOfPurchase[$i++][1] = $row['total'];
            }
        }

        $maxIteration = count($finalArrayOfPurchase) > count($finalArrayOfSales) ? count($finalArrayOfPurchase) : count($finalArrayOfSales);

        $totalAmountForPurchase = $totalAmountForSales = 0;

        for($i=0;$i<$maxIteration;$i++) {
            
            if(isset($finalArrayOfSales[$i])) {
                $name = $finalArrayOfSales[$i][0];
                $amount = $finalArrayOfSales[$i][1];
                $totalAmountForSales += $amount; //amount field
            }
            else {
                $name = '';
                $amount = '';
            }

            $totalAmountForPurchase += $finalArrayOfPurchase[$i][1];
            if ($pdf->GetY() > 220) {
                $pdf->Cell(array_sum($colWidth),0,'','T');
                $pdf->Ln(0);
                $pdf->AddPage();
            }

            $pdf->Cell($colWidth[0],14,$finalArrayOfPurchase[$i][0],'LR',0, 'L');
            $pdf->Cell($colWidth[1],14,$finalArrayOfPurchase[$i][1],'R',0,'R');//libility amount field
            $pdf->Cell($colWidth[2],14,$name,'LR',0,'L');
            // $pdf->Cell($colWidth[2],6,$row[2],0,0,'C');
            $pdf->Cell($colWidth[3],14,$amount,'R',0,'R');
            $pdf->Ln(7);
        }

        //top table body ends

        //gross profit
        $grossTotal = $totalAmountForSales - $totalAmountForPurchase;
        $pdf->Ln(2);
        // $pdf->Cell($colWidth[0]);
        $pdf->Cell($colWidth[0],6, 'Gross profit', "RLBT",0,'R');
        $pdf->Cell($colWidth[1],6,$grossTotal,"RBT",0,'R');

        //closing line
        $pdf->Ln(6);
        $pdf->SetFont('Arial','B',10);
        // $pdf->Line($pdf->GetX(), $pdf->GetY(), 250,71);
        $pdf->Cell(array_sum($colWidth),0,'','T');
        $pdf->Ln(0);
        $pdf->Cell($colWidth[0],6, 'TOTAL', "RLB",0,'L');
        $pdf->Cell($colWidth[1],6,$totalAmountForPurchase,"RB",0,'R');
        $pdf->Cell($colWidth[2],6,'TOTAL',"RB",0,'R');
        $pdf->Cell($colWidth[3],6,$totalAmountForSales,"RB",0,'R');

        $pdf->Ln(1);

        $arrayOfExpenses = $con->query("SELECT distinct account_class from trial_balance where workspace_id = $wid and account_type like '%Expenses%'");
        $i = 0;
        $finalArrayOfExpenses = []; 
        while($arrayOfExpensesRow = $arrayOfExpenses->fetch_assoc()){
            $arrayOfExpensesResult = $con->query("SELECT max(financial_statement) financial_statement, sum(cy_final_bal) total FROM trial_balance where account_class = '".$arrayOfExpensesRow['account_class']."' GROUP BY financial_statment");
            while($row = $arrayOfExpensesResult->fetch_assoc()){
                $finalArrayOfExpenses[$i][0] = $row['financial_statement'];
                $finalArrayOfExpenses[$i++][1] = $row['total'];
            }
        }

        $arrayOfIncome = $con->query("SELECT distinct account_class from trial_balance where workspace_id = $wid and account_type like '%Income%'");
        $i = 0;
        $finalArrayOfIncome = []; 
        while($arrayOfIncomeRow = $arrayOfIncome->fetch_assoc()){
            $arrayOfIncomeResult = $con->query("SELECT max(financial_statement) financial_statement, sum(cy_final_bal) total FROM trial_balance where account_class = '".$arrayOfIncomeRow['account_class']."' GROUP BY financial_statment");
            while($row = $arrayOfIncomeResult->fetch_assoc()){
                $finalArrayOfIncome[$i][0] = $row['financial_statement'];
                $finalArrayOfIncome[$i++][1] = $row['total'];
            }
        }

        $maxIteration = count($finalArrayOfExpenses);
        
        $totalAmountForExpense = $totalAmountForIncome = 1;
        for($i=0;$i<$maxIteration;$i++) {
            if(isset($finalArrayOfIncome[$i])) {
                $name = $finalArrayOfIncome[$i][0];
                $amount = $finalArrayOfIncome[$i][1];
                $totalAmountForIncome += $amount; //amount field
            }
            else {
                $name = '';
                $amount = '';
            }
            $totalAmountForExpense += $finalArrayOfExpenses[$i][1];

            if ($pdf->GetY() > 220) {
                $pdf->Cell(array_sum($colWidth),0,'','T');
                $pdf->Ln(0);
                $pdf->AddPage();
            }

            $pdf->Cell($colWidth[0],14,$finalArrayOfExpenses[$i][0],'LR',0, 'L');
            $pdf->Cell($colWidth[1],14,$finalArrayOfExpenses[$i][1],'R',0,'R');//libility amount field
            if($i == 0) {
                // $pdf->Cell($colWidth[2],6,$row[2],0,0,'C');
                $pdf->Cell($colWidth[2],14,'By Gross Profit','LR',0,'L');
                $pdf->Cell($colWidth[3],14,$grossTotal,'R',0,'R');
            } else {
                // $pdf->Cell($colWidth[2],6,$row[2],0,0,'C');
                $pdf->Cell($colWidth[2],14,$name,'LR',0,'L');
                $pdf->Cell($colWidth[3],14,$amount,'R',0,'R');
            }
            $pdf->Ln(7);
        }

        //net profit
        $pdf->Ln(2);
        // $pdf->Cell($colWidth[0]);
        $pdf->Cell($colWidth[0],6, 'To Net profit', "RLBT",0,'L');
        $pdf->Cell($colWidth[1],6,$totalAmountForExpense,"RBT",0,'R');

        $pdf->Ln(6);
        $pdf->SetFont('Arial','B',10);
        // $pdf->Line($pdf->GetX(), $pdf->GetY(), 250,71);
        $pdf->Cell(array_sum($colWidth),0,'','T');
        $pdf->Ln(0);
        $pdf->Cell($colWidth[0],6, 'TOTAL', "RLB",0,'L');
        $pdf->Cell($colWidth[1],6,$totalAmountForExpense,"RB",0,'R');
        $pdf->Cell($colWidth[2],6,'TOTAL',"RB",0,'R');
        $pdf->Cell($colWidth[3],6,$totalAmountForIncome,"RB",0,'R');
        $pdf->Output();
    }
    $pdf->Output();
    $pdf->AliasNbPages();
?>