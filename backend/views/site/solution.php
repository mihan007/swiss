<?php $this->pageTitle = 'Solution - ' . Yii::app()->name; ?>

<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'profileSelector',
    'htmlOptions'=>array('class'=>'well'),
)); ?>

    <?php echo $form->dropDownListRow($model, 'id', Profile::getAllPossible()); ?><br/>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Submit ')); ?>

<?php $this->endWidget(); ?>

<?php if (isset($directFriends)): ?>
    <h2>Direct Friends Of <?php echo $model->fullName; ?></h2>
    <?php $this->renderPartial('_friends', array('friends'=>$directFriends)); ?>
<?php endif ?>

<?php if (isset($friendsOfFriends)): ?>
    <h2>Friends Of Friends of <?php echo $model->fullName; ?></h2>
    <?php $this->renderPartial('_friends', array('friends'=>$friendsOfFriends)); ?>
<?php endif ?>

<?php if (isset($suggestedFriends)): ?>
    <h2>Suggested Friends of <?php echo $model->fullName; ?></h2>
    <?php $this->renderPartial('_friends', array('friends'=>$suggestedFriends)); ?>
<?php endif ?>