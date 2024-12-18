<?php
require('FPDF-1.8.6/fpdf.php');

$con = mysqli_connect('localhost', 'root', '');
mysqli_select_db($con, 'pcstore');

// Check if $_GET['aid'] is set
if (isset($_GET['oid'])) {
    $query = mysqli_query($con, "SELECT * FROM orders INNER JOIN accounts ON orders.aid = accounts.aid WHERE orders.oid = '".$_GET['oid']."'");

    // Check if the query was successful
    if ($query) {
        $oid = mysqli_fetch_array($query);

        // Check if data is retrieved
        if ($oid) {
			class PDF extends FPDF {
				function Header() {
					$this->SetFont('Arial', 'B', 15);
					$this->Cell(12);
			
					$this->Image('img/lg.png',85,10,45,35);
			
					$this->Ln(5);
				}
			}
			
			$pdf = new PDF('P', 'mm', 'A4');
			
			$pdf->AddPage();
			$pdf->SetFont('Arial', 'B', 14);
			
			$pdf->Cell(130, 55, 'MyTechPC', 0, 0);
			$pdf->Cell(59, 55,'Order Reciept',0,1);
			
			$pdf->SetFont('Arial','',12);
			
			$pdf->Cell(130, 5, '[Street Address]', 0, 0);
			$pdf->Cell(59, 5, '', 0, 1);
			
			$pdf->Cell(130, 5, 'Metro Manila, Philippines', 0, 0);
			$pdf->Cell(25, 5,'Date', 0, 0);
			$pdf->Cell(34, 5,date('d/m/Y') ,0,1);
			
			$pdf->Cell(130, 5, 'Phone: 09355498379', 0, 0);
			$pdf->Cell(25,5,'Account ID: ', 0,0);
			$pdf->Cell(34, 5,$oid['aid'],0,1);
			
			$pdf->Cell(189, 10,'',0,1);
			
			$pdf->Cell(100, 5, 'Billing to', 0, 1);
			
			$pdf->Cell(10, 5, '', 0 , 0);
			$pdf->Cell(13, 5, 'Name: ', 0, 0 );
			$pdf->Cell(13, 5, $oid['afname'], 0, 0);
			$pdf->Cell(90, 5, $oid['alname'], 0, 1);
			
			$pdf->Cell(10,5,'', 0, 0);
			$pdf->Cell(18, 5, 'Address: ', 0, 0);
			$pdf->Cell(90, 5, $oid['address'], 0, 1);

			$pdf->Cell(10,5,'', 0, 0);
			$pdf->Cell(10,5,'City: ', 0, 0);
			$pdf->Cell(10,5,$oid['city'], 0, 1);
			
			$pdf->Cell(10, 5, '', 0, 0);
			$pdf->Cell(18, 5, 'Order ID: ', 0, 0);
			$pdf->Cell(90, 5, $oid['oid'], 0, 1);
			
			$pdf->Cell(10, 5, '', 0, 0);
			$pdf->Cell(14, 5, 'Phone: ', 0, 0);
			$pdf->Cell(90, 5, $oid['phone'], 0, 1);

			$pdf->Cell(10,5,'', 0, 0);
			$pdf->Cell(12,5,'Email: ', 0, 0);
			$pdf->Cell(90,5,$oid['email'], 0, 0);
			$pdf->Cell(10,10,'', 0, 1);

			$pdf->SetFont('Arial', 'B', 12);
			$pdf->Cell(155, 5, 'Product', 1, 0);
			$pdf->Cell(34, 5,  'Amount', 1, 1);

			$pdf->SetFont('Arial', '', 12);

			$productQuery = mysqli_query($con, "SELECT * FROM `order-details` INNER JOIN products ON `order-details`.pid = products.pid WHERE `order-details`.oid = '".$oid['oid']."'");
            $amount = 0;

            while ($product = mysqli_fetch_array($productQuery)) {
                $pdf->Cell(155, 5, $product['pname'], 1, 0);
                $pdf->Cell(34, 5, 'Php ' . number_format($product['price']), 1, 1, 'R');
                $amount += $product['price'];
            }

			$pdf->Cell(130, 5, '', 0, 0);
			$pdf->Cell(25, 5, 'Subtotal', 0, 0);
			$pdf->Cell(10, 5, 'Php', 1, 0);
			$pdf->Cell(24, 5, number_format($amount), 1, 1, 'R');

			$pdf->Cell(130, 5, '', 0, 0);
			$pdf->Cell(25, 5, 'HandlingFee', 0, 0);
			$pdf->Cell(10, 5, 'Php', 1, 0);
			$pdf->Cell(24, 5, '250', 1, 1, 'R');

			$pdf->Cell(130, 5, '', 0, 0);
			$pdf->Cell(25, 5, 'Total Due', 0, 0);
			$pdf->Cell(10, 5, 'Php', 1, 0);
			$pdf->Cell(24, 5, number_format($amount + 250), 1, 1, 'R');

			
			$pdf->Output();
        } else {
            // Handle the case where no data is retrieved
            echo "No data found for Account ID: ".$_GET['oid'];
        }
    } else {
        // Handle the case where the query fails
        echo "Query failed: ".mysqli_error($con);
    }
} else {
    // Handle the case where $_GET['aid'] is not set
    echo "Account ID not set.";
}


?>
