<?php
require('FPDF-1.8.6/fpdf.php');

$con = mysqli_connect('localhost', 'root', '');
mysqli_select_db($con, 'pcstore1');

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
                    // Tailwind-inspired styling
                    $this->SetFont('Arial', 'B', 15);
                    $this->SetTextColor(31, 41, 55); // Gray-800
                    $this->Cell(0, 10, 'MyTechPC - Official Receipt', 0, 1, 'C');
                    
                    // Logo
                    $this->Image('img/lg.png', 10, 10, 30);
                    
                    $this->Ln(15);
                }

                function Footer() {
                    // Tailwind-inspired footer
                    $this->SetY(-15);
                    $this->SetFont('Arial', 'I', 8);
                    $this->SetTextColor(107, 114, 128); // Gray-500
                    $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
                }

                function SectionHeader($text) {
                    $this->SetFont('Arial', 'B', 12);
                    $this->SetTextColor(17, 24, 39); // Gray-900
                    $this->Cell(0, 10, $text, 0, 1, 'L');
                    $this->SetDrawColor(209, 213, 219); // Gray-300
                    $this->Line($this->GetX(), $this->GetY(), $this->GetX() + 190, $this->GetY());
                    $this->Ln(5);
                }
            }
            
            $pdf = new PDF('P', 'mm', 'A4');
            $pdf->AliasNbPages();
            $pdf->AddPage();
            
            // Company Information
            $pdf->SetFont('Arial', '', 10);
            $pdf->SetTextColor(55, 65, 81); // Gray-700
            $pdf->Cell(0, 6, 'MyTechPC', 0, 1);
            $pdf->Cell(0, 6, 'Metro Manila, Philippines', 0, 1);
            $pdf->Cell(0, 6, 'Contact: 09355498379', 0, 1);
            $pdf->Cell(0, 6, 'Email: mytechpc@gmail.com', 0, 1);
            $pdf->Ln(10);

            // Order Details Section
            $pdf->SectionHeader('Order Information');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(60, 7, 'Order ID:', 0, 0);
            $pdf->Cell(0, 7, $oid['oid'], 0, 1);
            $pdf->Cell(60, 7, 'Order Date:', 0, 0);
            $pdf->Cell(0, 7, $oid['dateod'], 0, 1);
            $pdf->Cell(60, 7, 'Delivery Status:', 0, 0);
            $pdf->Cell(0, 7, $oid['datedel'] ?? 'Pending', 0, 1);
            $pdf->Ln(5);

            // Customer Information Section
            $pdf->SectionHeader('Customer Details');
            $pdf->Cell(60, 7, 'Name:', 0, 0);
            $pdf->Cell(0, 7, $oid['afname'] . ' ' . $oid['alname'], 0, 1);
            $pdf->Cell(60, 7, 'Email:', 0, 0);
            $pdf->Cell(0, 7, $oid['email'], 0, 1);
            $pdf->Cell(60, 7, 'Phone:', 0, 0);
            $pdf->Cell(0, 7, $oid['phone'], 0, 1);
            $pdf->Cell(60, 7, 'Address:', 0, 0);
            $pdf->Cell(0, 7, $oid['address'], 0, 1);
            $pdf->Ln(5);

            // Product Details Section
            $pdf->SectionHeader('Product Details');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetFillColor(243, 244, 246); // Gray-100
            $pdf->Cell(130, 7, 'Product', 1, 0, 'C', true);
            $pdf->Cell(30, 7, 'Quantity', 1, 0, 'C', true);
            $pdf->Cell(30, 7, 'Price', 1, 1, 'C', true);

            $pdf->SetFont('Arial', '', 10);
            $productQuery = mysqli_query($con, "SELECT * FROM `order-details` INNER JOIN products ON `order-details`.pid = products.pid WHERE `order-details`.oid = '".$oid['oid']."'");
            $total = 0;

            while ($product = mysqli_fetch_array($productQuery)) {
                $subtotal = $product['price'] * $product['qty'];
                $total += $subtotal;

                $pdf->Cell(130, 7, $product['pname'], 1);
                $pdf->Cell(30, 7, $product['qty'], 1, 0, 'C');
                $pdf->Cell(30, 7, 'Php ' . number_format($subtotal, 2), 1, 1, 'R');
            }

            // Total Section
            $pdf->Ln(5);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(160, 7, 'Subtotal', 0, 0, 'R');
            $pdf->Cell(30, 7, 'Php ' . number_format($total, 2), 1, 1, 'R');

            $handlingFee = 250;
            $pdf->Cell(160, 7, 'Handling Fee', 0, 0, 'R');
            $pdf->Cell(30, 7, 'Php ' . number_format($handlingFee, 2), 1, 1, 'R');

            $grandTotal = $total + $handlingFee;
            $pdf->Cell(160, 7, 'Total', 0, 0, 'R');
            $pdf->Cell(30, 7, 'Php ' . number_format($grandTotal, 2), 1, 1, 'R');

            // Thank You Note
            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'I', 10);
            $pdf->SetTextColor(107, 114, 128); // Gray-500
            $pdf->Cell(0, 7, 'Thank you for your purchase!', 0, 1, 'C');
            
            $pdf->Output();
        } else {
            echo "No data found for Order ID: ".$_GET['oid'];
        }
    } else {
        echo "Query failed: ".mysqli_error($con);
    }
} else {
    echo "Order ID not set.";
}
?>