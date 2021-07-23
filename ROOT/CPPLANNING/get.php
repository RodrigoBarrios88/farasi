<?php
$BUCKET_NAME = 'imagesexamplebucket';
$IAM_KEY = 'AKIA4QLXZQSRLRJIBK4V';
$IAM_SECRET = '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';

require '../vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

// Get the access code
$accessCode = $_GET['c'];
$accessCode = strtoupper($accessCode);
$accessCode = trim($accessCode);
$accessCode = addslashes($accessCode);
$accessCode = htmlspecialchars($accessCode);

// Connect to database
$codigo = $_REQUEST["programacion"];
$posicion = $_REQUEST["posicion"];
$result = $ClsEje->get_fotos_ejecucion('', $codigo, $posicion;
if (is_array($result)) {
    foreach ($result as $row) {
        $strFoto = trim($row["fot_foto"]);
        $keyPath = 'test_example/' . $strFoto;
    }
} else {
    die("Error: No se encuentra la ruta de la imagen");
}

// Get path from db
while ($row = mysqli_fetch_array($result)) {
    $keyPath = $row['s3FilePath'];
}

// Get file
try {
    $s3 = S3Client::factory(
        array(
            'credentials' => array(
                'key' => $IAM_KEY,
                'secret' => $IAM_SECRET
            ),
            'version' => 'latest',
            'region'  => 'us-east-2'
        )
    );

    //
    $result = $s3->getObject(array(
        'Bucket' => $BUCKET_NAME,
        'Key'    => $keyPath
    ));


    // Display it in the browser
    header("Content-Type: {$result['ContentType']}");
    header('Content-Disposition: filename="' . basename($keyPath) . '"');
    echo $result['Body'];
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
