<?php
/* @var $this yii\web\View */
$this->title = Yii::t('app','My Application in Gen');
?>
<div class="site-index">

    <div class="jumbotron">
        <h1><?=Yii::t('app','Welcome to come here!')?></h1>

        <p class="lead">You have successfully created your Gen-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="<?=\yii\helpers\Url::to(['/backend'])?>"><?=Yii::t('app','Getting start!')?></a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2><?=Yii::t('app','Contest of efficiency and quality')?></h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

            </div>
            <div class="col-lg-4">
                <h2><?=Yii::t('app','Automation module management')?></h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

            </div>
            <div class="col-lg-4">
                <h2><?=Yii::t('app','Rich module library')?></h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

            </div>
        </div>

    </div>
</div>
