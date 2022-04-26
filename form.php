<?php
    // var_dump($_FILES);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // var_dump($_FILES);
    $errors = [];
    // chemin vers un dossier sur le serveur qui va recevoir les fichiers transférés (attention ce dossier doit être accessible en écriture)
    $uploadDir = 'uploads/';
    // le nom de fichier sur le serveur est celui du nom d'origine du fichier sur le poste du client (mais d'autre stratégies de nommage sont possibles)
    $uploadFile = $uploadDir . uniqid('plop', true) . basename($_FILES['avatar']['name']);
    // Je récupère l'extension du fichier
    $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    // Les extensions autorisées
    $authorizedExtensions = ['jpg','gif','png','webp'];
    // Le poids max géré par PHP par défaut est de 2M
    $maxFileSize = 2000000;

    // Je sécurise et effectue mes tests
    /****** Si l'extension est autorisée *************/
    if ((!in_array($extension, $authorizedExtensions))) {
        $errors[] = 'Veuillez sélectionner une image de type Jpg ou Gif ou Png ou Webp !';
    }
    /****** On vérifie si l'image existe et si le poids est autorisé en octets *************/
    // file_exists($_FILES['avatar']['tmp_name']) &&
    if (filesize($_FILES['avatar']['tmp_name']) >= $maxFileSize) {
        $errors[] = "Votre fichier doit faire moins de 1Mo !";
    }
    /****** Si je n'ai pas d"erreur alors j'upload *************/
    /** TON SCRIPT D'UPLOAD*/
    // on déplace le fichier temporaire vers le nouvel emplacement sur le serveur. Ça y est, le fichier est uploadé
    if (empty($errors)) {
        move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile);
    }

    // function displayImage($file)
    // {
    //     if (file_exists($file)) {
    //         echo $file;
    //     }
    // }


    function delete($file)
    {
        if (file_exists($file)) {
            unlink($file);
        }
    }
    if (array_key_exists('delete', $_POST)) {
        delete($pathToFile);
    }
}


?>

<form method="post" enctype="multipart/form-data">
  <label for="lastname">Last Name</label>
  <input type="text" name="lastname" id="lastname" placeholder="Last name" value="<?php if (isset($_POST['lastname'])) {
    echo $_POST['lastname'];
} ?>"><br/>

  <label for="firstname">First Name</label>
  <input type="text" name="firstname" id="firstname" placeholder="First name" value="<?php if (isset($_POST['firstname'])) {
    echo $_POST['firstname'];
} ?>"><br/>

  <label for="age">Age</label>
  <input type="text" name="age" id="age" placeholder="Age" value="<?php if (isset($_POST['age'])) {
    echo $_POST['age'];
} ?>"><br/>

  <label for="imageUpload">Upload an profile image</label>
  <br/>
  <input type="file" name="avatar" id="imageUpload" />
  <br/>
  <p><?php if (isset($errors)) {
    foreach ($errors as $error) {
        if (!empty($error)) {
            echo $error;
        } else {
            echo '';
        }
    };
} ?></p>
  <br/>
  <button name="send">Send</button>
  <br/>
</form>

<form method="post" enctype="multipart/form-data">
  <input type="submit" name="delete" id="delete" value="Delete Profile Picture" />
  <br/>
  <img src="<?php if (empty($errors)) {
    if (isset($_FILES['avatar']['tmp_name']))
      echo $uploadFile;
  } ?>">

</form>
