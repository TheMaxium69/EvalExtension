<?php

/*
Plugin Name: EvalExtension
Plugin URI : https://github.com/TheMaxium69/EvalExtension
Author: Maxime Tournier
Author URI: https://tyrolium.fr
Description: Extension WordPress, L'Eval d'Extension
Version: 1.0-BETA
*/

//ShortCode : <p>[tirage_formulaire][/tirage_formulaire]</p>



//J'importe mes ficher Php dans class principal
require_once "tirage_extension.php";
require_once "tirage_extension_session.php";

class TirageExtension {
    //Function qui ce lance au démarrage de la class 'TirageExtension'
    public function __construct()
    {
        //On Charge les ficher que ce situe dans la function 'loadFiles'
        add_action('init', array('TirageExtension', 'loadFiles'));

        //On charge la function Install : Qui sert a s'appliqué uniquement quand l'extension s'install
        register_activation_hook(__FILE__, array('TirageExtension', 'install'));

        //On charge la function Uninstall : Qui sert a s'appliqué uniquement quand l'extension se desinstall
        register_uninstall_hook(__FILE__, array('TirageExtension', 'uninstall'));

        //On charge la function 'saveForm'
        add_action('wp_loaded', array($this, 'saveForm'), 1);

        //On charge la function 'checkInfo'
        add_action('wp_loaded', array($this, 'checkInfo'), 2);

        //Lancement de la class 'Tirage_ShortCode'
        new Tirage_ShortCode();

    }

    public static function loadFiles()
    {
        //On Charge le ficher style.css
        wp_register_style('TirageExtension', plugins_url('style.css', __FILE__));
        wp_enqueue_style('TirageExtension');

        //On Charge le ficher main.js
        wp_register_script('TirageExtension', plugins_url('main.js', __FILE__));
        wp_enqueue_script('TirageExtension');
    }


    public static function install() {
        global $wpdb;

        //On créer une table uniquement si elle n'existe pas
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tirage_au_sort (id INT(11) NOT NULL AUTO_INCREMENT , last_name VARCHAR(255) NOT NULL , first_name VARCHAR(255) NOT NULL ,  email VARCHAR(255) NOT NULL ,  date VARCHAR(255) NOT NULL ,  city VARCHAR(255) NOT NULL , PRIMARY KEY (id));");
    }

    public static function uninstall() {
        global $wpdb;


        //On supprime la table uniquement si elle existe pas
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tirage_au_sort;");
    }

    public function saveForm() {

        //Conditions pour vérifer si le formulaire a bien tout ce qu'on lui a demander et non vide
        if (isset($_POST['lastname']) && !empty($_POST['lastname']) &&
            isset($_POST['firstname']) && !empty($_POST['firstname']) &&
            isset($_POST['email']) && !empty($_POST['email']) &&
            isset($_POST['date']) && !empty($_POST['date']) &&
            isset($_POST['city']) && !empty($_POST['city'])
        ) {

            //Recuperation de class pour les notifications
            $tirageExtension_Session = new TirageExtension_Session();

            //On Met tout ce qu'on a besoin dans des variable
            $lastname = $_POST['lastname'];
            $firstname = $_POST['firstname'];
            $email = $_POST['email'];
            $date = $_POST['date'];
            $city = $_POST['city'];

            global $wpdb;

            //On va chercher si l'email de utilisateur est dans la base
            $user = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}tirage_au_sort WHERE email = '$email'");

            //Si on a bien un resultat dans requette precedente ça veut dire qu'on connais l'email
            if (is_null($user)) {

                //On met tout les informations dans un tableau
                $datas = [
                    'last_name' => $lastname,
                    'first_name' => $firstname,
                    'email' => $email,
                    'date' => $date,
                    'city' => $city,
                ];

                //On insert nos donnée dans la base de donén
                $result = $wpdb->insert("{$wpdb->prefix}tirage_au_sort", $datas);

                //Si la requette nous renvoie 'false' alors il y a une erreur
                if ($result === false) {
                    //On génére une notification grace a Class 'TirageExtension_Session'
                    $tirageExtension_Session->createMessage("error", "Il y a une erreur, réseillez plus tarrd");

                //Sinon il s'agit d'un succes dans l'insert de donné dans la base
                } else {
                    //On génére une notification grace a Class 'TirageExtension_Session'
                    $tirageExtension_Session->createMessage("success", "Votre inscription est un véritable succes.");
                }
            } else {
                //On génére une notification grace a Class 'TirageExtension_Session'
                $tirageExtension_Session->createMessage("error", "Votre Email est déjà connue de nos service.");
            }

        }
    }

    public function checkInfo()
    {
        //On recupère la class 'TirageExtension_Session'
        $tirageExtension_Session = new TirageExtension_Session();

        //recupère le message de la class 'TirageExtension_Session'
        $message = $tirageExtension_Session->getMessage();

        if ($message !== false) {
            echo ("
                <p class='tirage-ex-info " . $message["type"] . "'>
                    " . $message["message"] . "
                </p>
            ");
        }

        $message = $tirageExtension_Session->destroy();
    }

}

//Lancement de la function princpal "TirageExtension"
new TirageExtension();

