<?php
/* @var $this SiteController */

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
        <?php echo $form->dropDownListRow($model, 'clan1', array(null => null) + $list); ?>

        <?php echo $form->dropDownListRow($model, 'clan2', array(null => null) + $list); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Balance', array('class' => 'btn btn-primary')); ?>
    </div>

    <img id="loading" style="display: none" src="/images/loading.gif">


    <?php $this->endWidget(); ?>

</div><!-- form -->


<script>
    $('#BalanceForm_clan1').change(function (event) {
        $("#BalanceForm_clan2 option").removeAttr('disabled');
        $("#BalanceForm_clan2 option[value=" + $(this).val() + "]").attr('disabled', 'disabled')
    });

    $('#BalanceForm_clan2').change(function (event) {
        $("#BalanceForm_clan1 option").removeAttr('disabled');
        $("#BalanceForm_clan1 option[value=" + $(this).val() + "]").attr('disabled', 'disabled')
    });
</script>
