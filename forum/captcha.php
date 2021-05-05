<?php
class Captcha {
   public $code="";
   public $font = array();

   private $x = 250;
   private $y = 50;

   public function __construct(){
     if( isset($_SESSION["captcha"]) ) $this->code=$_SESSION["captcha"];
   }
   
   public function generate(){
      $this->font = array( 'fonts/stormfaze.ttf', 
                      'fonts/hemihead.ttf',
                      'fonts/leadcoat.ttf',
                      'fonts/stocky.ttf',
                      'fonts/arial.ttf' );

      $this->code =  chr(random_int(ord('A'),ord('Z')))
                    .chr(random_int(ord('A'),ord('Z')))
                    .chr(random_int(ord('A'),ord('Z')))
                    .chr(random_int(ord('A'),ord('Z')));
                    
                 
      $_SESSION["captcha"]=$this->code;     

      
      $im = imagecreate($this->x, $this->y);
      $background = imagecolorallocatealpha($im, 0, 0, 0, 127);
      imagefill($im,0,0, $background);
     
      $text_color = imagecolorallocate($im, 255,255,255);

      for( $n=0;$n<strlen($this->code);$n++){
        imagettftext( $im, 
                      random_int(20,28), 
                      random_int(-50,50),
                      10+($n*($this->x/4)), 
                      30,
                      $text_color, 
                      $this->font[random_int(0,4)],
                      $this->code[$n] 
                    );
      }      

      ob_start();
      header ('Content-Type: image/png');
      imagepng($im);
      $buf= ob_get_contents();
      ob_end_clean();
      imagedestroy($im);
      return $buf;         
   }
   
   public function check($code){
     return ($code==$this->code)?true:false;
   }

}