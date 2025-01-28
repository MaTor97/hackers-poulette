<?php
// Connexion à la base de données et traitement du formulaire
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "hackers_poulette";

// Initialiser une variable pour afficher un message de succès ou d'erreur
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Connexion à la base de données
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupérer les valeurs du formulaire
        $name = $_POST['name'];
        $firstname = $_POST['firstname'];
        $email = $_POST['email'];
        $file = $_FILES['file']['name']; // Nom du fichier téléchargé
        $description = $_POST['description'];

        // Validation reCAPTCHA
        $recaptchaSecret = 'YOUR_SECRET_KEY';
        $recaptchaResponse = $_POST['g-recaptcha-response'];
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse");
        $responseKeys = json_decode($response, true);

        if (intval($responseKeys["success"]) !== 1) {
            $message = 'Please complete the reCAPTCHA';
        } else {
            // Déplacer le fichier téléchargé vers le dossier 'uploads'
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["file"]["name"]);
            move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

            // Requête préparée pour insérer les données dans la base de données
            $sql = "INSERT INTO Users (name, firstname, email_address, file, description) 
                    VALUES (:name, :firstname, :email, :file, :description)";
            $stmt = $conn->prepare($sql);

            // Liaison des paramètres
            $stmt->bindParam(':name', $name);
            // ...existing code...
        }
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Formulaire</title>
</head>
<body>
    <header>
        <h1>hackers-poulette</h1>
    </header>
    <main>
        <form action="index.php" method="POST" enctype="multipart/form-data">
            <input 
                type="text" 
                name="name" 
                id="name" 
                placeholder="Name..." 
                pattern="^[A-Za-zÀ-ÖØ-öø-ÿ\s'-]+$"
                required
            >
            <p id="name-error" class="error-message"></p> <!-- Message d'erreur pour le nom -->
                <br>

            <input 
                type="text" 
                name="firstname" 
                id="firstname" 
                placeholder="FirstName..." 
                pattern="^[A-Za-zÀ-ÖØ-öø-ÿ\s'-]+$"
                required
            >
            <p id="firstname-error" class="error-message"></p> <!-- Message d'erreur pour le prénom -->
                <br>

            <input 
                type="email" 
                name="email" 
                id="email" 
                placeholder="E-mail..." 
                pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                required
            >
            <p id="email-error" class="error-message"></p> <!-- Message d'erreur pour l'email -->
                <br>

            <input 
                type="file" 
                name="file" 
                id="file" 
                accept=".gif, .jpg, .jpeg, .png" 
            >
            <p id="file-error" class="error-message"></p> <!-- Message d'erreur pour le fichier -->
                <br>

            <input 
                type="text" 
                name="description" 
                id="description" 
                placeholder="Description..." 
                pattern="[A-Za-z0-9]{5,}"
                required
            >
            <p id="description-error" class="error-message"></p> <!-- Message d'erreur pour la description -->
                <br>

            <div class="g-recaptcha" data-sitekey="6LfR2sUqAAAAAFHTWDpXqeEYnS-A5sxv6rWeygDt"></div>
                <br>
            <button type="submit">Submit</button>
        </form>
        <!-- Afficher un message après la soumission du formulaire -->
        <?php if ($message): ?>
            <p><?= $message ?></p>
        <?php endif; ?>
    </main>
</body>
    <script src="validation.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</html>
