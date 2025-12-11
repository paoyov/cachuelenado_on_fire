<?php
// Test file upload structure
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>POST Data:</h2>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    echo "<h2>FILES Data:</h2>";
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";
    
    if (isset($_FILES['documentos'])) {
        echo "<h2>documentos structure:</h2>";
        echo "<pre>";
        print_r($_FILES['documentos']);
        echo "</pre>";
    }
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test File Upload</title>
</head>
<body>
    <h1>Test Document Upload Structure</h1>
    <form method="POST" enctype="multipart/form-data">
        <h3>DNI:</h3>
        <input type="file" name="documentos[dni][]" accept=".pdf,.jpg,.jpeg,.png">
        
        <h3>Certificados:</h3>
        <input type="file" name="documentos[certificado][]" accept=".pdf,.jpg,.jpeg,.png" multiple>
        
        <h3>Fotos:</h3>
        <input type="file" name="documentos[foto_trabajo][]" accept=".jpg,.jpeg,.png" multiple>
        
        <br><br>
        <button type="submit">Test Upload</button>
    </form>
</body>
</html>
