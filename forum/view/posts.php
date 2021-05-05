<section>
  <nav>
  <table><tr>
  <td style="width: 33.3%;"></td>
  <td  style="width: 33.3%;">
    <a href="<?=$this->baseurl?>?cmd=topics">Lista tematów</a>
  </td>
  <td  style="width: 33.3%;"></td>
  </tr></table>
  </nav>

  <article  class="topic">
    <header>Temat dyskusji: <b><?=htmlentities($topic['topic'])?></b></header>
    <div><?=nl2br(htmlentities($topic['topic_body']))?></div>
    <footer>
    ID: <?=$topic['topicid']?>, Autor: <?=htmlentities($users[$topic['userid']]['username'])?>, Data: <?=$topic['date']?>
    </footer>
  </article>
<?php if( !$posts ){ ?>
  <p>To forum nie zawiera jeszcze żadnych głosów w dyskusji!</p>
<?php }else{ ?>
<?php foreach($posts as $k=>$v){ ?>
  <article class="post">
  <div><?=nl2br(htmlentities($v['post']))?><br />
  <?php if($images){ 
    foreach($images as $imgid=>$img){ 
      if($img["postid"]!=$v["postid"]) continue; 
    ?>
  <div class="image">
  <img src="files/<?=$imgid.$img['sufix']?>" /><br />
  <?=$img["title"]?><br />
  <?php if($this->u['userid']==$v['userid'] || $this->u['userlevel']==10) { ?>
  <a href="?cmd=imgedit&imgid=<?=$img["id"]?>&postid=<?=$img["postid"]?>" ?>EDYTUJ</a> 
  <a href="?cmd=imgdelete&imgid=<?=$img["id"]?>" ?>KASUJ</a>
  <?php } ?>
  </div>
  <?php } 
    } ?>
  </div>
  <footer>
  <nav>
  <?php if( $this->u['userlevel']==10 || $this->u['userid']==$v['userid']){ ?>
  <a href="?id=<?=$v['postid']?>&cmd=edit">EDYTUJ</a>  
  <a href="?&id=<?=$v['postid']?>&cmd=delete">KASUJ</a>
  <form method="post" enctype="multipart/form-data">
  <?php if(!isset($_SESSION["imgedit"]) or $_SESSION['postid']!=$v['postid']){ ?> 
  <input type="file" name="image" /> 
  <?php }else{ ?>
  Nowy opis ilustracji: 
  <?php } ?>
  <input type="text" name="imagetitle" value="<?=(isset($_SESSION["imgedit"]) and $_SESSION['postid']==$v['postid'])?$images[$_SESSION["imgid"]]["title"]:"";?>" placeholder="Opis pliku" /> <button type="submit" >Zapisz</button>
  <input type="hidden" name="postid" value="<?=$v['postid']?>" />
  </form>
  <?php } ?>
  </nav>
  ID: <?=$v['postid']?>, Autor: <?=htmlentities($users[$v['userid']]['username'])?>, Utworzono dnia: <?=$v['date']?>
  <div style="clear:both;"></div>    
  </footer>
  </article>
<?php } } ?>

  <form action="<?=$this->baseurl?>" method="post" enctype="multipart/form-data">
     <a name="post_form" ></a>
     <header><h2><?php if($post){ ?>Edytuj wypowiedź<?php }else{ ?>Dodaj nowa wypowiedź do dyskusji<?php } ?></h2></header>  
     <textarea name="post" autofocus cols="80" rows="10" placeholder="Wpisz tu swoją wypowiedź." ><?=($post)?$post["post"]:'';?></textarea><br />
     <input type="hidden" name="postid" value="<?=($post)?$post["postid"]:"";?>" />
     <button type="submit" >Zapisz</button>
  </form>
  <!--
  <p>$images: <?=print_r($images,true)?></p>
  <p>$_SESSION: <?=print_r($_SESSION,true)?></p>
  <p>x: <?=print_r($images[$_SESSION["imgid"]],true)?></p>
  -->
</section>
