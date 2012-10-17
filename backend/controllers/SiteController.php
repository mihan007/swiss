<?php
/**
 * SiteController.php
 *
 * @author: antonio ramirez <antonio@clevertech.biz>
 * Date: 7/24/12
 * Time: 1:21 AM
 */

class SiteController extends Controller
{
    /**
     * @return array list of action filters (See CController::filter)
     */
    public function filters()
    {
        return array('accessControl');
    }

    /**
     * @return array rules for the "accessControl" filter.
     */
    public function accessRules()
    {
        return array(
            array('allow', // Allow registration form for anyone
                'actions' => array('index', 'solution', 'error'),
            ),
            array('allow', // Allow all actions for logged in users ("@")
                'users' => array('@'),
            ),
            array('deny'), // Deny anything else
        );
    }

    /**
     * Renders index page
     */
    public function actionIndex()
    {
        $this->render('index');
    }

    /**
     * Action to render the error
     * todo: design proper error page
     */
    public function actionError()
    {
        if ($error = app()->errorHandler->error)
        {
            if (app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    public function actionSolution()
    {
        $profile = new Profile();
        if (isset($_POST['Profile']))
        {
            $profile = $this->loadModel($_POST['Profile']);
            $this->render('solution', array(
                'model' => $profile,
            ));
        }
        else
            $this->render('solution', array('model' => $profile));
    }

    private function loadModel($row)
    {
        if (!isset($row['id']))
            throw new CException(400, 'Bad request');
        $id = $row['id'];
        //to avoid extra queries
        $dependency = new CDbCacheDependency('SELECT updated FROM profile WHERE id=\''.$id.'\'');
        $profile = Profile::model()->cache(param('cache.duration'), $dependency)->findByPk($id);
        if (!$profile)
            throw new CException(404, 'Not found');
        return $profile;
    }
}