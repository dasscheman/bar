<?php

namespace app\models;

use Yii;
use app\models\BarActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "assortiment".
 *
 * @property int $assortiment_id
 * @property string $name
 * @property string $merk
 * @property string $soort
 * @property int $alcohol
 * @property int $status
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * @property int $change_stock_auto
 *
 * @property Afschrijving[] $afschrijvings
 * @property User $createdBy
 * @property User $updatedBy
 * @property Eenheid[] $eenhes
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
    public function getAfschrijving()
    {
        return $this->hasMany(Afschrijving::className(), ['assortiment_id' => 'assortiment_id']);
    }

    public function getMaandAfschrijving($date)
    {
        return $this->getAfschrijving()
            ->where('month(datum) = month(' . $date . ')')
            ->andWhere('year(datum) = year(' . $date . ')');
    }

    public function getTotaalTotMaandAfschrijving($date)
    {
        return $this->getAfschrijving()
            ->andWhere('date_format(datum, "%Y-%m") <= date_format(' . $date . ', "%Y-%m")');
            // ->andWhere('year(datum) <= year(' . $date . ')');
    }

    public function getSumMaandAfschrijving($date)
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) use ($date){
            return (float) $this->getMaandAfschrijving($date)
                ->sum('totaal_prijs');

        });
        return $data;
    }

    public function getVolumeMaandAfschrijving($date)
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) use ($date){
            return (float) $this->getMaandAfschrijving($date)
                ->sum('totaal_volume');

        });
        return $data;
    }

    public function getVolumeTotMaandAfschrijving($date)
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) use ($date){
            return (float) $this->getTotaalTotMaandAfschrijving($date)
                ->sum('totaal_volume');

        });
        return $data;
    }

    public function getVolumeTotMaandOverdatum($date)
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) use ($date){
            return (float) $this->getTotaalTotMaandAfschrijving($date)
                ->andWhere('type = ' . Afschrijving::TYPE_overdatum)
                ->sum('totaal_volume');

        });
        return $data;
    }

    public function getCountMaandAfschrijving($date)
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) use ($date){
            return (int) $this->getMaandAfschrijving($date)
            ->sum('aantal');
        });
        return $data;
    }

    public function getCountTotMaandAfschrijving($date)
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) use ($date){
            return (int) $this->getTotaalTotMaandAfschrijving($date)
                ->sum('aantal');

        });
        return $data;
    }

    public function getCountTotMaandOverdatum($date)
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) use ($date){
            return (int) $this->getTotaalTotMaandAfschrijving($date)
                ->andWhere('type = ' . Afschrijving::TYPE_overdatum)
                ->sum('aantal');

        });
        return $data;
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
    public function getEenheid()
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) {
            return $this->hasMany(Eenheid::className(), ['assortiment_id' => 'assortiment_id']);
        });
        return $data;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInkoop()
    {
        return $this->hasMany(Inkoop::className(), ['assortiment_id' => 'assortiment_id']);
    }

    public function getMaandInkoop($date)
    {
        return $this->getInkoop()
            ->where('month(datum) = month(' . $date . ')')
            ->andWhere('year(datum) = year(' . $date . ')');
    }

    public function getTotaalTotMaandInkoop($date)
    {
        return $this->getInkoop()
            ->andWhere('date_format(datum, "%Y-%m") <= date_format(' . $date . ', "%Y-%m")');
            // ->where('month(datum) <= month(' . $date . ')')
            // ->andWhere('year(datum) <= year(' . $date . ')');
    }

    public function getSumMaandInkoop($date)
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) use ($date){
            return (float) $this->getMaandInkoop($date)
                ->sum('totaal_prijs');

        });
        return $data;
    }

    public function getSumTotMaandInkoop($date)
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) use ($date){
            return (float) $this->getTotaalTotMaandInkoop($date)
                ->sum('totaal_prijs');

        });
        return $data;
    }

    public function getVolumeMaandInkoop($date)
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) use ($date){
            return (float) $this->getMaandInkoop($date)
                ->sum('totaal_volume');
        });
        return $data;
    }

    public function getVolumeTotMaandInkoop($date)
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) use ($date){
            return (float) $this->getTotaalTotMaandInkoop($date)
            ->sum('totaal_volume');

        });
        return $data;
    }

    public function getCountTotMaandInkoop($date)
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) use ($date){
            return (float) $this->getTotaalTotMaandInkoop($date)
                ->sum('aantal');

        });
        return $data;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTotaalInkoop()
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) {
            return (float) $this->getInkoop()
            ->sum('totaal_prijs');

        });
        return $data;
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

    /**
     * Get the assortiment name based on id
     *
     * @param int $id Assortiment id.
     * @return string Name of assortiment.
     */
    public function getAssortimentNameOptions()
    {
        if (($model = self::find()) !== null) {
            return ArrayHelper::map($model->asArray()->all(), 'assortiment_id', 'name');
        }
        return false;
    }

    public function getTurven()
    {
        $eenheid_ids = [];
        foreach ($this->eenheid as $eenheid) {
            array_push($eenheid_ids, $eenheid->eenheid_id);
        }

        return Turven::find()
            ->where(['in', 'eenheid_id', $eenheid_ids]);
    }

    public function getMaandTurven($date)
    {
        return $this->getTurven()
            ->andWhere('month(datum) = month(' . $date . ')')
            ->andWhere('year(datum) = year(' . $date . ')');
    }

    public function getTotaalTotMaandTurven($date)
    {
        return $this->getTurven()
            ->andWhere('date_format(datum, "%Y-%m") <= date_format(' . $date . ', "%Y-%m")');
            // ->andWhere('month(datum) <= month(' . $date . ')')
            // ->andWhere('year(datum) <= year(' . $date . ')');
    }

    public function getSumMaandInkomen($date)
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) use ($date){
            return (float) $this->getMaandTurven($date)
                ->sum('totaal_prijs');

        });
        return $data;
    }

    public function getSumTotMaandInkomen($date)
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) use ($date){
            return (float) $this->getTotaalTotMaandTurven($date)
                ->sum('totaal_prijs');
        });
        return $data;
    }

    public function getVolumeMaandTurven($date)
    {
        $volume = 0;
        foreach ($this->getMaandTurven($date)->all() as $turf) {
            $volume += $turf->getEenheid()->one()->volume * $turf->aantal / 1000;
        }
        return (float) $volume;
    }

    public function getVolumeTotMaandTurven($date)
    {
        $volume = 0;
        $db = self::getDb();
        $turven = $db->cache(function ($db) use ($date){
            return $this->getTotaalTotMaandTurven($date)->all();
        });

        foreach ($turven as $turf) {
            $db = self::getDb();
            $eenheid = $db->cache(function ($db) use ($turf){
                return $turf->getEenheid()->one();

            });
            $volume += $eenheid->volume * $turf->aantal / 1000;
        }
        return (float) $volume;
    }

    public function getCountMaandTurven($date)
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) use ($date){
            return (int) $this->getMaandTurven($date)
                ->sum('aantal');
        });
        return $data;
    }

    public function getCountTotMaandTurven($date)
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) use($date) {
            return (int) $this->getTotaalTotMaandTurven($date)
            ->sum('aantal');
        });
        return $data;
    }

    public function getVolumeInkoopPeriod($start_date, $end_date)
    {
        $totaal = 0;
        if (!$this->change_stock_auto) {
            $inkoop = Inkoop::find()
                ->where(['between', 'datum', $start_date, $end_date])
                ->andWhere('assortiment_id = ' . $this->assortiment_id);
            foreach ($inkoop->all() as $item) {
                $totaal += $item['volume'] * $item ['aantal'];
            }
        } else {
            $inkoop = Inkoop::find()
                ->where(['between', 'datum', $start_date, $end_date])
                ->andWhere('assortiment_id = ' . $this->assortiment_id);
            foreach ($inkoop->all() as $item) {
                $totaal += $item['aantal'] * $item ['aantal'];
            }
        }
        return (float) $totaal;
    }

    public function getAantalSerie($aantalMaanden)
    {
        $i = 0;
        $seriesAantal = [];
        while ($i < $aantalMaanden) {
            $date = date("Ymd", strtotime("-$i months"));
            $month =date("M", strtotime("-$i months"));
            $turven_temp = $this->getCountTotMaandTurven($date);
            if (isset($turven[$month])) {
                $turven[$month] = $turven[$month] + $turven_temp;
            } else {
                $turven[$month] = $turven_temp;
            }

            $inkoop_temp = $this->getCountTotMaandInkoop($date);
            if (isset($inkoop[$month])) {
                $inkoop[$month] = $inkoop[$month] + $inkoop_temp;
            } else {
                $inkoop[$month] = $inkoop_temp;
            }

            $verlies_temp = $this->getCountTotMaandAfschrijving($date);
            if (isset($verlies[$month])) {
                $verlies[$month] = $verlies[$month] + $verlies_temp;
            } else {
                $verlies[$month] = $verlies_temp;
            }

            $overdatum_temp = $this->getCountTotMaandOverdatum($date);
            if (isset($overdatum[$month])) {
                $overdatum[$month] = $overdatum[$month] + $overdatum_temp;
            } else {
                $overdatum[$month] = $overdatum_temp;
            }
            $i++;
        }

        $rendement = [];
        foreach($turven as $maand => $value) {
            if($value == 0 || $inkoop[$maand] == 0) {
                $rendement[$maand] = 0;
                continue;
            }

            $rendement[$maand] = ($value + $overdatum[$maand]) / $inkoop[$maand] * 100;
        }

        $seriesAantal[] = ['name' => 'Turven Drank', 'type' => 'area', 'data' => array_reverse(array_values($turven)), 'stack' => 'turven'];
        $seriesAantal[] = ['name' => 'Inkoop Drank', 'type' => 'area', 'data' => array_reverse(array_values($inkoop)), 'stack' => 'inkoop'];
        $seriesAantal[] = ['name' => 'Afgeschreven Drank', 'type' => 'area', 'data' => array_reverse(array_values($verlies)), 'stack' => 'afschrijving'];
        $seriesAantal[] = ['name' => 'Rendement', 'type' => 'spline', 'yAxis' => 1, 'data' => array_reverse(array_values($rendement))];
        return $seriesAantal;
    }

    public function getVolumeSerie($aantalMaanden)
    {
        $i = 0;
        $seriesGeld = [];
        $seriesVolume = [];
        while ($i < $aantalMaanden) {
            $date = date("Ymd", strtotime("-$i months"));
            $month = date("M", strtotime("-$i months"));
            // Volume verkoop
            $turven_temp = $this->getVolumeTotMaandTurven($date);
            if (isset($turven[$month])) {
                $turven[$month] = $turven[$month] + $turven_temp;
            } else {
                $turven[$month] = $turven_temp;
            }

            $inkoop_temp = $this->getVolumeTotMaandInkoop($date);
            if (isset($inkoop[$month])) {
                $inkoop[$month] = $inkoop[$month] + $inkoop_temp;
            } else {
                $inkoop[$month] = $inkoop_temp;
            }

            $verlies_temp = $this->getVolumeTotMaandAfschrijving($date);
            if (isset($verlies[$month])) {
                $verlies[$month] = $verlies[$month] + $verlies_temp;
            } else {
                $verlies[$month] = $verlies_temp;
            }
            $overdatum_temp = $this->getVolumeTotMaandOverdatum($date);
            if (isset($overdatum[$month])) {
                $overdatum[$month] = $overdatum[$month] + $overdatum_temp;
            } else {
                $overdatum[$month] = $overdatum_temp;
            }
            $i++;
        }

        $rendement = [];
        foreach($turven as $maand => $value) {
            if($value == 0 || $inkoop[$maand] == 0) {
                $rendement[$maand] = 0;
                continue;
            }
            $rendement[$maand] = ($value + $overdatum[$maand]) / $inkoop[$maand] * 100;
        }

        $seriesVolume[] = ['name' => 'Inkoop Drank', 'type' => 'area', 'data' => array_reverse(array_values($inkoop)), 'stack' => 'inkoop'];
        $seriesVolume[] = ['name' => 'Turven Drank', 'type' => 'area', 'data' => array_reverse(array_values($turven)), 'stack' => 'turven'];
        $seriesVolume[] = ['name' => 'Afgeschreven Drank', 'type' => 'area', 'data' => array_reverse(array_values($verlies)), 'stack' => 'afschrijving'];
        $seriesVolume[] = ['name' => 'Rendement', 'type' => 'spline', 'yAxis' => 1, 'data' => array_reverse(array_values($rendement))];
        return $seriesVolume;
    }

    public function getGeldSerie($aantalMaanden)
    {
        $i = 0;
        $seriesGeld = [];
        while ($i < $aantalMaanden) {
            $date = date("Ymd", strtotime("-$i months"));            //Financieel overzicht per merk.
            $inkomsten_temp = $this->getSumTotMaandInkomen($date);
            if (isset($inkomsten[date("M", strtotime("-$i months"))])) {
                $inkomsten[date("M", strtotime("-$i months"))] = $inkomsten[date("M", strtotime("-$i months"))] + $inkomsten_temp;
            } else {
                $inkomsten[date("M", strtotime("-$i months"))] = $inkomsten_temp;
            }

            $uitgaven_temp = $this->getSumTotMaandInkoop($date);
            if (isset($uitgaven[date("M", strtotime("-$i months"))])) {
                $uitgaven[date("M", strtotime("-$i months"))] = $uitgaven[date("M", strtotime("-$i months"))] + $uitgaven_temp;
            } else {
                $uitgaven[date("M", strtotime("-$i months"))] = $uitgaven_temp;
            }

            $i++;
        }
        $seriesGeld[] = ['name' => 'Uitgaven Drank', 'data' => array_reverse(array_values($uitgaven)), 'stack' => 'uitgaven'];
        $seriesGeld[] = ['name' => 'Inkomsten Drank', 'data' => array_reverse(array_values($inkomsten)), 'stack' => 'inkomsten'];
        return $seriesGeld;
    }

    /**
    * Berekend een gemiddelde liter prijs over de inkoop van afgelopen 6 maanden.
    */
    public function gemiddeldePrijsPerLiter(){
        $aantalMaanden = 6;
        $i = 0;
        $prijs = 0;
        $volume = 0;
        while ($i < $aantalMaanden) {
            $date = date("Ymd", strtotime("-$i months"));

            $prijs = $this->getSumMaandInkoop($date) + $prijs;
            $volume = $this->getVolumeMaandInkoop($date) + $volume;
            $i++;
        }

        if(!$prijs > 0 || !$volume > 0) {
            return 0;
        }
        return (float) $prijs/$volume;
    }
}
