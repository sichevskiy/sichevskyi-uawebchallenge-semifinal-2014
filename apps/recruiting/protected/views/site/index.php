<?php
/* @var $this SiteController */

/* @var $form TbActiveForm
 *
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

    <div>
        LinkedIn
    </div>
    <div class="row well">
        <?php echo $form->textFieldRow($model, 'firstName'); ?>
        <?php echo $form->textFieldRow($model, 'lastName'); ?>
        <?php echo $form->textFieldRow($model, 'phone'); ?>
    </div>

    <div>
        GitHub
    </div>
    <div class="row well">
        <?php echo $form->textFieldRow($model, 'username'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Search', array('class' => 'btn btn-primary')); ?>
    </div>

    <img id="loading" style="display: none" src="/images/loading.gif">


    <?php $this->endWidget(); ?>

</div><!-- form -->