<?php

namespace app\controllers;

use Yii;
use \DateTime;
use app\models\Medication;
use app\models\Dose;
use yii\filters\AccessControl;
use yii\web\Controller;

class VisualisationController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ]
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $formatted_med_doses = [];

        // Combine doses by date
        $meds = Medication::find()->where(['user_id' => Yii::$app->user->identity->id])->all();

        return $this->render('index', [
            'graphs' => [
                [
                    'title' => 'Your Doses by Day',
                    'data' => VisualisationController::format_medication_doses_by_period($meds, 'day'),
                    'yAxisTitle' => 'Number of Doses (#)',
                    'quantityUnit' => 'dose(s)',
                ],
                [
                    'title' => 'Your Doses by Week',
                    'data' => VisualisationController::format_medication_doses_by_period($meds, 'week'),
                    'yAxisTitle' => 'Number of Doses (#)',
                    'quantityUnit' => 'dose(s)',
                ],
                [
                    'title' => 'Your Exacerbations by Day',
                    'data' => VisualisationController::format_exacerbations_by_period('day'),
                    'yAxisTitle' => 'Number of Exacerbations (#)',
                    'quantityUnit' => 'exacerbation(s)',
                ],
                [
                    'title' => 'Your Exacerbations by Week',
                    'data' => VisualisationController::format_exacerbations_by_period('week'),
                    'yAxisTitle' => 'Number of Exacerbations (#)',
                    'quantityUnit' => 'exacerbation(s)',
                ],
                [
                    'title' => 'Your Average Peak Flow by Day',
                    'data' => VisualisationController::format_peak_flows_by_period('day'),
                    'yAxisTitle' => 'Peak Flow (L/min)',
                    'quantityUnit' => 'L/min',
                ],
                [
                    'title' => 'Your Average Peak Flow by Week',
                    'data' => VisualisationController::format_peak_flows_by_period('week'),
                    'yAxisTitle' => 'Peak Flow (L/min)',
                    'quantityUnit' => 'L/min',
                ],
            ]
        ]);
    }

    public static function format_peak_flows_by_period($period = 'day') {
        $arr = [];
        $earliest_date_query;
        $latest_date_query;

        switch( $period ) {
            case 'day':
                $date_increment = 1;
                $earliest_date_query = "
                    SELECT MIN(UNIX_TIMESTAMP(DATE_FORMAT(recorded_at, '%Y-%m-%d 01:00:00'))) AS min_date
                    FROM peak_flow
                    WHERE user_id = ". Yii::$app->user->identity->id;
                $latest_date_query = "
                    SELECT MAX(UNIX_TIMESTAMP(DATE_FORMAT(recorded_at, '%Y-%m-%d 01:00:00'))) AS max_date
                    FROM peak_flow
                    WHERE user_id = ". Yii::$app->user->identity->id;
                $recordings_per_period_select = "SELECT AVG(value) AS average_value, UNIX_TIMESTAMP(DATE_FORMAT(recorded_at, '%Y-%m-%d 01:00:00')) AS recorded_during";
                break;

            case 'week':
                $date_increment = 7;
                $earliest_date_query = "
                    SELECT MIN(UNIX_TIMESTAMP(SUBDATE(DATE_FORMAT(recorded_at, '%Y-%m-%d 01:00:00'), WEEKDAY(DATE_FORMAT(recorded_at, '%Y-%m-%d 01:00:00'))))) AS min_date
                    FROM peak_flow
                    WHERE user_id = ". Yii::$app->user->identity->id;
                $latest_date_query = "
                    SELECT MAX(UNIX_TIMESTAMP(SUBDATE(DATE_FORMAT(recorded_at, '%Y-%m-%d 01:00:00'), WEEKDAY(DATE_FORMAT(recorded_at, '%Y-%m-%d 01:00:00'))))) AS max_date
                    FROM peak_flow
                    WHERE user_id = ". Yii::$app->user->identity->id;
                $recordings_per_period_select = "SELECT AVG(value) AS average_value, UNIX_TIMESTAMP(SUBDATE(DATE_FORMAT(recorded_at, '%Y-%m-%d 01:00:00'), WEEKDAY(DATE_FORMAT(recorded_at, '%Y-%m-%d 01:00:00')))) AS recorded_during";
                break;

        }

        $earliest_date = Yii::$app->db->createCommand($earliest_date_query)->queryOne()['min_date'];
        $latest_date = Yii::$app->db->createCommand($latest_date_query)->queryOne()['max_date'];

        $recordings_per_period = Yii::$app->db->createCommand($recordings_per_period_select . " FROM peak_flow WHERE user_id = " . Yii::$app->user->identity->id . " GROUP BY recorded_during")->queryAll();
        $recorded_times = array_column($recordings_per_period, 'recorded_during');

        for ( $date = $earliest_date; $date <= $latest_date; $date += $date_increment * 24 * 60 * 60) {
            if ( !in_array($date, $recorded_times) ) {
                $recordings_per_period []= [
                    'average_value' => 0,
                    'recorded_during' => $date,
                ];
            }
        }
        usort($recordings_per_period, function ($item1, $item2) {
            return $item1['recording_during'] <=> $item2['recording_during'];
        });

        $data = array_map(
            function ($peak_flow) {
                return [ floatval($peak_flow['recorded_during']) * 1000, floatval($peak_flow['average_value']) ];
            },
            $recordings_per_period
        );

        $arr = $data == [0,0] ? [] : [
            'name' => 'Your  Peak Flow',
            'data' => $data,
        ];

        return $arr;
    }

    public static function format_exacerbations_by_period($period = 'day') {
        $arr = [];
        $earliest_date_query;
        $latest_date_query;

        switch( $period ) {
            case 'day':
                $date_increment = 1;
                $earliest_date_query = "
                    SELECT MIN(UNIX_TIMESTAMP(DATE_FORMAT(happened_at, '%Y-%m-%d 01:00:00'))) AS min_date
                    FROM exacerbation
                    WHERE user_id = ". Yii::$app->user->identity->id;
                $latest_date_query = "
                    SELECT MAX(UNIX_TIMESTAMP(DATE_FORMAT(happened_at, '%Y-%m-%d 01:00:00'))) AS max_date
                    FROM exacerbation
                    WHERE user_id = ". Yii::$app->user->identity->id;
                $exacerbations_per_period_select = "SELECT COUNT(id) AS num_exacerbations, UNIX_TIMESTAMP(DATE_FORMAT(happened_at, '%Y-%m-%d 01:00:00')) AS happened_during";
                break;

            case 'week':
                $date_increment = 7;
                $earliest_date_query = "
                    SELECT MIN(UNIX_TIMESTAMP(SUBDATE(DATE_FORMAT(happened_at, '%Y-%m-%d 01:00:00'), WEEKDAY(DATE_FORMAT(happened_at, '%Y-%m-%d 01:00:00'))))) AS min_date
                    FROM exacerbation
                    WHERE user_id = ". Yii::$app->user->identity->id;
                $latest_date_query = "
                    SELECT MAX(UNIX_TIMESTAMP(SUBDATE(DATE_FORMAT(happened_at, '%Y-%m-%d 01:00:00'), WEEKDAY(DATE_FORMAT(happened_at, '%Y-%m-%d 01:00:00'))))) AS max_date
                    FROM exacerbation
                    WHERE user_id = ". Yii::$app->user->identity->id;
                $exacerbations_per_period_select = "SELECT COUNT(id) AS num_exacerbations, UNIX_TIMESTAMP(SUBDATE(DATE_FORMAT(happened_at, '%Y-%m-%d 01:00:00'), WEEKDAY(DATE_FORMAT(happened_at, '%Y-%m-%d 01:00:00')))) AS happened_during";
                break;

        }

        $earliest_date = Yii::$app->db->createCommand($earliest_date_query)->queryOne()['min_date'];
        $latest_date = Yii::$app->db->createCommand($latest_date_query)->queryOne()['max_date'];

        $exacerbations_per_period = Yii::$app->db->createCommand($exacerbations_per_period_select . " FROM exacerbation WHERE user_id = " . Yii::$app->user->identity->id . " GROUP BY happened_during")->queryAll();
        $happened_times = array_column($exacerbations_per_period, 'happened_during');

        for ( $date = $earliest_date; $date <= $latest_date; $date += $date_increment * 24 * 60 * 60) {
            if ( !in_array($date, $happened_times) ) {
                $exacerbations_per_period []= [
                    'num_exacerbations' => 0,
                    'happened_during' => $date,
                ];
            }
        }
        usort($exacerbations_per_period, function ($item1, $item2) {
            return $item1['happened_during'] <=> $item2['happened_during'];
        });

        $arr = [
            [
                'name' => 'Exacerbations',
                'data' => array_map(
                    function ($exacerbation) {
                        return [ floatval($exacerbation['happened_during']) * 1000, floatval($exacerbation['num_exacerbations']) ];
                    },
                    $exacerbations_per_period
                )
            ]
        ];

        return $arr;
    }

    public static function format_medication_doses_by_period($meds, $period = 'day') {
        $arr = [];
        $earliest_date_query;
        $latest_date_query;

        switch( $period ) {
            case 'day':
                $date_increment = 1;
                $earliest_date_query = "
                    SELECT MIN(UNIX_TIMESTAMP(DATE_FORMAT(taken_at, '%Y-%m-%d 01:00:00'))) AS min_date
                    FROM dose
                    WHERE medication_id IN (
                        SELECT id
                        FROM medication
                        WHERE user_id = ". Yii::$app->user->identity->id .
                    ")";
                $latest_date_query = "
                    SELECT MAX(UNIX_TIMESTAMP(DATE_FORMAT(taken_at, '%Y-%m-%d 01:00:00'))) AS max_date
                    FROM dose
                    WHERE medication_id IN (
                        SELECT id
                        FROM medication
                        WHERE user_id = ". Yii::$app->user->identity->id .
                    ")";
                $doses_per_period_select = "SELECT SUM(dose_size) AS num_doses, UNIX_TIMESTAMP(DATE_FORMAT(taken_at, '%Y-%m-%d 01:00:00')) AS taken_during";
                break;

            case 'week':
                $date_increment = 7;
                $earliest_date_query = "
                    SELECT MIN(UNIX_TIMESTAMP(SUBDATE(DATE_FORMAT(taken_at, '%Y-%m-%d 01:00:00'), WEEKDAY(DATE_FORMAT(taken_at, '%Y-%m-%d 01:00:00'))))) AS min_date
                    FROM dose
                    WHERE medication_id IN (
                        SELECT id
                        FROM medication
                        WHERE user_id = ". Yii::$app->user->identity->id .
                    ")";
                $latest_date_query = "
                    SELECT MAX(UNIX_TIMESTAMP(SUBDATE(DATE_FORMAT(taken_at, '%Y-%m-%d 01:00:00'), WEEKDAY(DATE_FORMAT(taken_at, '%Y-%m-%d 01:00:00'))))) AS max_date
                    FROM dose
                    WHERE medication_id IN (
                        SELECT id
                        FROM medication
                        WHERE user_id = ". Yii::$app->user->identity->id .
                    ")";
                $doses_per_period_select = "SELECT SUM(dose_size) AS num_doses, UNIX_TIMESTAMP(SUBDATE(DATE_FORMAT(taken_at, '%Y-%m-%d 01:00:00'), WEEKDAY(DATE_FORMAT(taken_at, '%Y-%m-%d 01:00:00')))) AS taken_during";
                break;

        }

        $earliest_date = Yii::$app->db->createCommand($earliest_date_query)->queryOne()['min_date'];
        $latest_date = Yii::$app->db->createCommand($latest_date_query)->queryOne()['max_date'];

        foreach ($meds as $med) {
            $doses_per_period = Yii::$app->db->createCommand($doses_per_period_select . " FROM dose WHERE medication_id = " . $med->id . " GROUP BY taken_during")->queryAll();
            $taken_times = array_column($doses_per_period, 'taken_during');

            for ( $date = $earliest_date; $date <= $latest_date; $date += $date_increment * 24 * 60 * 60) {
                if ( !in_array($date, $taken_times) ) {
                    $doses_per_period []= [
                        'num_doses' => 0,
                        'taken_during' => $date,
                    ];
                }
            }
            usort($doses_per_period, function ($item1, $item2) {
                return $item1['taken_during'] <=> $item2['taken_during'];
            });

            $arr []= [
                'name' => $med->name,
                'data' => array_map(
                    function ($dpd) {
                        return [ floatval($dpd['taken_during']) * 1000, floatval($dpd['num_doses']) ];
                    },
                    $doses_per_period
                )
            ];
        }

        return $arr;
    }
}
