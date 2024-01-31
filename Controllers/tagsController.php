<?php
include "Modules/tags.php";
include "Models/tagsManager.php";

class tagsController
{
    private $tagsManager; // instance du manager
    private $twig;

    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db, $twig)
    {
        $this->tagsManager = new tagsManager($db);
        $this->twig = $twig;
    }
} 