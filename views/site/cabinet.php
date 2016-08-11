<?php

use yii\helpers\Html;


$this->title = 'Cabinet';
?>

<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
    <br>
    <p class="">Добрый день, <?=$user->username;?>!</p>
    <?=
        Html::beginForm(['/site/logout'], 'post', ['class' => 'navbar-form'])
        . Html::submitButton(
            'Выход',
            ['class' => 'btn btn-success']
        )
        . Html::endForm();
    ?>

</div>