<?php
class Uploadfile extends Datafile {
    public $image = "image";
    public $folder = "files/";
    public $prefix = "";
  
    /*
private function pictureLoad($id, $width=0)
{
   if( !($img = $this->get($id))) {
      return false;
   }
   $id = $img['id'];
   $sufix = $img['sufix'];

   $imdata;
   if($sufix==".jpg")
      $imdata = imagecreatefromjpeg($this->folder.$this->prefix.$id.$sufix);
   else if($sufix==".gif")
      $imdata = imagecreatefromgif($this->folder.$this->prefix.$id.$sufix);
   else if($sufix==".png")
      $imdata = imagecreatefrompng($this->folder.$this->prefix.$id.$sufix);  
   else
      return false;
   if($width) $imdata = imagescale($imdata, $width);
   return $imdata;
} 
*/
public function insert($file){
   switch($file['type']){
       case "image/jpg":
       case "image/jpeg":
         $sufix = ".jpg";
       break;
       case "image/gif":
         $sufix = ".gif";
       break;
       case "image/png":
         $sufix = ".png";
       break;
       default:
         return false;
    }
    
    parent::insert( array( 
       "userid"=>$_SESSION['user']['userid'],
       "postid"=>$_POST["postid"],
       "topicid"=>$_SESSION["topicid"],
       "name"=>basename($file['name'],$sufix),
       "sufix"=>$sufix,
       "title"=>($_POST["imagetitle"]!='')?$_POST["imagetitle"]:basename($file['name'],$sufix),
       "date"=>date("Y-m-d H:i:s")
    ));
    $img = parent::getLastItem();
    if( move_uploaded_file($file['tmp_name'], $this->folder.$this->prefix.$img["id"].$sufix ) ){
       return $img["id"];
    }else{
       parent::delete($img["id"]);
       return false;
    }  
}

public function update($id, $title=""){
    $img = $this->get($id);
    $img["title"]=($title!="")?$title:$img["name"];
    return parent::update( $img );
}

public function delete($id,$key=false){
    if( !($img = $this->get($id)) ) return false; 
    if( unlink($this->folder.$this->prefix.$img["id"].$img["sufix"]) )
       return parent::delete($id);
}

public function delete_from_post( $postid ){
   $result = true;
   if( $img = $this->getAll( $postid, "postid" ) )
     foreach( $img as $k=>$v ) 
        if(!$this->delete($k)) 
            $result=false;
   return $result;      
}

}