<?php
    
    spl_autoload_register(function ($class) {
        if (file_exists($class . '.php')) {
            require_once $class . '.php';
        }
    });
    
    $classe = $_REQUEST['class'];
    $method = isset($_REQUEST['method']) ? $_REQUEST['method'] : null;
    
    if (class_exists($classe)) {
        
        $pagina = new $classe($_REQUEST);
        if (!empty($method) and (method_exists($classe, $method))) {
            $pagina->$method($_REQUEST);
        }
        $pagina->show();
    }


    
