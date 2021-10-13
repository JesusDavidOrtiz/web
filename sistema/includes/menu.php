<?php

Class MenuDinamic {
    private $_db;
    
    public function __construct() {
        $this->_db = new Database();
               
    }
    
    public function getMenu()
	{
		if($_SESSION['rol'] == 1){
	    $menu = $this ->_db->query("SELECT * FROM menu WHERE estatus = 1 ");
	    return $menu->fetchAll();
	    }else{
	    	$menu = $this ->_db->query("SELECT * FROM menu WHERE estatus = 1 and idmenu !=1 ");
	    return $menu->fetchAll();
	    }
	}
        
       public function getCategoria($idmenu)
	{
	$menu = $this ->_db->query("SELECT * FROM categorias WHERE estatus = 1 and idmenu = $idmenu ");
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