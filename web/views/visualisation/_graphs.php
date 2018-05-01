<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $graphs [] */
?>

<?php $count = 0 ?>
<?php foreach ( $graphs as $graph ): ?>
    <div id="container-<?= $count++ ?>" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<?php endforeach ?>
<script>
    $(function () {
        $.getScript('http://code.highcharts.com/highcharts.js', function () {
            $.getScript('http://code.highcharts.com/modules/exporting.js', function () {

                <?php $count = 0 ?>
                <?php foreach ( $graphs as $graph ): ?>
                    <?php if ( $graph['data'] != [] && $graph['data'][0]['data'] != [[0,0]] ): ?>
                        $('#container-<?= $count ?>').highcharts({
                            chart: {
                                type: 'spline'
                            },
                            title: {
                                text: '<?= $graph['title'] ?>',
                            },
                            subtitle: {
                                text: ''
                            },
                            xAxis: {
                                type: 'datetime',
                                pointInterval: 24 * 60 * 60 * 1000,
                                title: {
                                    text: 'Date',
                                },
                            },
                            yAxis: {
                                title: {
                                    text: '<?= $graph['yAxisTitle'] ?>'
                                },
                                min: 0,
                            },
                            tooltip: {
                                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                    '<td style="padding:0"><b>{point.y:.0f} <?= $graph['quantityUnit'] ?></b></td></tr>',
                                footerFormat: '</table>',
                                shared: true,
                                useHTML: true,
                            },
                            plotOptions: {
                                spline: {
                                    marker: {
                                        enabled: true
                                    }
                                }
                            },
                            series: <?= json_encode( $graph['data'] ) ?>
                        });
                    <?php else: ?>
                        $('#container-<?= $count ?>').hide();
                    <?php endif ?>
                    <?php $count++ ?>
                <?php endforeach ?>
            });
        });
    });
</script>
