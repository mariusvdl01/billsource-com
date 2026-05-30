<?php

namespace frontend\controllers\business;

use common\controllers\BusinessController;
use common\models\business\BusinessClient as Client;

/**
 * The ecosystem controller class for business authenticated users.
 *
 * @author Kenneth Onah
 *
 */
class EcosystemController extends BusinessController
{
    public $defaultAction = 'dashboard';

    /**
     * Renders business user ecosystem dashboard (home page) after successful login.
     *
     */
    public function actionDashboard()
    {
        $data = $this->client->getProfileData();
        $progress = ceil(($data['completed'] / Client::EXPECTED_FIELD) * 100);

        return $this->render('dashboard', [
            'client' => $this->client,
            'data' => $data,
            'progress' => $progress,
            'ecosystem' => $this->client->hydrateEcosystem()
        ]);

    }
}
