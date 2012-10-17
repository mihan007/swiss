<?php $this->pageTitle = 'Solution - ' . Yii::app()->name; ?>

<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'profileSelector',
    'htmlOptions'=>array('class'=>'well'),
)); ?>

    <?php echo $form->dropDownListRow($model, 'id', Profile::getAllPossible()); ?><br/>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Submit ')); ?>

<?php $this->endWidget(); ?>

<?php if ($model->id>0): ?>
    <?php if($this->beginCache('directFriends', array('dependency'=>$model->cacheFriendDependency($model->id)))) { ?>
    <h2>Direct Friends Of <?php echo $model->fullName; ?></h2>
        <?php $this->renderPartial('_friends', array('friends'=>$model->directFriends)); ?>
    <?php $this->endCache(); } ?>

    <?php if($this->beginCache('friendsOfFriends', array('dependency'=>$model->cacheFriendDependency($model->id)))) { ?>
        <h2>Friends Of Friends of <?php echo $model->fullName; ?></h2>
        <?php $this->renderPartial('_friends', array('friends'=>$model->friendsOfFriends)); ?>
    <?php $this->endCache(); } ?>

    <?php if($this->beginCache('suggestedFriends', array('dependency'=>$model->cacheFriendDependency($model->id)))) { ?>
        <h2>Suggested Friends of <?php echo $model->fullName; ?></h2>
        <?php $this->renderPartial('_friends', array('friends'=>$model->suggestedFriends)); ?>
    <?php $this->endCache(); } ?>
<?php endif ?>