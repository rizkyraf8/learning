<?php 
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    public function Header() {
        // $image_file = K_PATH_IMAGES.'logo_example.jpg';
        // $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->SetFont('helvetica', 'B', 18);
        $this->SetY(13);
        $this->Cell(0, 15, '', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('YAPRI');
$pdf->SetTitle('Bank Soal');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 50, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 10);

// add a page
$pdf->AddPage();

// create some HTML content
$html = "
<style>
    table, th, td {
        border: 1px solid black;
        padding:5px;
    }

    table th {
        background-color: #c1c1c1;
        font-weight: bold;
    }

</style>
<h2>Data Pelajaran</h2>
<table id=\"data-peserta\">
    <tr>
        <th>Nama Pelajaran</th>
        <td>{$matpel->nama_matpel}</td>
    </tr>
    <tr>
        <th>Guru</th>
        <td>{$matpel->nama_guru}</td>
    </tr>
</table>
<h2>Soal</h2>
<table>
    <tr>
        <th width=\"8%\" align=\"center\">No</th>
        <th colspan=\"5\" width=\"77%\" align=\"center\">Soal</th>
        <th width=\"15%\" align=\"center\">Jawaban</th>
    </tr>
";

$i = 1;
foreach ($soal as $key => $value) {
    $html.="
    <tr>
        <td rowspan=\"2\" align=\"center\">$i</td>
        <td colspan=\"5\">".$value->soal."</td>
        <td rowspan=\"2\" align=\"center\" style=\"vertical-align:center\">".$value->jawaban."</td>
    </tr>
    <tr>
        <td>A.".$value->opsi_a."</td>
        <td>B.".$value->opsi_b."</td>
        <td>C.".$value->opsi_c."</td>
        <td>D.".$value->opsi_d."</td>
        <td>E.".$value->opsi_e."</td>
    </tr>
    ";

    $i++;
}

$html.="</table>";

// echo "$html";
// die();

// output the HTML content
$pdf->writeHTML($html, true, 0, true, 0);
// reset pointer to the last page
$pdf->lastPage();
// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('tes.pdf', 'I');
