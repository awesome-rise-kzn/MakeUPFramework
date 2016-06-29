<?php
/*
 * Author: Blezigen
 * Email: khairullin.it@gmail.com
 * error__string__text - сss стиль для вывода ошибок
*/

$site = array(
    'header' => "inc/header.phtml",
    'footer' => "inc/footer.phtml",
    'default' => "inc/pages/index.phtml",
    'pages' => array(
        'dance' => "inc/pages/dance.phtml", // ?action=dance
        'teachers' => "inc/pages/teachers.phtml",// ?action=teachers
        'curiculumb' => "inc/pages/curiculumb.phtml",// ?action=teachers
        'contacts' => "inc/pages/contacts.phtml",// ?action=teachers
    )
);


/////////////////==> NO TOUCH <==/////////////////

class MakeUPFramework{
    protected $_options = array(
        "auto_repair_project" => true,
        'site_tree' => [],
    );


    function __construct($tree)
    {
        $this->_options['site_tree'] = $tree;
        $this->repairFolder("inc/");
        $this->repairFolder("inc/pages/");

        $this->validateFile($this->_options['site_tree']["header"]);
        $this->validateFile($this->_options['site_tree']["footer"]);
        $this->validateFile($this->_options['site_tree']["default"]);

        foreach ($this->_options['site_tree']["pages"] as $key => $value){
            $this->validateFile($value);
        }
    }

    public function run($data)
    {
        $site = $this->_options['site_tree'];

        include $site["header"];

        if (array_key_exists($data, $site["pages"])) {
            include $site["pages"][$data];
        }
        else{
            include $site["default"];
        }
        include $site["footer"];
    }


    public function validateFile($file){
        if (preg_replace("/^(.*)\./", '', $file) == "phtml"){
            if (!file_exists($file)) {
                if ($this->_options["auto_repair_project"]) {
                    $text = "File: " . $file . "<br />";
                    $fp = fopen($file, "w");
                    fwrite($fp, $text);
                    fclose($fp);
                } else {
                    echo "Файл " . $file . " не существует";
                }
            }
        }
    }


    public function repairFolder($folder){
        if (!file_exists($folder)) {
            if ($this->_options["auto_repair_project"]){
                mkdir($folder, 0777);
            }
            else {
                echo "Папки ".$folder." не существует";
            }
        }
    }
}
$MUPF = new MakeUPFramework($site);
$MUPF->run($_GET["action"]);

