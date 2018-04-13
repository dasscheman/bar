<?php

namespace app\models;

use Yii;
use app\models\BarActiveRecord;

/**
 * This is the model class for table "assortiment".
 *
 * @property integer $assortiment_id
 * @property string $name
 * @property string $merk
 * @property string $soort
 * @property integer $alcohol
 * @property double $volume
 * @property integer $status
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $change_stock_auto
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property Inkoop[] $inkoops
 * @property Prijstlijst[] $prijstlijsts
 * @property Turven[] $turvens
 */
class Assortiment extends BarActiveRecord
{
    const SOORT_fris = 1;
    const SOORT_bier = 2;
    const SOORT_wijn = 3;
    const SOORT_snack = 4;
    const SOORT_overige = 5;

    const STATUS_beschikbaar = 1;
    const STATUS_niet_beschikbaar = 2;
    const STATUS_tijdelijk_niet_beschikbaar = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'assortiment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'alcohol', 'status', 'soort'], 'required'],
            [['alcohol', 'status', 'created_by', 'updated_by', 'change_stock_auto'], 'integer'],
            [['volume'], 'number'],
            [['name', 'alcohol', 'status', 'created_at', 'updated_at'], 'safe'],
            [['name', 'merk', 'soort'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'assortiment_id' => 'Assortiment ID',
            'name' => 'Name',
            'merk' => 'Merk',
            'soort' => 'Soort',
            'alcohol' => 'Alcohol',
            'volume' => 'Volume (ml)',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'change_stock_auto' => 'Pas voorraad automatisch aan',
        ];
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
    public function getTurvens()
    {
        return $this->hasMany(Turven::className(), ['assortiment_id' => 'assortiment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTotaalTurven()
    {
        return $this->hasMany(Turven::className(), ['assortiment_id' => 'assortiment_id'])
            ->sum('aantal');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpbrengstTurven()
    {
        return $this->hasMany(Turven::className(), ['assortiment_id' => 'assortiment_id'])
            ->sum('totaal_prijs');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInkoops()
    {
        return $this->hasMany(Inkoop::className(), ['assortiment_id' => 'assortiment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTotaalInkoop()
    {
        return $this->hasMany(Inkoop::className(), ['assortiment_id' => 'assortiment_id'])
            ->sum('totaal_prijs');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrijs()
    {
        return $this->hasMany(Prijslijst::className(), ['assortiment_id' => 'assortiment_id'])
            ->andWhere(['<=','from', Yii::$app->setupdatetime->storeFormat(time(), 'date')])
            ->andWhere(['>=','to', Yii::$app->setupdatetime->storeFormat(time(), 'date')]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrijslijsts()
    {
        return $this->hasMany(Prijslijst::className(), ['assortiment_id' => 'assortiment_id']);
    }

    /**
     * @inheritdoc
     * @return AssortimentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AssortimentQuery(get_called_class());
    }

    /**
     * Retrieves a list of statussen
     * @return array an array of available statussen.
     */
    public function getSoortOptions()
    {
        return [
            self::SOORT_fris => Yii::t('app', 'Fris'),
            self::SOORT_bier => Yii::t('app', 'Bier'),
            self::SOORT_wijn => Yii::t('app', 'Wijn'),
            self::SOORT_snack => Yii::t('app', 'Snacks'),
            self::SOORT_overige => Yii::t('app', 'Overige'),
        ];
    }

    /**
     * @return string the status text display
     */
    public function getSoortText()
    {
        $statusOptions = $this->soortOptions;
        if (isset($statusOptions[$this->soort])) {
            return $statusOptions[$this->soort];
        }
        return "unknown status ({$this->soort})";
    }

    /**
     * Retrieves a list of statussen
     * @return array an array of available statussen.
     */
    public function getStatusOptions()
    {
        return [
            self::STATUS_beschikbaar => Yii::t('app', 'Beschikbaar'),
            self::STATUS_niet_beschikbaar => Yii::t('app', 'Niet beschikbaar'),
            self::STATUS_tijdelijk_niet_beschikbaar => Yii::t('app', 'Tijdelijk niet beschikbaar'),
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
        return "unknown status ({$this->status})";
    }

    /**
     * Get the assortiment name based on id
     *
     * @param int $id Assortiment id.
     * @return string Name of assortiment.
     */
    public function getAssortimentName($id)
    {
        if (($model = self::findOne($id)) !== null) {
            return $model->name;
        }
        
        return false;
    }

    public function getSumMonthlyTurven($date)
    {
        $inkomsten = (float) Turven::find()
            ->where('month(datum) = month(' . $date . ')')
            ->andWhere('year(datum) = year(' . $date . ')')
            ->andWhere('assortiment_id = ' . $this->assortiment_id)
            ->sum('totaal_prijs');

        return $inkomsten;
    }

    public function getCountMonthlyTurven($date)
    {
        $aantal = (float) Turven::find()
            ->where('month(datum) = month(' . $date . ')')
            ->andWhere('year(datum) = year(' . $date . ')')
            ->andWhere('assortiment_id = ' . $this->assortiment_id)
            ->sum('aantal');
        return $aantal;
    }

    public function getvolumeMonthlyTurven($date)
    {
        $aantal = $this->getCountMonthlyTurven($date);
        $volume = (float) $aantal * (float) $this->volume / (float) 1000;
        return $volume;
    }

    public function getSumMonthlyLoss($date)
    {
        $afgeschreven = (float) Inkoop::find()
            ->where('month(datum) = month(' . $date . ')')
            ->andWhere('year(datum) = year(' . $date . ')')
            ->andWhere('assortiment_id = ' . $this->assortiment_id)
            ->andWhere('status = ' . Inkoop::STATUS_afgeschreven)
            ->sum('totaal_prijs');
        return $afgeschreven;
    }

    public function getVolumeMonthlyLoss($date)
    {
        $afgeschreven = (float) Inkoop::find()
            ->where('month(datum) = month(' . $date . ')')
            ->andWhere('year(datum) = year(' . $date . ')')
            ->andWhere('assortiment_id = ' . $this->assortiment_id)
            ->andWhere('status = ' . Inkoop::STATUS_afgeschreven)
            ->sum('volume');
        return $afgeschreven;
    }

    public function getCountMonthlyLoss($date)
    {
        $inkoop = (float) Inkoop::find()
            ->where('month(datum) = month(' . $date . ')')
            ->andWhere('year(datum) = year(' . $date . ')')
            ->andWhere('assortiment_id = ' . $this->assortiment_id)
            ->andWhere('status = ' . Inkoop::STATUS_afgeschreven)
            ->sum('aantal');

        return $inkoop;
    }

    public function getSumMonthlyVerkocht($date)
    {
        $inkoop = (float) Inkoop::find()
            ->where('month(datum) = month(' . $date . ')')
            ->andWhere('year(datum) = year(' . $date . ')')
            ->andWhere('assortiment_id = ' . $this->assortiment_id)
            ->andWhere('status = ' . Inkoop::STATUS_verkocht)
            ->sum('totaal_prijs');

        return $inkoop;
    }

    public function getCountMonthlyVerkocht($date)
    {
        $inkoop = (float) Inkoop::find()
            ->where('month(datum) = month(' . $date . ')')
            ->andWhere('year(datum) = year(' . $date . ')')
            ->andWhere('assortiment_id = ' . $this->assortiment_id)
            ->andWhere('status = ' . Inkoop::STATUS_verkocht)
            ->sum('aantal');

        return $inkoop;
    }

    public function getVolumeMonthlyVerkocht($date)
    {
        $inkoop = (float) Inkoop::find()
            ->where('month(datum) = month(' . $date . ')')
            ->andWhere('year(datum) = year(' . $date . ')')
            ->andWhere('assortiment_id = ' . $this->assortiment_id)
            ->andWhere('status = ' . Inkoop::STATUS_verkocht)
            ->sum('volume');
        return $inkoop;
    }

    public function getAssortimentMerken()
    {
        return Assortiment::find()
            ->where(['status' => Assortiment::STATUS_beschikbaar])
            ->orWhere(['status' => Assortiment::STATUS_tijdelijk_niet_beschikbaar])
            ->groupBy('merk')
            ->all();
    }

    public function getVolumeTurvenPeriod($start_date, $end_date)
    {
        $aantal = (float) Turven::find()
                        ->where(['between', 'datum', $start_date, $end_date])
                        ->andWhere('assortiment_id = ' . $this->assortiment_id)
                        ->sum('aantal');

        if (!$this->change_stock_auto) {
            $volume = (float) $aantal * (float) $this->volume / (float) 1000;
        } else {
            $volume = $aantal;
        }
        return $volume;
    }

    public function getVolumeInkoopPeriod($start_date, $end_date)
    {
        if (!$this->change_stock_auto) {
            $inkoop = (float) Inkoop::find()
                ->where(['between', 'datum', $start_date, $end_date])
                ->andWhere('assortiment_id = ' . $this->assortiment_id)
                ->andWhere('(status = ' . Inkoop::STATUS_verkocht . ' OR status = ' . Inkoop::STATUS_afgeschreven . ')')
                ->sum('volume');
        } else {
            $inkoop = (float) Inkoop::find()
                ->where(['between', 'datum', $start_date, $end_date])
                ->andWhere('assortiment_id = ' . $this->assortiment_id)
                ->andWhere('(status = ' . Inkoop::STATUS_verkocht . ' OR status = ' . Inkoop::STATUS_afgeschreven . ')')
                ->sum('aantal');
        }
        return $inkoop;
    }
}
