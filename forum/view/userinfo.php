<section class="user-info"><?php if($this->u['userlevel']==10){ ?>
<div>
<?php if($this->context=="images" or $this->context=="userlist"){?><a href="?cmd=topics">Tematy</a><?php } ?>
<?php if($this->context=="topics"){?><a href="?cmd=userlist">Lista uczestników</a><?php } ?>
<?php if($this->context=="topics" or $this->context=="userlist"){?><a href="?cmd=images">Lista obrazkow</a><?php } ?>
</div>
<?php } ?>
Zalogowany jako: <?=$this->u['username'];?> (<?=$this->u['userid'];?>) <a href="?cmd=logout" >WYLOGUJ</a>
<?php if(isset($_SESSION['userlist'])){ ?>
<br />
<table><tr><th>Identyfikator</th><th>Nazwa</th><th>Poziom</th><th></th></tr>
<?php foreach($users as $k=>$v){ ?>
<tr>
<td><?=$v['userid']?></td>
<td><?=$v['username']?></td>
<td><?=($v['userlevel']==10)?'admin':'user';?></td>
<td><?php if($v['userid']!='admin'){ ?>
<a href="?cmd=changeuser&userid=<?=$v['userid']?>">Zmień</a>&nbsp;
<a href="?cmd=deluser&userid=<?=$v['userid']?>">Kasuj</a>
<?php } ?></td>
</tr>
<?php } ?>
</table>
<?php } ?>
</section>
