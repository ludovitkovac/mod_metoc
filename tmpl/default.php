<?php
defined('_JEXEC') or die('Restricted access');
JHTML::script(Juri::base() . '/media/mod_metoc/js/show-hide.js');               // link to JavaScript file
JFactory::getLanguage();

require_once(JPATH_BASE . '/modules/mod_metoc/helper.php');

$hidi = JText::_('MOD_METOC_HIDI');
$wora = JText::_('MOD_METOC_WORA');
$today = JText::_('MOD_METOC_TODAY');
$lastweek = JText::_('MOD_METOC_LAST_WEEK');
$lastmonth = JText::_('MOD_METOC_LAST_MONTH');
$threemonts = JText::_('MOD_METOC_LAST_THREE_MONTHS');
$lastnews = JText::_('MOD_METOC_LAST_NEWS');

echo ModMetocHelper::AddCountOfWords();
echo ModMetocHelper::AddUniCountOfWords();
echo ModMetocHelper::UpdateCountOfWords();
echo ModMetocHelper::UpdateUniCountOfWords();

$module = JModuleHelper::getModule('mod_metoc');
$params = new JRegistry($module->params);
$visHidi = $params->get('visibleHidi');
$visWora = $params->get('visibleWora');
$visTA = $params->get('visibleToday');
$visWA = $params->get('visibleWeek');
$visMA = $params->get('visibleMonth');
$visTMA = $params->get('visibleThreeMonts');
$visLA = $params->get('visibleLatest');


if ($visHidi == "SHOW") {
    echo '<div class="button" onclick="showHideHidi()" align="center"><b><a>' . $hidi . '</a></b></div>';
    echo '<div id="hididiv" style="display: none">';
    echo ModMetocHelper::Hidi();
    echo '</div>';
}

if ($visWora == "SHOW") {
    echo '<div class="button" onclick="showHideWora()" align="center"><hr><b><a>' . $wora . '</a></b></div>';
    echo '<div id="woradiv" style="display: none">';
    echo ModMetocHelper::Wora();
    echo '</div>';
}

if ($visTA == "SHOW") {
    echo '<div class="button" onclick="showHideToday()" align="center"><hr><b><a>' . $today . '</a></b></div>';
    echo '<div id="todaydiv" style="display: none">';
    echo ModMetocHelper::TodayArticles();
    echo '</div>';
}

if ($visWA == "SHOW") {
    echo ' <div class="button" onclick="showHideWeek()" align="center"><hr><b><a>' . $lastweek . '</a></b></div> ';
    echo '<div id="weekdiv" style="display: none">';
    echo ModMetocHelper::WeekArticles();
    echo '</div>';
}

if ($visMA == "SHOW") {
    echo '<div class="button" onclick="showHideMonth()" align="center"><hr><b><a>' . $lastmonth . '</a></b></div>';
    echo '<div id="monthdiv" style="display: none">';
    echo ModMetocHelper::MonthArticles();
    echo '</div>';
}

if ($visTMA == "SHOW") {
    echo '<div class="button" onclick="showHideThreeMonts()" align="center"><hr><b><a>' . $threemonts . '</a></b></div>';
    echo '<div id="threemontsdiv" style="display: none">';
    echo ModMetocHelper::ThreeMontsArticles();
    echo '</div>';
}

if ($visLA == "SHOW") {
    echo '<div class="button" onclick="showHideLatest()" align="center"><hr><b><a>' . $lastnews . '</a></b></div>';
    echo '<div id="latestdiv" style="display: none">';
    echo ModMetocHelper::LatestArticles();
    echo '</div>';
}
echo ModMetocHelper::closeCoToDa();
