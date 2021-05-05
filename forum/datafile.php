<?php
class Datafile
{
    public $filename;
    public $separator;
    
    protected $names;
    protected $key;
    protected $autoincrenet;
  
    public function __construct($names,$filename='data.csv',$key='id',$autoincrement=true,$separator=';') {
       $this->names = $names;
       $this->filename = $filename;
       $this->key = $key;
       $this->autoincrement = $autoincrement;
       $this->separator = $separator;
       if( !is_file($filename) ) file_put_contents($filename,'');
    }
    
    public function insert($data) {
       
       if($this->autoincrement){
          if( count($data)!=(count($this->names)-1) ) return false;
          $data[$this->key]=$this->getNewId();
       }else{
          if($d = $this->get($data[$this->key])) return false;
          if( count($data)!=count($this->names) ) return false;
       }
       return file_put_contents( $this->filename, implode($this->separator, array_map("bin2hex", $data) )."\n", FILE_APPEND|LOCK_EX );
    }
    
    public function update($data) {
       if( count($data)!=count($this->names) ) return false;
       $tmpfile = tempnam("/tmp", "dat");
       $tmph = fopen($tmpfile,'w');
       $fh = fopen($this->filename,"r");
       flock($fh,LOCK_SH);
       while($buf=fgets($fh)){
          $d = array_combine($this->names,array_map("hex2bin",explode($this->separator,trim($buf))));
          if($d[$this->key]==$data[$this->key])
             $buf = implode($this->separator,array_map("bin2hex",$data))."\n";
          fwrite( $tmph, $buf, strlen($buf) );
       }
       flock($fh,LOCK_UN);
       fclose($fh);
       fclose($tmph);
       return rename($tmpfile,$this->filename);
    }

    public function delete($id,$key=false) {
       if(!$key) $key=$this->key;
       $tmpfile = tempnam("/tmp", "dat");
       $tmph = fopen($tmpfile,'w');
       $fh = fopen($this->filename,"r");
       flock($fh,LOCK_SH);
       while($buf=fgets($fh)){
          $d = array_combine($this->names,array_map("hex2bin",explode($this->separator,trim($buf))));
          if($d[$key]!=$id)
            fwrite( $tmph, $buf, strlen($buf) );
       }
       flock($fh,LOCK_UN);
       fclose($fh);
       fclose($tmph);
       return rename($tmpfile,$this->filename);
    }

    public function get($val,$key=false) {
       if(!$key) $key=$this->key;
       $fh = fopen($this->filename,"r");
       flock($fh,LOCK_SH);
       $d=false;
       while($buf=fgets($fh)){
          $item = array_combine($this->names,array_map("hex2bin",explode($this->separator,trim($buf))));
          if($item[$key]==$val) {
             $d=$item;
             break;
          } 
       }
       flock($fh,LOCK_UN);
       fclose($fh);
       return $d;
    }

    public function getAll($val=false,$key=false) {
       if(!$key) $key=$this->key;
       $fh = fopen($this->filename,"r");
       flock($fh,LOCK_SH);
       $result=false;
       while($buf=fgets($fh)){
          $d = array_combine($this->names,array_map("hex2bin",explode($this->separator,trim($buf))));
          if(!$val) $result[$d[$this->key]]=$d;
          elseif($d[$key]==$val) $result[$d[$this->key]]=$d; 
       }
       flock($fh,LOCK_UN);
       fclose($fh);
       return $result;
    }
    
    public function getNames(){ return $this->names; }
    
    public function getLastItem(){
       $fh = fopen($this->filename,"r");
       flock($fh,LOCK_SH);
       $buf=false;
       while($tmp=fgets($fh)){$buf=$tmp;}
       flock($fh,LOCK_UN);
       fclose($fh);
       if($buf) 
          return $result = array_combine($this->names,array_map("hex2bin",explode($this->separator,trim($buf))));
       else 
          return false;
    }
    
    protected function getNewId(){
       $fh = fopen($this->filename,"r");
       $last=false;
       while( $l = fgets($fh)  ){$last=$l;};
       fclose($fh);
       if(!$last) return 1;
       $d = array_combine($this->names,array_map("hex2bin",explode($this->separator,trim($last))));
       return $d[$this->key]+1; 
    }
}
