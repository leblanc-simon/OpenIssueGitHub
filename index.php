<?php
session_start();

require_once dirname(__FILE__).'/config.php';
require_once dirname(__FILE__).'/functions.php';

$url = false;
$datas = initDatas();

if (isset($_POST['submit']) === true) {
  list($datas, $error) = getDatas();
  if (
    $error === false 
    && isset($_POST['token']) === true 
    && is_string($_POST['token']) === true 
    && $_POST['token'] === $_SESSION['token']
    && array_key_exists($datas['project'], $projects) === true
  ) {
  
    $input = array(
      'title'   => $datas['title'],
      'body'    => $datas['body']."\n\nSoumi par ".$datas['name'],
    );
    $repo = $datas['project'];
    $user = $projects[$datas['project']];
    
    $url = submit_issue($input, $repo, $user);
    
    if ($url !== false) {
      $datas = initDatas();
    }
  }
}

$token = sha1(uniqid(SALT, true));
$_SESSION['token'] = $token;

?><!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <title>Signaler une erreur</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <link rel="stylesheet" type="text/css" href="style.css" />
  </head>
  <body>
    <!--
    Copyright (c) 2011, Leblanc Simon <contact@leblanc-simon.eu>
    All rights reserved.
    
    Redistribution and use in source and binary forms, with or without modification, are permitted provided that
    the following conditions are met:
    
        * Redistributions of source code must retain the above copyright notice, this list of conditions
          and the following disclaimer.
        * Redistributions in binary form must reproduce the above copyright notice, this list of conditions
          and the following disclaimer in the documentation and/or other materials provided with the distribution.
        * The names of its contributors may be used to endorse or promote products derived from this software
          without specific prior written permission.
    
    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
    INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
    DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
    SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
    SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
    WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE
    USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
    -->
    <?php if ($url !== false) { ?>
    <header class="center success">
      <h1>Votre erreur a bien été signalé, merci beaucoup !</h1>
      <p>Vous pouvez suivre l'évolution du traitement de votre demande à cette adresse : <a href="<?php echo $url; ?>"><?php echo $url; ?></a></p>
    </header>
    <?php } else { ?>
    <header class="center">
      <h1>Signaler une erreur</h1>
    </header>
    
    <form action="index.php" method="post" id="submit_issue" class="center">
      <input type="hidden" name="token" value="<?php echo $token ?>" />
      <div class="item">
        <label for="project">Projet</label>
        <select id="project" name="project" autofocus>
          <option value="">Sélectionner le projet concerné</option>
          <?php ksort($projects); foreach ($projects as $project => $repo) { ?>
          <option value="<?php echo $project ?>"><?php echo $project ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="item">
        <label for="name">Nom</label>
        <input type="text" value="<?php echo str_replace('"', '\"', $datas['name']) ?>" name="name" id="name" placeholder="Indiquer votre nom" />
      </div>
      <div class="item">
        <label for="email">Adresse e-mail</label>
        <input type="text" value="<?php echo $datas['email'] ?>" name="email" id="email" placeholder="Indiquer votre adresse e-mail" />
      </div>
      <div class="item">
        <label for="title">Titre</label>
        <input type="text" value="<?php echo $datas['title'] ?>" name="title" id="title" placeholder="Indiquer un libellé pour l'erreur" />
      </div>
      <div class="item">
        <label for="body">Description</label>
        <textarea name="body" id="body" placeholder="Décriver l'erreur constatée"><?php echo $datas['body'] ?></textarea>
      </div>
      <div class="submit">
        <input type="submit" name="submit" value="Envoyer" />
        <input type="reset" name="reset" value="Annuler" />
      </div>
    </form>
    <?php } ?>
    
    <!-- chargement des fichiers javascript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
    <script src="scripts.js"></script>
  </body>
</html>
