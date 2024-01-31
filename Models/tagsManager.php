<?php
use phpDocumentor\Descriptor\Builder\Reflector\Tags\GenericTagAssembler;

class tagsManager
{
    private $_db; // Instance de PDO - objet de connexion au SGBD

    /** 
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db)
    {
        $this->_db = $db;
    }

    public function getTagsProjet($idtags)
    {
        $req = "SELECT tags.Id_tags , tags.intitule FROM tags WHERE tags.Id_tags = ?";
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array($idtags));

        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            throw new Exception("SQL Error: " . implode(" ", $errorInfo));
        }

        $tags = [];
        while ($tagsData = $stmt->fetch()) {
            $tags[] = new Tags($tagsData);
        }
        // print_r($tags);

        return $tags;
    }


    public function allTags()
    {
        $req = "SELECT * FROM tags";
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array($req));

        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            throw new Exception("SQL Error: " . implode(" ", $errorInfo));
        }

        $tags = [];

        while ($donnees = $stmt->fetch()) {
            $tags[] = new Tags($donnees);
        }

        // print_r($contextes);
        // var_dump($contextes);
        return $tags;
    }
}
