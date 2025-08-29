<?php
$message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name       = $_POST['name'];
    $fathername = $_POST['fathername'];
    $gender     = $_POST['gender'];
    $dob        = $_POST['dob'];
    $contact    = $_POST['contact'];
    $address    = $_POST['address'];
    $dist_mandal = $_POST['dist_mandal']; 
    $occupation = $_POST['occupation'];
    $membership = $_POST['membership'];

    // Handle file upload (photo)
    $photoName = "";
    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true); // Create directory if not exists
        }
        // unique name for photo
        $photoName = uniqid() . "_" . basename($_FILES["photo"]["name"]);
        $target_file = $target_dir . $photoName;
        move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);
    }
    // Save to MySQL database
    $conn = new mysqli('localhost','root','', 'aiwf2');
    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    } else {
        $stmt = $conn->prepare("INSERT INTO registration 
    (name, fathername, gender, dob, contact, address, dist_mandal, occupation, membership, photo) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", 
    $name, $fathername, $gender, $dob, $contact, $address, 
    $dist_mandal, $occupation, $membership, $photoName
    );

    if($stmt->execute()){
            $message = " విజయవంతంగా నమోదు అయ్యారు ";
        }
      if($stmt->execute()){
            $message = " విజయవంతంగా నమోదు అయ్యారు ";
            $userData = [
                "పేరు" => $name,
                "తండ్రి పేరు" => $fathername,
                "లింగం" => $gender,
                "పుట్టిన తేదీ" => $dob,
                "సంప్రదింపు" => $contact,
                "చిరునామా" => $address,
                "జిల్లా / మండలం" => $dist_mandal,
                "వృత్తి" => $occupation,
                "సభ్యత్వం" => $membership,
                "ఫోటో" => $photoName
            ];
        }
         else {
            $message = "పొరపాటు జరిగింది: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
    }
}
?>
<?php if(!empty($message)): ?>
    <div style="padding:15px; margin:15px; background:#e8f5e9; border:1px solid #2e7d32; color:#2e7d32; display:flex; align-items:center; gap:10px;">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fb/Yes_check.svg/1024px-Yes_check.svg.png" 
             alt="Success" width="30" height="30" />
        <span><?php echo $message; ?></span>
    </div>
      <?php if (!empty($userData)): ?>
        <div style="margin:20px; padding:20px; border:1px solid #ccc; background:#fafafa;">
            <h3>మీ వివరాలు:</h3>
            <ul style="list-style:none; padding:0;">
                <?php foreach($userData as $label => $value): ?>
                    <li style="margin:8px 0;">
                        <strong><?php echo $label; ?>:</strong> 
                        <?php if($label == "ఫోటో" && !empty($value)): ?>
                            <br><img src="uploads/<?php echo $value; ?>" alt="User Photo" width="100">
                        <?php else: ?>
                            <?php echo htmlspecialchars($value); ?>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
require('fpdf/fpdf.php');

class PDF extends FPDF {
    function Header() {
        // Title
        $this->SetFont('Arial','B',14);
        $this->Cell(0,10,'Membership Registration Receipt',0,1,'C');
        $this->Ln(5);
    }
}

if (!empty($userData)) {
    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','',12);

    // Show user details
    foreach ($userData as $label => $value) {
        if ($label == "ఫోటో" && !empty($value)) {
            $pdf->Cell(40,10,iconv('UTF-8','ISO-8859-1',$label).": ");
            $pdf->Ln(10);
            $pdf->Image("uploads/".$value, 80, $pdf->GetY(), 40, 40);
            $pdf->Ln(45);
        } else {
            $pdf->MultiCell(0,10, iconv('UTF-8','ISO-8859-1',$label).": ".$value);
        }
    }

    // Save PDF file
    $pdfFileName = "receipts/receipt_".time().".pdf";
    if (!file_exists("receipts")) {
        mkdir("receipts",0777,true);
    }
    $pdf->Output('F', $pdfFileName);

    echo "<div style='margin:20px;'>
            <a href='".$pdfFileName."' target='_blank' style='padding:10px 20px; background:green; color:white; text-decoration:none; border-radius:5px;'>Download Receipt (PDF)</a>
          </div>";
}
?>

    <?php endif; ?>
<?php endif; ?>
