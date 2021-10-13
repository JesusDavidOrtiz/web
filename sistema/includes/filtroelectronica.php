<?php

Class FiltroCat {
    private $_db;
    
    public function __construct() {
        $this->_db = new Database();
               
    }
            
       public function getCategoria()
	{
	$menu = $this ->_db->query("SELECT * FROM categorias WHERE estatus = 1 and idmenu = '2'");
	return $menu->fetchAll();
	}   
        
        public function getSubCat($idcat)
	{
	$menu = $this ->_db->query("SELECT * FROM subcategoria WHERE estatus = 1 and idcat = $idcat ");
	return $menu->fetchAll();
	}
 
 	/*Filtro de categorias*/
	public function CatFiltro()
	{
	$menu = $this ->_db->query("SELECT DISTINCT cat.categoria, m.menu
				 FROM subcategoria sub
				 INNER JOIN categorias cat
				 ON sub.idcat = cat.idcat
                 INNER JOIN menu m
				 ON cat.idmenu = m.idmenu
				 WHERE m.idmenu = 6");
	return $menu->fetchAll();
	}


}
?>