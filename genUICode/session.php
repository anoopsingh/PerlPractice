<?php

require_once("AppDBLocal.class.php");
require_once("AppDB.class.php");
class sessionHandle extends AppDB {
   private $sessionDebug = FALSE;
   function __construct(){
        parent::__construct();
        if($this->xmlConfig->SESSDEBUGLVL == '1'){
            $this->sessionDebug = TRUE;
        }else{
            $this->sessionDebug = FALSE;
        }
        #$this->sessionDebug = FALSE;
   }
   
   function open($savePath, $sessName) {
      if(!$this){
         parent::__construct();
         return true;
      }
      return false;
   }
   
   function close() { 
      $this->gc(get_cfg_var("session.gc_maxlifetime"));
      return true;
   }
   function read($id) {
        //fetch the session record
        $sql = "SELECT data FROM session WHERE id = '$id'";
        if($this->sessionDebug){
            $this->logSystemStatement('read',"FORMULATED SQL STATEMENT");
            $this->logSystemStatement('read',$sql);
        }
        try {
            $stmt = $this->prepare($sql) or die("Unable to prepare SQL statement: $sql");
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['data'];
        }catch(Exception $e){
            if($this->sessionDebug){
                $this->logSystemStatement('read',"EXCEPTION ERROR");
                $this->logSystemStatement('read',$e->getMessage());
            }
        }
    }

    function write($id,$data){
        $sql = "REPLACE INTO session SET id = '$id', lastaccess = '".time()."', data = '$data'";
        if($this->sessionDebug){
            $this->logSystemStatement('write',"FORMULATED SQL STATEMENT");
            $this->logSystemStatement('write',$sql);
        }
        try {
            $stmt = $this->prepare($sql) or die("Unable to prepare SQL statement: $sql");
            $stmt->execute() or die("Unable to execute prepared SQL statment: $sql");
            if($stmt->rowCount() > 0){
               return true;
            }
        }catch(Exception $e){
            if($this->sessionDebug){
                $this->logSystemStatement('write',"EXCEPTION ERROR");
                $this->logSystemStatement('write',$e->getMessage());
            }
        }
        return false;
    }

    function destroy($id) {
        //remove session record from the database and return result
        $sql = "DELETE FROM session WHERE id = '$id'";
        if($this->sessionDebug){
            $this->logSystemStatement('destroy',"FORMULATED SQL STATEMENT");
            $this->logSystemStatement('destroy',$sql);
        }
        try {
            $count = $this->exec($sql);
            if($count > 0){
               return true;
            }
        }catch(Exception $e){
            if($this->sessionDebug){
                $this->logSystemStatement('destroy',"EXCEPTION ERROR");
                $this->logSystemStatement('destroy',$e->getMessage());
            }
        }
        return false;
    }

    function gc($maxLifeTime){
        //garbage collection
        $timeout = time() - $maxLifeTime;
        $sql = "DELETE FROM session WHERE lastaccess < '".$timeout."'";
        if($this->sessionDebug){
            $this->logSystemStatement('gc',"FORMULATED SQL STATEMENT");
            $this->logSystemStatement('gc',$sql);
        }
        try {
            return $this->exec($sql);
        }catch (Exception $e){
            if($this->sessionDebug){
                $this->logSystemStatement('gc',"EXCEPTION ERROR");
                $this->logSystemStatement('gc',$e->getMessage());
            }
        }
    }
    
}

$session = new sessionHandle();
session_set_save_handler(array(&$session,"open"), array(&$session,"close"), array(&$session,"read"), array(&$session,"write"), array(&$session,"destroy"), array(&$session,"gc"));
session_start();
?>
