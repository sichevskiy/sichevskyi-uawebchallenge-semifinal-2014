<h2><?= $clan1Name ?></h2>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => $clan1DataProvider,
    'template' => "{items}",
    'columns' => array(
        array(
            'name' => 'accountName',
            'header' => 'Account Name',
            'type' => 'raw',
            'value' => '$data["accountName"]',
        ),
        array(
            'name' => 'mom',
            'header' => 'Mark of Mastery',
            'type' => 'raw',
            'value' => '$data["mom"]',
        ),
        array(
            'name' => 'name',
            'header' => 'Tank',
            'type' => 'raw',
            'value' => '$data["name"]',
        ),
        array(
            'name' => 'level',
            'header' => 'Level',
            'type' => 'raw',
            'value' => '$data["level"]',
        ),
    ),
)); ?>

<h2><?= $clan2Name ?></h2>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => $clan2DataProvider,
    'template' => "{items}",
    'columns' => array(
        array(
            'name' => 'accountName',
            'header' => 'Account Name',
            'type' => 'raw',
            'value' => '$data["accountName"]',
        ),
        array(
            'name' => 'mom',
            'header' => 'Mark of Mastery',
            'type' => 'raw',
            'value' => '$data["mom"]',
        ),
        array(
            'name' => 'name',
            'header' => 'Tank',
            'type' => 'raw',
            'value' => '$data["name"]',
        ),
        array(
            'name' => 'level',
            'header' => 'Level',
            'type' => 'raw',
            'value' => '$data["level"]',
        ),
    ),
)); ?>