<?php

namespace Jenga\MyProject\Services;

use Jenga\App\Views\HTML;

class Charts {

    public $link;
    public $export;
    private $_chart;
    private $_settings;

    public function __construct($settings) {

        //add to HTML head section
        //HTML::register($link);

        if (isset($settings['export'])) {

            $export = '<script src="'
                    . RELATIVE_APP_PATH . '/services/highcharts-4.1.5/js/modules/exporting.js'
                    . '"></script>';

            //HTML::register($export);
        }

        echo $this->export = $export;

        $this->_settings = $settings;
        $this->_chart['id'] = $settings['id'];

        $this->_disableCredits();
    }

    /**
     * Sets the charts general settings
     *
     * @param type $config
     */
    public function setup($config = []) {

        $chart = 'chart: {';

        foreach ($config as $setting => $value) {

            if ($value != 'null' && $value != 'false')
                $options .= $setting . ': \'' . $value . '\',';
            else
                $options .= $setting . ': ' . $value . ',';
        }

        $chart .= rtrim($options, ',');

        $chart .= '},';

        $this->_chart['chart'] = $chart;
    }

    /**
     * Sets the charts main title
     *
     * @param type $title
     */
    public function title($title) {

        $chart_title = "title: {
            text: '" . addslashes($title) . "'
        },";

