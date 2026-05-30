<?php

namespace common\models;

use common\helpers\Billsource;
use common\Registry;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;

/**
 * Base Model class. This class defines common attributes, behavoirs, and traits common
 * across all AR models
 *
 * Utilizes the Active Record pattern to handle database access on a row by row basis.
 *
 * @author Kenneth Onah
 * @license 
 * @link 
 */
abstract class BaseActiveRecord extends \yii\db\ActiveRecord
{
    const ROLE_READER = 'reader';
    const ROLE_LOADER = 'loader';
    const ROLE_INDIVIDUAL = 'individual';
    const ROLE_BUSINESS_ADMIN = 'businessAdmin';
    const ROLE_SINGLEUSER_ADMIN = 'singleUserAdmin';

    const IMAGE_DIR = 'uploads/media';
    const EXPECTED_FIELD = '16';

    protected $roles = [];
    protected $authMan = null;

    public function init()
    {
        parent::init();

        $this->authMan = Yii::$app->getAuthManager();
    }

    /**
     * Behaviors rules to initialize safe variables (such as created_at, updated_at)
     * prior to saving user's data.
     *
     * @return array | array of behaviors
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => function () {
                    $now = new \DateTime();
                    return $now->format('Y-m-d H:i:s');
                },
            ],
        ];
    }

    /**
     * Get assigned user roles
     *
     * @return array|\yii\rbac\Role[]
     */
    public function getRoles()
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();

        if (isset($roles) && is_array($roles)) {
            foreach (array_keys($roles) as $role) {
                if (!in_array($role, $this->roles))
                    unset($roles[$role]);
            }
        } else {
            $roles = $this->roles;
        }

        return $roles;
    }

    /**
     * Validates user role
     *
     * @param int $user_id user ID
     *
     * @return bool if role exists
     */
    public function checkUserInRole($user_id)
    {
        $auth = Yii::$app->authManager;
        if($auth) {
            if($roles = $auth->getRolesByUser($user_id)) {
                foreach ($roles as $role) {
                    if(in_array($role->name, $this->roles))
                        return true;
                }
            }
        }
        return false;
    }

    /**
     * Transform and save uploaded user logo/image
     */
    protected function uploadLogo()
    {
        $imageAttr = 'photo';
        $user = Registry::registry('user');
        if($user->business_user)
            $imageAttr = 'business_logo';

        // directory random number generator. This helps to prevent the
        // image directory from being to big.
        $rand = rand(0, 9);
        $oldImage = $this->getOldAttribute($imageAttr);

        $path = Yii::$app->basePath . DIRECTORY_SEPARATOR . self::IMAGE_DIR;
        $image = UploadedFile::getInstance($this, $imageAttr);
        if (!is_null($image)) {
            $ext = end(explode('.', $image->name));
            $this->$imageAttr = Yii::$app->security->generateRandomString(8) . ".{$ext}";

            $imageDir = $path . DIRECTORY_SEPARATOR . $rand;
            if (!file_exists($imageDir)) {
                mkdir($imageDir, 0777, true);
            }
            $imageFile = $imageDir . DIRECTORY_SEPARATOR . $this->$imageAttr;
            $image->saveAs($imageFile);

            $oldLogo = $path . DIRECTORY_SEPARATOR . $oldImage;
            if (!empty($oldImage) && file_exists($oldLogo)) {
                unlink($oldLogo);
            }

            // Image transformation
            $image = Billsource::transformImage($image, $path, $imageFile, $rand);
            $this->$imageAttr = $rand . DIRECTORY_SEPARATOR . basename($image->target_path);
        } else {
            $this->$imageAttr = $oldImage;
        }
    }
}