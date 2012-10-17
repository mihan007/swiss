<?php $this->pageTitle = 'Solution - ' . Yii::app()->name; ?>

<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'profileSelector',
    'htmlOptions'=>array('class'=>'well'),
)); ?>

    <?php echo $form->dropDownListRow($model, 'id', Profile::getAllPossible()); ?><br/>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Submit ')); ?>

<?php $this->endWidget(); ?>

<?php if($this->beginCache('latestPost', array('dependency'=>$model->cacheFriendDependency))) { ?>
    <?php if ($model->id>0): ?>
        <h2>Direct Friends Of <?php echo $model->fullName; ?></h2>
        <?php $this->renderPartial('_friends', array('friends'=>$model->directFriends)); ?>
    <?php endif ?>
<?php $this->endCache(); } ?>

<?php if($this->beginCache('latestPost', array('dependency'=>$model->cacheFriendDependency))) { ?>
    <?php if ($model->id>0): ?>
        <h2>Friends Of Friends of <?php echo $model->fullName; ?></h2>
        <?php $this->renderPartial('_friends', array('friends'=>$model->friendsOfFriends)); ?>
    <?php endif ?>
<?php $this->endCache(); } ?>

<?php if($this->beginCache('latestPost', array('dependency'=>$model->cacheFriendDependency))) { ?>
    <?php if ($model->id>0): ?>
        <h2>Suggested Friends of <?php echo $model->fullName; ?></h2>
        <?php $this->renderPartial('_friends', array('friends'=>$model->suggestedFriends)); ?>
    <?php endif ?>
<?php $this->endCache(); } ?>