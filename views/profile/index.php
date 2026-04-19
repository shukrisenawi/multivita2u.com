<?php

$this->title = "Profile";
$this->params['breadcrumbs'][] = $this->title;
?>
<?=

    $this->render('/user/_form', [
        'model' => $model,
    ])
?>
