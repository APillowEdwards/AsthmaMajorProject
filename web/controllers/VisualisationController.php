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

        $earliest_date =
            Yii::$app->db->createCommand("
                SELECT MIN(UNIX_TIMESTAMP(DATE_FORMAT(taken_at, '%Y-%m-%d 00:00:00'))) AS min_date
                FROM dose
                WHERE medication_id IN (
                    SELECT id
                    FROM medication
                    WHERE user_id = " . Yii::$app->user->identity->id .
                ")"
            )->queryOne()['min_date']
        ;

        $latest_date =
            Yii::$app->db->createCommand("
                SELECT MAX(UNIX_TIMESTAMP(DATE_FORMAT(taken_at, '%Y-%m-%d 00:00:00'))) AS max_date
                FROM dose
                WHERE medication_id IN (
                    SELECT id
                    FROM medication
                    WHERE user_id = " . Yii::$app->user->identity->id .
                ")"
            )->queryOne()['max_date']
        ;

        echo "E: " . $earliest_date . " ----- L: " . $latest_date;

        //4662000
        foreach ($meds as $med) {
            $doses_per_day = Yii::$app->db->createCommand("SELECT SUM(dose_size) AS num_doses, UNIX_TIMESTAMP(DATE_FORMAT(taken_at, '%Y-%m-%d 00:00:00')) AS taken_on FROM dose WHERE medication_id = " . $med->id . " GROUP BY DATE_FORMAT(taken_at, '%Y-%m-%d 00:00:00')")->queryAll();

            // 1524438000 - 1524524400
            for ( $date = $earliest_date; $date <= $latest_date; $date += 24 * 60 * 60 ) {
                $taken_times = array_column($doses_per_day, 'taken_on');
                if ( !in_array($date, $taken_times) ) {
                    $doses_per_day []= [
                        'num_doses' => 0,
                        'taken_on' => $date,
                    ];
                }
            }

            $formatted_med_doses []= [
                'name' => $med->name,
                'data' => $doses_per_day,
            ];
        }

        return $this->render('index', [
            'doses_graph_data' => $formatted_med_doses,
        ]);

        // Combine doses by date
        $meds = Medication::find()->where(['user_id' => Yii::$app->user->identity->id])->all();
        foreach ($meds as $med) {
            echo Yii::$app->db->createCommand('SELECT SUM(dose_size) FROM doses GROUP BY')->queryAll();
            $doses = $med->doses;
            if ( $doses->length ) {
                $dose_times = []; // Can't send this, as the keys need to be 0-indexed

                $earliest_date = new DateTime('31-12-2099 00:00:00.0000');
                $latest_date = new DateTime('01-01-1900 00:00:00.0000');

                foreach ($doses as $dose) {
                    $date = new DateTime($dose->taken_at);
                    $date->setTime(0,0,0);
                    if ( $date < $earliest_date) {
                        $earliest_date = $date;
                    }
                    if ( $date > $latest_date) {
                        $latest_date = $date;
                    }
                    if ( isset( $dose_times[ $date->getTimestamp() ] ) ) {
                        $dose_times[ $date->getTimestamp() ][1] += floatval( $dose->dose_size );
                    } else {
                        $dose_times[ $date->getTimestamp() ] = [$date->getTimestamp()  * 1000, floatval( $dose->dose_size )];
                    }
                }
                echo "<pre>" . $earliest_date->format('Y-m-d H:i:s') . " --- " . $latest_date->format('Y-m-d H:i:s') . "</pre>";
                // Make a 0-indexed version of the dose_times array
                $dose_times_zero_index = [];
                foreach ($dose_times as $dose_time) {
                    $dose_times_zero_index []= $dose_time;
                }

                $formatted_med_doses []= [
                    'name' => $med->name,
                    'data' => $dose_times_zero_index,
                ];
            }
        }

        return $this->render('index', [
            'doses_graph_data' => $formatted_med_doses,
        ]);
    }
}
