<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;

abstract class BarActiveRecord extends ActiveRecord
{
    const STATUS_ingevoerd = 1;
    const STATUS_gecontroleerd = 2;
    const STATUS_tercontrole = 3;
    const STATUS_factuur_gegenereerd = 4;
    const STATUS_factuur_verzonden = 5;
    const STATUS_herberekend = 6;
    const STATUS_teruggestord = 97;
    const STATUS_geannuleerd = 98;
    const STATUS_ongeldig = 99;

    /**
    * Attaches the timestamp behavior to update our create and update times
    */
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => function () {
                    return Yii::$app->setupdatetime->storeFormat(time(), 'datetime');
                },
            ],
        ];
    }

    /**
     * Retrieves a list of statussen
     * @return array an array of available statussen.
     */
    public function getStatusOptions()
    {
        return [
            self::STATUS_ingevoerd => 'Ingevoerd',
            self::STATUS_gecontroleerd => 'Gecontroleerd',
            self::STATUS_tercontrole => 'Tercontrole',
            self::STATUS_factuur_gegenereerd => 'Factuur gegenereerd',
            self::STATUS_factuur_verzonden => 'Factuur verzonden',
            self::STATUS_herberekend => 'Herberekend',
            self::STATUS_teruggestord => 'Betaling teruggestord',
            self::STATUS_geannuleerd => 'Geannuleerd',
            self::STATUS_ongeldig => 'Ongeldige transactie',
        ];
    }

    /**
     * @return string the status text display
     */
    public function getStatusText()
    {
        $statusOptions = $this->statusOptions;
        if (isset($statusOptions[$this->status])) {
            return $statusOptions[$this->status];
        }
        return "Onbekende status ({$this->status})";
    }

    /**
     * Used to send een email with errors to admin.
     * Use this only for critical processe, for example the mollie webhook.
     */
    public function sendErrorReport($errors = null)
    {
        if ($errors === null) {
            $errors = $this->errors;
        }
        $message = Yii::$app->mailer->compose('mail_critical_error', [
                'errors' => $errors,
                'model' => $this,
            ])
            ->setFrom($_ENV['ADMIN_EMAIL'])
            ->setTo('daan@biologenkantoor.nl')
            ->setSubject('Critical Error Bison bar');
        $message->send();
    }
}
