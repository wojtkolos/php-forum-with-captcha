<section class="images-table">
<table>
<caption>Lista obrazkow</caption>
<tr><th>Id</th><th>Obrazek</th><th>Uczestnik</th><th>Post</th><th>Nazwa</th><th>Data</th></tr>
<?php if($images) foreach( $images as $k=>$img ){ ?>
    <tr>
    <td><?=$k?></td>    
    <td ><img src="<?="files/" . $img["id"] . $img["sufix"]?>" width="100" height="100"><br /><?=($img["title"]!=""?$img["title"]:"") ?></td>
    <td><?=$img["userid"]?><br />[<?=$users[$img["userid"]]["username"]?>]</td>
    <td><?=$img["postid"]?></td>
    <td><?=$img["name"].$img["sufix"]?></td>
    <td><?=$img["date"]?></td>
    </tr>
<?php } ?>
</table>
</section>
