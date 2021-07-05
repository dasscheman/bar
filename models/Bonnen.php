<?php

namespace app\models;

use Yii;
use app\models\BarActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "bonnen".
 *
 * @property integer $bon_id
 * @property string $omschrijving
 * @property string $image
 * @property integer $type
 * @property string $datum
 * @property string $bedrag
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $inkoper_user_id
 * @property integer $soort
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property User $inkoperUser
 * @property Inkoop[] $inkoops
 * @property Transacties[] $transacties
 */
class Bonnen extends BarActiveRecord
{
    public $image_temp;

    const TYPE_declaratie = 1;
    const TYPE_pin_betaling = 2;
    const TYPE_overschrijving = 3;

    const SOORT_consumpties = 1;
    const SOORT_materiaal = 2;
    const SOORT_Verbruiksartikelen = 3;
    const SOORT_overige = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bonnen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['omschrijving', 'image', 'type', 'datum', 'bedrag'], 'required'],
            [['soort'], 'required', 'when' => function($model) {
                if (Yii::$app->controller->id == 'transacties' &&
                    Yii::$app->controller->action->id =='update') {
                        return false;
                }
                return true;
            }, 'enableClientValidation' => false
            ],
            [['type', 'created_by', 'updated_by', 'inkoper_user_id', 'soort'], 'integer'],
            [['bon_id', 'datum', 'created_at', 'updated_at'], 'safe'],
            [['bedrag'], 'number'],
            [['omschrijving', 'image'], 'string', 'max' => 255],
            [['image_temp'],'file', 'extensions'=>'jpg, gif, png, jpeg, pdf', 'maxSize'=>1024 * 1024 * 2],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['inkoper_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['inkoper_user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bon_id' => 'Bon nummer',
            'omschrijving' => 'Omschrijving',
            'image' => 'Image',
            'type' => 'Type',
            'datum' => 'Datum',
            'bedrag' => 'Bedrag',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'inkoper_user_id' => 'Inkoper User ID',
            'soort' => 'Soort',
            'image_temp' => 'PDF of jpg bon'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInkoperUser()
    {
        return $this->hasOne(User::className(), ['id' => 'inkoper_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInkoops()
    {
        return $this->hasMany(Inkoop::className(), ['bon_id' => 'bon_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSumInkoop()
    {
        return $this->hasMany(Inkoop::className(), ['bon_id' => 'bon_id'])
            ->sum('totaal_prijs');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKostens()
    {
        return $this->hasMany(Kosten::className(), ['bon_id' => 'bon_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSumKosten()
    {
        return $this->hasMany(Kosten::className(), ['bon_id' => 'bon_id'])
            ->sum('prijs');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransacties()
    {
        return $this->hasMany(Transacties::className(), ['bon_id' => 'bon_id']);
    }

    /**
     *
     * Retrieves a list of statussen
     * @return array an array of available statussen.
     */
    public function getTypeOptions() {
        return [
            self::TYPE_declaratie => Yii::t('app', 'Declaratie'),
            self::TYPE_pin_betaling => Yii::t('app', 'Pin betaling'),
            self::TYPE_overschrijving => Yii::t('app', 'Bank overschrijving'),
        ];
    }

    /**
     * @return string the status text display
     */
    public function getTypeText() {
        $typeOptions = $this->typeOptions;
        if (isset($typeOptions[$this->type])) {
            return $typeOptions[$this->type];
        }
        return "unknown status ({$this->type})";
    }

    /**
     * Retrieves a list of statussen
     * @return array an array of available statussen.
     */
    public function getSoortOptions() {
        return [
            self::SOORT_consumpties => Yii::t('app', 'Consumpties'),
            self::SOORT_materiaal => Yii::t('app', 'Materiaal'),
            self::SOORT_Verbruiksartikelen => Yii::t('app', 'Verbruiksartikelen'),
            self::SOORT_overige => Yii::t('app', 'Overige'),
        ];
    }

    /**
     * @return string the status text display
     */
    public function getSoortText() {
        $statusOptions = $this->soortOptions;
        if (isset($statusOptions[$this->soort])) {
            return $statusOptions[$this->soort];
        }
        return "unknown status ({$this->soort})";
    }

    /**
    * Retrieves a list of users
    * @return array an of available relatedtransactions.'.
    */
    public function getBonnenArray()
    {
        $result = Bonnen::find()->all();
        $arrayRestuls = ArrayHelper::map($result, 'bon_id',
            function($model) {
                return $model['omschrijving'].' ('.$model['bedrag'] . ')';
            }
        );
        return $arrayRestuls;
    }

    public function saveBonForTransactie($model)
    {
        if ($this->soort == null) {
            $this->soort = Bonnen::SOORT_overige;
        }
        $image = UploadedFile::getInstance($this, 'image_temp');
        if($this->bon_id != null || $this->bon_id != '' ) {
            if($image) {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Er is een bestaande bon gelinkt, het geladen bestand is genegeerd!'));
            }
            $model->bon_id = $this->bon_id;
            $model->save();
            return;
        }

        // get the uploaded file instance. for multiple file uploads
        // the following data will return an array
        if(!$image) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Geen bon aanwezig '));
            return;
        }
        // store the source file name
        $this->image = date('Y-m-d H:i:s') . '-' . $image->name;
        $this->bedrag = $model->bedrag;
        $this->datum = $model->datum;
        $this->type = Bonnen::TYPE_pin_betaling;
        $this->omschrijving = $model->omschrijving;
        $path = Yii::$app->params['bonnen_path'] . $this->image;
        if ($this->save()) {
            $image->saveAs($path);
            $model->bon_id = $this->bon_id;
            $model->save();
            return;
        }
        foreach ($this->errors as $key => $error) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Fout met opslaan van de Bon: ' . $key . ':' . $error[0]));
        }
    }
}
