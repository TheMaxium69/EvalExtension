<?php

class Tirage_ShortCode {
    //Function qui ce lance au démarrage de la class 'Tirage_ShortCode'
    public function __construct() {
        //Création d'un shortcode qui une fois pausez applique la function 'shortcodeInscription'
        add_shortcode('tirage_formulaire', array($this, 'shortcodeInscription'));
    }

    public function shortcodeInscription () {
        //Création du formulaire inscription
        $html = '
<form action="" method="POST"><p>
    <label for="tirage-formulaire-lastname">Votre Nom :</label><input type="text" name="lastname" id="tirage-formulaire-lastname">
    <label for="tirage-formulaire-firstname">Votre Prénom :</label><input type="text" name="firstname" id="tirage-formulaire-firstname">
    <label for="tirage-formulaire-email">Votre E-Mail :</label><input type="email" name="email" id="tirage-formulaire-email">
    <label for="tirage-formulaire-date">Votre Date de naissance :</label><input type="text" name="date" id="tirage-formulaire-date">
    <label for="tirage-formulaire-city">Votre Ville :</label><input type="text" name="city" id="tirage-formulaire-city">
</p><input type="submit" value="S\'inscrire"></form>';
        return $html;
    }
}

