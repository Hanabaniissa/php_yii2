<?php

/** @var \app\models\Assign[] $assigns */

use app\models\Option;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

?>


<div class="row">
    <?php foreach ($assigns as $assign):
        $options = Option::getOptionByFieldQuery($assign['field_id'])->all(); ?>
        <div class="col-md-3" style="margin-bottom: 10px;">
            <label for="flieldSelect" style="margin-bottom: 4px"><?= $assign['label_en'] ?></label>
            <?= Html::dropDownList("PostField[{$assign['field_id']}]", null, ArrayHelper::map($options, 'id', 'label_en'), ['class' => 'form-select form-control options', 'prompt' => 'Select...', 'data-role' => "field", 'data-field' => $assign['field_id']]) ?>
        </div>

        <br>
    <?php endforeach; ?>
</div>


<?php
$this->registerJs(<<<JS

$('[data-role="field"]').on('click',function (){
    const fieldId=$(this).attr('data-field');
    //const option=$(this).find();
   
    $.ajax({
    url:'http://yii2-basic.local/option/get',
    data:{
        field_id:fieldId
        },
        
        success:function (response){
            if (!response)
                return false;
            option.html('');
            option.append('<option selected>Select...</option>');
            response.forEach((item)=>{
                option.append('<option value=' + item.id + '>'+ item.label_en + '</option>');
            }) 
        }
    }) 
})
JS
)

?>
