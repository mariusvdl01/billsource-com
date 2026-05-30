<?php

namespace common\models;

use Yii;
use common\helpers\Billsource;
use common\models\invoice\Invoice;
use common\models\individual\IndividualClient;
use yii\web\UploadedFile;

class Vault extends \yii\base\Model
{
    const VAULT_DIR = '@frontend/vault';
    /**
     * @var integer $status_id the status of the invoice
     */
    public $status_id;
    /**
     * @var string $issueDate the issue date of invoice
     */
    public $issue_date;
    /**
     * @var string $dueDate the due date of invoice
     */
    public $due_date;
    /**
     * @var string $businessName the business being issued the invoice
     */
    public $business_name;
    /**
     * @var float $amount the total amount
     */
    public $amount;
    /**
     * @var string $terms the terms of payment
     */
    public $terms;
    /**
     * @var UploadedFile | \yii\web\UploadedFile
     */
    public $invoice_file;

    public function rules()
    {
        return [
            [['status_id', 'issue_date', 'due_date', 'invoice_file',
                'business_name', 'amount', 'terms'], 'required'],
            ['status_id', 'integer'],
            [['issue_date', 'due_date'], 'date', 'format' => 'yyyy-mm-dd'],
            [['invoice_file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf', 'maxSize' => 2097152],
        ];
    }

    public function upload($user_id)
    {
        if ($this->validate() && $this->_vaultInsert($user_id)) {
            $this->invoice_file->saveAs(Yii::getAlias(self::VAULT_DIR . '/') .
                $this->invoice_file->baseName . '.' . $this->invoice_file->extension);

            return true;
        } else {
            return false;
        }
    }

    protected function _vaultInsert($user_id, $invoice_type = 'INV')
    {
        $user = User::findOne(['id' => $user_id]);
        $client = IndividualClient::findOne(['user_id' => $user->id]);
        if ($user) {
            $invoice = Invoice::findOne(['pdf' => $this->invoice_file->name]);
            if ($invoice === null) {
                $invoice = new Invoice();
                $invoice->client_id = $client->id_number;
                $invoice->status_id = $this->status_id;
                $invoice->business_id = '0';
                $invoice->paid = '0';
                $invoice->reference_number = Billsource::getReferenceNumber();
                $invoice->pdf = $this->invoice_file->name;
                $invoice->type = $invoice_type;
                $invoice->issue_date = $this->issue_date;
                $invoice->due_date = $this->due_date;
                $invoice->amount = round(floatval($this->amount), 2);
                $invoice->vat = round(($invoice->amount * .14), 2);
                $invoice->total = round(($invoice->amount * 1.14), 2);
                $invoice->client_email = $user->email;
                $invoice->comments = $this->terms;
                $invoice->alt_business_name = $this->business_name;
                if ($invoice->save(false)) {
                    return true;
                }
            }
        }

        return false;
    }
}