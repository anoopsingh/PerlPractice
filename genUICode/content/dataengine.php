<?php
header( "Cache-Control: s-maxage=0, no-store, no-cache, must-revalidate, post-check=0, pre-check=0" );
header( "Pragma: private");
header( "Pragma: no-cache" );
// setup application include path (for classes and autoloading capabilities
// TODO: Add autoload function, remove 'require_once'  statements
if (!defined('DIR_SEP')) { define('DIR_SEP', DIRECTORY_SEPARATOR); } 
if (!defined('APP_DIR')) { define('APP_DIR', dirname(__FILE__));   }
$include_path = APP_DIR . PATH_SEPARATOR;
$paths = array('config', 'php/classes');
foreach($paths as $path){
  $include_path .= APP_DIR . "/" . $path . PATH_SEPARATOR; 
}
set_include_path(get_include_path() . PATH_SEPARATOR . $include_path  );
include_once("session.php");
include_once('AppDB.class.php');
class dataengine extends AppDB {
   private $dataset;
   private $xmlOutput;
   private $query = null;
   private $queryCount = null;
   private $resultCount = 0;   
   private $errorFlag = 0;
   private $errorMsg;
   private $session = false;
   public $utf8 = false;
   protected $externalParams = null;
   function __construct(){
      parent::__construct();
      if(!is_array($this->queries)){
        $this->errorFlag = 1;
        $this->prepareErrorDataSet_xml($e->getMessage());
      }      
   }
   /*
	Method updated for XUL interface.
    
    */
    public function setParams($p) {
	$this->externalParams = $p;
    }
   public function setDataSet($queryString, $orderby=null) {
      //fetch the session record
      try {
        $stmt = $this->prepare($queryString) or die("Unable to prepare SQL statement: $queryString");
        $stmt->execute() or die("Unable to execute prepared SQL statment: $queryString");
        $this->dataset = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if((!is_null($orderby)) && (trim($orderby) != '')){            
            $descOrder = (preg_match('/desc/',$orderby))?true:false;
            $orderby = preg_replace('/(asc|desc| )/','',$orderby);
            $sortFields = array_unique(split(",", $orderby));
            if(sizeof($sortFields) > 1)
              array_push($sortFields,array_shift($sortFields));
            //$this->logSystemStatement('setDataSet',"ORDERBY2: " . implode(",",array_reverse($sortFields)), true);
            $content = "
                        \$sortFields = array('" . implode("','",array_reverse($sortFields)) . "');
                        for(\$i = 0; \$i < count(\$sortFields); \$i++){
                            \$fieldname = \$sortFields[\$i];
                            if((\$j=strnatcasecmp(trim(\$a[\"{\$fieldname}\"]) , trim(\$b[\"{\$fieldname}\"]))) == 0)
                                continue;
                            else
                                break;
                        }
                        return strnatcasecmp(trim(\$a[\"{\$fieldname}\"]) , trim(\$b[\"{\$fieldname}\"]));
                      ";
            usort($this->dataset, create_function('$a,$b',$content));
            if($descOrder)
              $this->dataset = array_reverse($this->dataset);
        }
        if ( preg_match('/SQL_CALC_FOUND_ROWS/', $queryString) ) {
            $queryCount = "select FOUND_ROWS()";
            $stmt = $this->prepare($queryCount) or die("Unable to prepare SQL statement: $queryCount");
            $stmt->execute() or die("Unable to execute prepared SQL statment: $queryCount");
            $result = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
            $this->resultCount = $result[0];
        }        
      }
      catch (Exception $e){
        $this->errorFlag = 1;
        $this->errorMsg = $e->getMessage();
        $this->logSystemStatement('setDataSet',"Exception: " . $e->getMessage(), true);
      }
   }
   public function prepareDataSet_xml() {
      $this->xmlOutput .= "<nodes>\n";
      if(is_array($this->dataset) && sizeof($this->dataset) > 0 ){
        $assoc = array();
        foreach($this->dataset as $index => $row){
           $this->xmlOutput .= "\t<node>\n";
            foreach($row as $element => $value){
              if(($element == 'label1') && ($this->session)){
                if($value){
                  $userData = unserialize(base64_decode(substr($value, strpos($value,'"'), strrpos($value,'"'))));
                  $value = $userData['displayname'];
                }else
                  $value = "Unknown";
              }
              
              $value = $this->normalize_special_characters($value);
               $this->xmlOutput .= "\t\t<$element><![CDATA[$value]]></$element>\n";
            }
           $this->xmlOutput .= "\t</node>\n";
        }
      }
      $this->xmlOutput .= "</nodes>";
      if($this->resultCount > 0)
        $this->xmlOutput .= "<count>" . "<![CDATA[$this->resultCount]]>" . "</count>\n";      
   }


    //Function inserts a 'count' tag to the output XML
    //
    public function prepareCount_xml() {
        $this->xmlOutput .= "<count>";

        foreach($this->dataset as $index => $row) {
            
            //$this->logSystemStatement('Pawel', "index = <$index> row = <$row>", true);

            foreach($row as $element => $value){
                $this->xmlOutput .= "<![CDATA[$value]]>";
            }
            break;
        }

        $this->xmlOutput .= "</count>\n";
    }

    // To be called instead of retrieveXMLDataSet()
    // To avoid PHP fatal errors (Allowed memory size of xyz bytes exhausted)
    //
    public function echoXMLDataSet() {
     
        if ($this->errorFlag) {
            $this->xmlOutput = ($this->utf8)?"<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n":"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";            
            $this->xmlOutput .=
                "<results>\n" .
                "\t<error>\n" .
                "\t\t<message>." . $this->errorMsg . "</message>\n" .
                "\t</error>\n" .
                "</results>\n";
        } else {
            $xml = ($this->utf8)?"<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n":"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
            //Do not combine the two commands below into one
            $this->xmlOutput = $xml .
                "<results>\n" . 
                "\t<success>\n" . 
                "\t\t<message>Successful Retrieval of dataset</message>\n" .
                "\t</success>\n" .
                "\t<query><![CDATA[$this->query]]></query>\n" .
                $this->xmlOutput;
                
            $this->xmlOutput .= "</results>\n";
        }

        echo $this->xmlOutput;
        
    }

   public function retrieveXMLDataSet() {
    if($this->errorFlag){
        echo $this->prepareErrorDataSet_xml($this->errorMsg);
      }else{
        echo  $this->prepareSuccessDataSet_xml("Successful Retrieval of dataset",$this->xmlOutput);
      }
   }
   public function prepareErrorDataSet_xml($m){
      $xmlOutput = ($this->utf8)?"<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n":"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
      $xmlOutput .= "<results>\n";
      $xmlOutput .= "\t<error>\n";
      $xmlOutput .= "\t\t<message>$m</message>\n";
      $xmlOutput .= "\t</error>\n";
      $xmlOutput .= "</results>\n";
      return $xmlOutput;
   }
   public function prepareSuccessDataSet_xml($m,$data=null){
      $xmlOutput = ($this->utf8)?"<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n":"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
      $xmlOutput .= "<results>\n";
      $xmlOutput .= "\t<success>\n";
      $xmlOutput .= "\t\t<message>$m</message>\n";
      $xmlOutput .= "\t</success>\n";
      $xmlOutput .= "\t<query><![CDATA[$this->query]]></query>\n";
      $xmlOutput .= $data;
      $xmlOutput .= "</results>\n";
      return $xmlOutput;
   }
   
   public function loadQuery($q){
      #$this->logSystemStatement('loadQuery',"HERE",true);
      foreach($this->queries as $query => $qarray){
        #$this->logSystemStatement('loadQuery',"QUERY: $query SUBMITTED QUERY: $q",true);
         if($query === $q){
            $sortOrder = null;
            if($q == "q49") { $this->session = true; }
            #$this->logSystemStatement('loadQuery',"QUERY MATCHED: $query",true);
            if(array_key_exists('sql',$qarray)){             
              $this->query = $qarray['sql'];
              #$this->logSystemStatement('loadQuery',"QUERY HAS SQL: {$this->query}",true);
              $this->logSystemStatement('loadQuery'," external params : \n". print_r($this->externalParams,true));
              if(isset($this->externalParams['where']) || isset($this->externalParams['orderby']) || isset($this->externalParams['arg3'])){
                
                // Defined and not empty?
                if (isset($this->externalParams['where'])) {
                    $whereStmt = " WHERE " . $this->externalParams['where'];
                    $this->logSystemStatement('loadQuery'," WHERE clause : {$this->externalParams['where']}",true);
                }  else {
                    $whereStmt = "";
                }   

                if (isset($this->externalParams['unionQuery']) && !$this->externalParams['unionQuery']){
                    $qarray2 = split("UNION", $this->query);
                    $this->query = $qarray2[0];
                }
                
                //Do we need to perform sprintf or a simple append?
                if (preg_match('/%s.*%s/s', $this->query) and isset($this->externalParams['arg2'])) {
                    $this->query = sprintf($this->query, $whereStmt, $this->externalParams['arg2']);
                } else if (preg_match('/%s/', $this->query)) {
                    $this->query = sprintf($this->query, $whereStmt);              
                } else {
                    $this->query .= $whereStmt;
                }
                

                if (isset($this->externalParams['orderby']) && $this->externalParams['orderby']){
                    $this->query .= " ORDER BY " . $this->externalParams['orderby'];
                    $sortOrder = $this->externalParams['orderby'];
                    //$this->logSystemStatement('setDataSet',"ORDERBY: " . $sortOrder, true);
                }
                if (isset($this->externalParams['arg3']))
                    $this->query .= ", " . $this->externalParams['arg3'];

                $this->logSystemStatement('loadQuery',"QUERY HAS WHERE: {$this->query}",true);
              }


            if (isset($this->externalParams['limit'])) {
                $this->query .= " limit " .$this->externalParams['limit'];
            }

              #$this->logSystemStatement('loadQuery',"FORMULATED SQL STATEMENT",true);
              #$this->logSystemStatement('loadQuery',$this->query,true);
              
              $this->setDataSet($this->query, $sortOrder);
              $this->prepareDataSet_xml();
/*
              if ( preg_match('/SQL_CALC_FOUND_ROWS/', $this->query) ) {
                  $queryCount = "select FOUND_ROWS()";
                  $this->setDataSet($queryCount);
                  $this->prepareCount_xml();
              }
*/
              return true;
            }
         }       
      }
      return false;
   }


    public function executeQuery($q) {
        
        $this->query = $q;

        if ( isset($this->externalParams['where'])) {
            $this->query .= " where " .$this->externalParams['where'];
        }
        
        
        if (isset($this->externalParams['limit'])) {
            $this->query .= " limit " .$this->externalParams['limit'];
        }
        
        //$this->logSystemStatement('executeQuery', $this->query, true);
        $this->setDataSet($this->query);
        $this->prepareDataSet_xml();
        return true;
    }

}
#ini_set("memory_limit","64M");
header("Content-type: text/xml");

$de = new dataengine();

$params = array();

if ( isset($_GET['queryid']) || isset($_GET['query']) ) {
    $params = $_GET;
    
} elseif ( isset($_POST['queryid']) || isset($_POST['query']) ) {
    $params = $_POST;
}

if ( !isset($params['query']) and !isset($params['queryid']) ) {
    echo $de->prepareErrorDataSet_xml("Data Engine ERROR: Either 'queryid' or 'query' has to be defined");

} else {
    
    $de->setParams($params);
    
    if (isset($params['queryid'])) {
        
        if (!empty($params['queryid'])) {
            $q = $params['queryid'];
            if (!$de->loadQuery($q)) {
                $de->prepareErrorDataSet_xml("Data Engine Error: Unable to load query identifier $q");
            }
        } else {
            $de->prepareErrorDataSet_xml("Data Engine Error: Unable to determine query identifier");
        }

    } elseif (isset($params['query'])) {
        
        if (!empty($params['query'])) {
            $q = $params['query'];
            
            if(!$de->executeQuery($q)){
                $de->prepareErrorDataSet_xml("Data Engine Error: Unable to execute query $q");
            }
        } else {
            $de->prepareErrorDataSet_xml("Data Engine Error: Unable to determine query");
        }
    }

    try {
        //$de->retrieveXMLDataSet();
        $de->echoXMLDataSet();
        
    } catch(Exception $e) {
        echo $de->prepareErrorDataSet_xml($e->getMessage);
    }
}
?>
