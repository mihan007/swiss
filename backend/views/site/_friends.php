<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',
    'dataProvider'=>$friends,
    'template'=>"{items}",
    'columns'=>array(
        array('name'=>'id', 'header'=>'#'),
        array('name'=>'fullName', 'header'=>'Name'),
        array('name'=>'age', 'header'=>'Age'),
        array('name'=>'gender', 'header'=>'Gender'),
    ),
)); ?>