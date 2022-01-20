<?php include_once('includes/init.php'); ?>

<?php
if (isset($_SESSION['USER_LOGGED'])) {
    include_once('includes/redirect_backward.php');
}
?>

<?php
if (
    !isset($_POST['email'])
    || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
    || !isset($_POST['pseudo'])
    || empty($_POST['pseudo'])
    || str_contains($_POST['pseudo'], '@')
    || !isset($_POST['nom'])
    || empty($_POST['nom'])
    || !isset($_POST['prenom'])
    || empty($_POST['prenom'])
    || !isset($_POST['password'])
    || empty($_POST['password'])
    || !isset($_POST['password_comfirm'])
    || empty($_POST['password_comfirm'])
) {
    $_SESSION['ERROR_MSG'] = 'Informations fournies non valides !';
    include_once('includes/error.php');
}

$email = htmlspecialchars($_POST['email']);
$pseudo = htmlspecialchars($_POST['pseudo']);
$nom = htmlspecialchars($_POST['nom']);
$prenom = htmlspecialchars($_POST['prenom']);
$password = htmlspecialchars($_POST['password']);
$password_comfirm = htmlspecialchars($_POST['password_comfirm']);

if ($password != $password_comfirm) {
    $_SESSION['ERROR_MSG'] = 'Les deux mot de passe ne sont pas identique';
    include_once('includes/error.php');
}

// On vérifie si il existe déjà un utilisateur avec ce pseudo
try {
    $sqlQuery = 'SELECT pseudo FROM Utilisateurs WHERE pseudo = :pseudo';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'pseudo' => $pseudo
    ]);
    $pseudos = $sqlStatement->fetchAll();
    if (count($pseudos) > 0) {
        $_SESSION['ERROR_MSG'] = "Le pseudo \"" . $pseudo . "\" existe déjà";
        include_once('includes/error.php');
    }
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include_once('includes/error.php');
}

// On vérifie si il existe déjà un utilisateur avec cet email
try {
    $sqlQuery = 'SELECT email FROM Utilisateurs WHERE email = :email';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'email' => $email
    ]);
    $emails = $sqlStatement->fetchAll();
    if (count($emails) > 0) {
        $_SESSION['ERROR_MSG'] = "L'email \"" . $email . "\" existe déjà";
        include_once('includes/error.php');
    }
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include_once('includes/error.php');
}

// Tout est OK on ajoute l'utilisateur
try {
    $sqlQuery = 'INSERT INTO Utilisateurs (pseudo, email, hashPassword, prenom, nom) VALUES (:pseudo, :email, :hashPassword, :prenom, :nom)';
    $sqlStatement = $mysqlClient->prepare($sqlQuery);
    $sqlStatement->execute([
        'pseudo' => $pseudo,
        'email' => $email,
        'nom' => $nom,
        'prenom' => $prenom,
        'hashPassword' => password_hash($password, PASSWORD_BCRYPT)
    ]);
} catch (Exception $e) {
    $_SESSION['ERROR_MSG'] = 'Erreur lors de l\'éxécution de la requête SQL:</br>' . $e->getMessage();
    include_once('includes/error.php');
}

// On login l'utilisateur
$_POST['identifiant'] = $email;
$_POST['password'] = $password;
include_once('submit_login.php');
?>