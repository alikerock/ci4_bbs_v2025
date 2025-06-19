
<?php

   // print_r($pager_links);

?>
    <h2>Board List</h2>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">제목</th>
                <th scope="col">날짜</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach($list as $l){             
            ?>
            <tr>
                <th scope="row"><?= $l->bid; ?></th>
                <td><a href="boardView/<?= $l->bid; ?>"><?= $l->subject; ?></a></td>
                <td><?= $l->regdate; ?></td>
            </tr>
            <?php
                }
            ?>            
          
        </tbody>
    </table>
    <?= $pager_links ;?>
    