<?php

class TirageExtension_Session
{
    public function __construct()
    {
        //On démarrre la session
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function createMessage($type, $message)
    {
        $_SESSION['tirage-ex'] =
            [
                'type'    => $type,
                'message' => $message
            ];
    }

    public function getMessage()
    {
        return isset($_SESSION['tirage-ex']) && count($_SESSION['tirage-ex']) > 0 ? $_SESSION['tirage-ex'] : false;
    }

    public function destroy()
    {
        $_SESSION['tirage-ex'] = array();
    }
}
