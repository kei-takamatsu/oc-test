<ul class="pagination">
    <?php
    echo '<li>'.$this->Paginator->prev(__('◀︎').' ', array(), null, array('class' => 'prev disabled')).'</li>';
    echo '<li>'.$this->Paginator->numbers(array('separator' => '</li><li>')).'</li>';
    echo '<li>'.$this->Paginator->next(__('▶︎').' ', array(), null, array('class' => 'next disabled')).'</li>';
    ?>
</ul>