        $this->_chart['title'] = $chart_title;
    }

    /**
     * Sets the tooltip for each section
     *
     * @param type $tip
     */
    public function tooltip($tip) {

        $data = $this->_processPhpArray($tip);

        $tooltip = "tooltip: {
            $data
        },";

        $this->_chart['tooltip'] = $tooltip;
    }

    /**
     * Creates plot options for chart
     *
     * @param type $options
     */
    public function plotOptions($options = []) {

        $type = array_keys($options);

        $plot = 'plotOptions: {'
                . "'$type[0]' : {";

        foreach ($options[$type[0]] as $option => $value) {

            if ($option == 'dataLabels') {

                $plot .= 'dataLabels: {';
                $plot .= $this->_dataLabels($value);
                $plot .= '},';
            } else {

                $plot .= $option . " : " . $value . ",";
            }
        }

        $plot .= '}'
                . '},';

        $this->_chart['plotOptions'] = $plot;
    }

    /**
     * Processes the xAxis
     *
     * @param type $settings
     */
    public function xAxis($settings) {

        if (isset($settings['categories'])) {

            $data = 'categories: ' . $this->_processJsArray($settings['categories']);
        }

        $xdata = "xAxis: {
            " . $data . "
        },";

        $this->_chart['xaxis'] = $xdata;
    }

    /**
     * Process the yAxis
     *
     * @param type $settings
     */
    public function yAxis($settings) {

        foreach ($settings as $name => $value) {

            if (is_array($value)) {

                $value = '{' . $this->_processPhpArray($value) . '}';

                if ($name == 'plotLines') {

                    $value = '[' . $value . ']';
                }
            }

            $data .= $name . ': ' . $value . ',';
        }

        $data = rtrim($data, ',');

        $ydata = "yAxis: {
                        $data
                    },";

        $this->_chart['yaxis'] = $ydata;
    }

    /**
     *
     * @param type $items
     */
    public function legend($items) {

        $data = $this->_processPhpArray($items);

        $legend = 'legend: {'
                . $data
                . '},';

        $this->_chart['legend'] = $legend;
    }

    /**
     * This sets the exact pie chart settings
     *
     * @param type $params
     */
    public function pieSeries($params) {

        array_multisort($params);

        $series = "series: [{
            type: 'pie',
            name: '" . $this->_settings['id'] . "',
            data: [";

        $count = 0;
        foreach ($params as $name => $value) {

            if ($count == '2') {

                $settings .= '{'
                        . "name: '$name',"
                        . "y: " . $value . ","
                        . "sliced: true,"
                        . "selected: true"
                        . '},';
            } else {

                $settings .= "['" . $name . "', " . $value . "],";
            }

            $count++;
        }

        $series .= rtrim($settings, ',');

        $series .= "]"
                . "}]";

        $this->_chart['series'] = $series;
    }

    /**
     * This sets the exact bar chart settings
     *
     * @param type $params
     */
    public function barSeries($params, $legend) {

        $series = 'series: [';

        $count = 0;
        foreach ($legend as $status) {

            $series .= "{";

            $series .= "name:'" . $status . "',";
            $series .= 'data: [';

            foreach ($params as $month => $valuestr) {

                $value = explode(',', $valuestr);
                $entries .= $value[$count] . ',';
            }

            $series .= rtrim($entries, ',');
            unset($entries);

            $series .= ']';
            $series .= "},";
            $count++;
        }

        $series = rtrim($series, ',');

        $series .= ']';

        $this->_chart['series'] = $series;
    }

    /**
     * This sets the exact column chart settings
     *
     * @param type $params
     */
    public function columnSeries($params, $legend) {

        $series = 'series: [';

        $count = 0;
        foreach ($legend as $status) {

            $series .= "{";

            $series .= "name:'" . $status . "',";
            $series .= 'data: [';

            foreach ($params as $month => $valuestr) {

                $value = explode(',', $valuestr);
                $entries .= $value[$count] . ',';
            }

            $series .= rtrim($entries, ',');
            unset($entries);

            $series .= ']';
            $series .= "},";
            $count++;
        }

        $series = rtrim($series, ',');

        $series .= ']';

        $this->_chart['series'] = $series;
    }

    /**
     * This sets the exact stacked column chart settings
     *
     * @param type $params
     */
    public function stackedColumnSeries($params, $legend) {

        $series = 'series: [';

        $pkeys = array_keys($params);

        $count = 0;
        foreach ($legend as $status) {

            $series .= "{";

            $series .= "name:'" . $status . "',";
            $series .= 'data: [';
            $series .= join(',', $params[$pkeys[$count]]);
            $series .= ']';

            $series .= "},";
            $count++;
        }

        $series = rtrim($series, ',');
        $series .= ']';

        $this->_chart['series'] = $series;
    }

    /**
     * This sets the exact line chart settings
     *
     * @param type $params
     */
    public function lineSeries($params, $legend) {

        $series = 'series: [';

        foreach ($legend as $status) {

            $series .= "{";

            $series .= "name:'" . $status . "',";
            $series .= 'data: [';

            foreach ($params as $month => $value) {

                $entries .= $value . ',';
            }

            $series .= rtrim($entries, ',');
            unset($entries);

            $series .= ']';
            $series .= "},";
        }

        $series = rtrim($series, ',');

        $series .= ']';

        $this->_chart['series'] = $series;
    }

    /**
     * Renders the complete chart
     *
     * @return string
     */
    public function build() {

        $chart .= "<script>" .
                "$(function () {
                        $('#" . $this->_chart['id'] . "').highcharts({";

        unset($this->_chart['id']);

        foreach ($this->_chart as $option => $value) {

            $chart .= $value;
        }

        $chart .= "});
                });
                </script>";

        if (isset($this->_settings['width'])) {

            $style = 'style="width:' . $this->_settings['width'] . '; height:' .
                    (isset($this->_settings['height']) ? $this->_settings['height'] : 'auto')
                    . ';"';
        }

        $chart .= '<div id="' . $this->_settings['id'] . '" ' . $style . '></div>';

        return $chart;
    }

    private function _disableCredits() {

        $data = "credits: {
                    enabled: false
                },";

        $this->_chart['credits'] = $data;
    }

    /**
     * Create the chart data labels
     *
     * @param type $labels
     * @return string
     */
    private function _dataLabels($labels) {

        foreach ($labels as $label => $value) {

            if ($label == 'style') {

                $data .= 'style: {';
                $data .= $this->_style($value);
                $data .= '}';
            } else {

                $data .= $label . ": " . $value . ",";
            }
        }
        $data = rtrim($data, ',');

        return $data;
    }

    /**
     * Creates the data labels style
     *
     * @param type $labels
     * @return type
     */
    private function _style($labels) {

        foreach ($labels as $label => $value) {

            $data .= $label . ": " . $value . ",";
        }

        $data = rtrim($data, ',');

        return $data;
    }

    /**
     * Processes a php array and returns a Javascript array
     *
     * @param type $phparray
     */
    private function _processJsArray($phparray) {

        return json_encode($phparray);
    }

    /**
     * Process PHP array
     *
     * @param type $items
     * @return type
     */
    private function _processPhpArray($items) {

        foreach ($items as $name => $value) {

            $data .= $name . ': ' . $value . ',';
        }
        $data = rtrim($data, ',');

        return $data;
    }

}
