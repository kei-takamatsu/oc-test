<?php
$this->Csv->addRow($th);
foreach($backers as $b){
    $this->Csv->addField(h($b['User']['nick_name']));
    $this->Csv->addField(h($b['User']['name']));
    $this->Csv->addField(h($b['User']['email']));
    $this->Csv->addField(h($b['User']['receive_address']));
    $this->Csv->addField(h($b['BackedProject']['created']));
    $this->Csv->addField(number_format(h($b['BackedProject']['invest_amount'])).'円');
    $this->Csv->addField(h($b['BackingLevel']['name']));
    $this->Csv->addField(h($b['BackingLevel']['return_amount']));
    $pay = $b['BackedProject']['manual_flag'] ? '手動入金' : 'カード決済';
    $this->Csv->addField($pay);
    $this->Csv->addField(h($b['BackedProject']['comment']));
    $this->Csv->endRow();
}
$this->Csv->setFilename($filename);
echo $this->Csv->render(true, 'sjis', 'utf-8');
//echo $this->Csv->render();
