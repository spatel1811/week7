<?php  
ini_set('display_errors', 'On');
error_reporting(E_ALL);
$obj = new main();

abstract class pdoConnection {
    protected $html;  
    
    public function __construct(){        
        $this->html .= '<html>';        
        $this->html .= '<body>';
    }    
    
    public static function openConnection($serverName,$userName,$password){ 
        
        $connectionString= new PDO("mysql:host=$serverName;dbname=sjp77", $userName, $password);
        // set the PDO error mode to exception
        $connectionString->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);       
        
        return $connectionString;        
    }
    public static function fetchData($connectionString,$query){
        $stmt = $connectionString->prepare($query); 
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function closeConnection(){
        $connectionString = null;        
    }
    
    public function __destruct(){
        $this->html .= '</body></html>';        
        die($this->html);
    }  
}
class createHtml extends pdoConnection{
    
    public function __construct($hmtlObj){
        
        $this->html.=createHtml::generateHtml($hmtlObj);        
    }
    
    public function generateHtml($htmlEntity){
        $html='';        
        $html='<table border="1">';
        $counter=0;
        foreach($htmlEntity as $output){
          $html.='<tr>';
          foreach($output as $data){            
              $html.='<td>'.$data.'</td>';                      
          }
          $counter++;
          $html.='</tr>';
        }
        $html.='</table>';        
        stringFunctions::printMessage($counter);
        return $html;        
    }
}
class stringFunctions{
    
    public static function printMessage($message){
        print($message);
    }
}

class main {
    protected $message;
    public function __construct(){ 
        $serverName = "sql1.njit.edu";
        $userName = "sjp77";
        $password = "hS1DY7pYO";
        $query="SELECT * FROM accounts where id<6";     
        try {              
            $conn= createHtml::openConnection($serverName,$userName,$password); 
            stringFunctions::printMessage("Connected successfully <br>");
            $htmlObj= createHtml::fetchData($conn,$query);            
            $result=new createHtml($htmlObj);
        }
        catch(PDOException $e){
            stringFunctions::printMessage("Connection failed: " . $e->getMessage()."<br>");
        }        
    }  
}

?>