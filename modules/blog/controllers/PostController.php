<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 6/13/16
 * Time: 10:37 PM
 */

namespace app\modules\blog\controllers;

use app\modules\blog\models\Post;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class PostController
 * @package app\modules\blog\controllers
 */
class PostController extends Controller
{
    /**
     * @return array
     */
    public function actions()
    {
        return [
            'options' => ['class' => 'yii\rest\OptionsAction']
        ];
    }
    
    /**
     * @param null $title
     * @param null $content
     * @return ActiveDataProvider
     */
    public function actionIndex($title = null, $content = null)
    {
        $q = Post::find()
            ->andFilterWhere(['like', 'title', $title])
            ->andFilterWhere(['like', 'content', $content]);

        return new ActiveDataProvider(['query' => $q]);
    }

    /**
     * @return Post
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $model = new Post();
        $model->load(\Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->save()) {
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(201);
            $response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $model->id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $model;
    }

    /**
     * @param $id
     * @return Post
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        /** @var Post $model */
        if (!$model = Post::findOne($id))
            throw new NotFoundHttpException("Object not found: $id");
        return $model;
    }

    /**
     * @param $id
     * @return Post
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate($id)
    {
        /** @var Post $model */
        if (!$model = Post::findOne($id))
            throw new NotFoundHttpException("Object not found: $id");

        $model->load(\Yii::$app->request->getBodyParams(), '');
        if (!$model->save() && !$model->hasErrors())
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');

        return $model;
    }

    /**
     * @param $id
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \Exception
     */
    public function actionDelete($id)
    {
        /** @var Post $model */
        if (!$model = Post::findOne($id))
            throw new NotFoundHttpException("Object not found: $id");

        if ($model->delete() === false)
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');

        \Yii::$app->getResponse()->setStatusCode(204);
    }
}