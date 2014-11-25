<?php 

class DBManager 
{
	private static $instance;
	private $db;


	private function __construct(){

        }

	public static function getInstance() 
	{
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
	}

	public function getConnection() 
	{
            if(is_null($this->db))
            {
                if ($_SERVER['SERVER_NAME'] === 'localhost') {
                    $servidor = 'localhost';
                    $bd = 'readme'; $user = 'root'; $pwd = 'root';

                } else if ($_SERVER['SERVER_NAME'] === 'proyectos.proweb.ua.es') {             
                    $servidor = 'localhost'; $bd = 'DBp13lpg'; $user = 'p13lpg'; $pwd = '48328029S';
                }

                $this->db = new PDO('mysql:host='.$servidor.';dbname='. $bd,$user, $pwd);
                $this->db->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                $this->db->query("SET NAMES 'utf8'");
                    
            }
            return $this->db;
	}

	public function close() {
		$this->db = null;
	}
        
        
}

