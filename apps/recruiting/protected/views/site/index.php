<?php
/* @var $this SiteController
 * @var $form TbActiveForm
 */

$this->pageTitle = Yii::app()->name;

?>

    <div class="form">
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'edit-form',
            'htmlOptions' => array(
                'class' => 'well',
                'onsubmit' => '$("#loading").show()',
            ),
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
        )); ?>

        <p class="note">Fields with <span class="required">*</span> are required.</p>

        <div class="row">
            <?php echo $form->textFieldRow($model, 'firstName'); ?>
            <?php echo $form->textFieldRow($model, 'lastName'); ?>
            <?php echo $form->textFieldRow($model, 'email'); ?>
            <?php echo $form->textFieldRow($model, 'phone'); ?>
            <?php echo $form->textFieldRow($model, 'username'); ?>
        </div>

        <div class="row buttons">
            <?php echo CHtml::submitButton('Show', array('class' => 'btn btn-primary')); ?>
            <?php echo CHtml::submitButton('Export to CSV', array('class' => 'btn btn-primary')); ?>
        </div>

        <?php $this->endWidget(); ?>

    </div><!-- form -->

<?php if (!empty($commits)) { ?>
    <h2>GitHub latest activity:</h2>
    <?php
    $this->widget('bootstrap.widgets.TbGridView', array(
        'type' => 'striped bordered condensed',
        'dataProvider' => $commits,
        'template' => "{items}",
        'columns' => array(
            array(
                'name' => 'date',
                'header' => 'Date',
                'type' => 'raw',
                'value' => 'date("Y-m-d",$data["date"])',
            ),
            array(
                'name' => 'count',
                'header' => 'Count of commits',
                'type' => 'raw',
                'value' => '$data["count"]',
            ),
        ),
    ));

    $this->widget('jqBarGraph', array('values' => $graphData,
            'type' => 'simple',
            'width' => 1000,
            'color1' => '#122A47',
            'color2' => '#1B3E69',
            'space' => 5,
            'title' => 'simple graph')
    );
    ?>

<?php } ?>