<?php

defined('_JEXEC') or die('Unauthorized access');                                // Unauthorized access protection

class ModMetocHelper {

    public static function coToDa() {                                           // connect to database
        $config = JFactory::getConfig();
        $host = $config->get('host');
        $user = $config->get('user');
        $password = $config->get('password');
        $db = $config->get('db');


        $conn = mysqli_connect("$host", "$user", "$password", "$db");
        if (mysqli_connect_errno()) {
            echo "Canot create connection to MySQL: " . mysqli_connect_error();
        }return $conn;
    }

// navrat premennej conn

    public static function conTab() {                                           // creatie name of content tab
        $config = JFactory::getConfig();
        $dbprefix = $config->get('dbprefix');
        $cont = $dbprefix . 'content';
        return $cont;
    }

    public static function closeCoToDa() {                                      // cloce connection to database
        mysqli_close(ModMetocHelper::coToDa());
    }

    public static function AddCountOfWords() {                                  // add column count_of_words to content tab
        $conn = ModMetocHelper::coToDa();
        $cont = ModMetocHelper::conTab();

        $sql = "ALTER TABLE $cont ADD count_of_words INT(10)";
        $result = $conn->query($sql);
    }

    public static function UpdateCountOfWords() {                               // add values to column count_of_words
        $conn = ModMetocHelper::coToDa();
        $cont = ModMetocHelper::conTab();

        $sql = "UPDATE $cont SET $cont.count_of_words=(LENGTH($cont.introtext) - LENGTH(REPLACE($cont.introtext, ' ', ''))+1 + LENGTH($cont.fulltext) - LENGTH(REPLACE($cont.fulltext, ' ', ''))) WHERE $cont.count_of_words IS NULL";
        $result = $conn->query($sql);
    }

    public static function AddUniCountOfWords() {                               // add column uni_count_of_words to content tab
        $conn = ModMetocHelper::coToDa();
        $cont = ModMetocHelper::conTab();

        $sql = "ALTER TABLE $cont ADD uni_count_of_words INT(10)";
        $result = $conn->query($sql);
    }

    public static function UpdateUniCountOfWords() {                            // add values to column uni_count_of_words
        $conn = ModMetocHelper::coToDa();
        $cont = ModMetocHelper::conTab();
        $sql = "SELECT `id`, `introtext`, `fulltext` FROM $cont";
        $result = $conn->query($sql);


        if ($result->num_rows > 0) {
            ini_set('max_execution_time', 100);                                 // extend max time of processing
            while ($row = $result->fetch_assoc()) {
                $ucow = count(array_unique(str_word_count(($row['introtext'] . ' ' . $row['fulltext']), 1))); //upravit
                $id = $row['id'];
                $sqlUpdate = "UPDATE $cont SET $cont.uni_count_of_words=($ucow) WHERE $cont.uni_count_of_words IS NULL AND $cont.id=$id";

                $conn->query($sqlUpdate);
            }
        }
        ini_set('max_execution_time', 30);                                      // set max time to 30 second
    }

    public static function Hidi() {

        $conn = ModMetocHelper::coToDa();
        $cont = ModMetocHelper::conTab();

        $module = JModuleHelper::getModule('mod_metoc');
        $params = new JRegistry($module->params);
        $lim = $params->get('NumberHidi');

        $sql = "SELECT id, title, (hits/DATEDIFF(CURRENT_DATE+1, publish_up)) AS hidi FROM $cont ORDER BY hidi DESC LIMIT $lim";  // intelligent ordering "HiDi"
        $result = $conn->query($sql);
        echo '<hr>';
        if ($result == NULL) {
            
        } else if ($result->num_rows > 0) {
            echo '<ol>';

            while ($row = $result->fetch_assoc()) {
                echo '<li>' . '<a href="/index.php?view=article&id=' . $row['id'] . '">' . $row['title'] . '</a></li>';
            } echo '</ol>';
        }
    }

    public static function Wora() {

        $conn = ModMetocHelper::coToDa();
        $cont = ModMetocHelper::conTab();

        $module = JModuleHelper::getModule('mod_metoc');
        $params = new JRegistry($module->params);
        $lim = $params->get('NumberWora');
        $sql = "SELECT id, title, uni_count_of_words*(uni_count_of_words/count_of_words)*(hits/DATEDIFF(CURRENT_DATE+1, publish_up)) AS wora FROM $cont ORDER BY wora DESC LIMIT $lim";  // intelligent ordering "WoRa"
        $sql = "SELECT * FROM $cont LIMIT $lim";
        $result = $conn->query($sql);
        echo '<hr>';
        if ($result == NULL) {
            
        } else if ($result->num_rows > 0) {
            echo '<ol>';

            while ($row = $result->fetch_assoc()) {
                echo '<li>' . '<a href="/index.php?view=article&id=' . $row['id'] . '">' . $row['title'] . '</a></li>';
            } echo '</ol>';
        }
    }

    public static function TodayArticles() {

        $conn = ModMetocHelper::coToDa();
        $cont = ModMetocHelper::conTab();

        $module = JModuleHelper::getModule('mod_metoc');
        $params = new JRegistry($module->params);
        $lim = $params->get('NumberToday');

        $sql = "SELECT id, title, hits FROM $cont WHERE DATE(publish_up)=CURDATE() ORDER BY hits DESC LIMIT $lim"; // today most-read articles
        $result = $conn->query($sql);
        echo '<hr>';
        if ($result == NULL) {
            
        } else if
        ($result->num_rows > 0) {
            echo '<ol>';
            while ($row = $result->fetch_assoc()) {
                echo '<li>' . '<a href="/index.php?view=article&id=' . $row['id'] . '">' . $row['title'] . '</a> (' . $row['hits'] . 'x)</li>';
            } echo '</ol>';
        }
    }

    public static function WeekArticles() {

        $conn = ModMetocHelper::coToDa();
        $cont = ModMetocHelper::conTab();

        $module = JModuleHelper::getModule('mod_metoc');
        $params = new JRegistry($module->params);
        $lim = $params->get('NumberWeek');


        $sql = "SELECT id, title, hits FROM $cont WHERE DATE(publish_up)>CURDATE() - INTERVAL 7 DAY ORDER BY hits DESC LIMIT $lim"; // most-read articles of week
        $result = $conn->query($sql);
        echo '<hr>';
        if ($result == NULL) {
            
        } else if ($result->num_rows > 0) {
            echo '<ol>';
            while ($row = $result->fetch_assoc()) {
                echo '<li>' . '<a href="/index.php?view=article&id=' . $row['id'] . '">' . $row['title'] . '</a> (' . $row['hits'] . 'x)</li>';
            } echo '</ol>';
        }
    }

    public static function MonthArticles() {

        $conn = ModMetocHelper::coToDa();
        $cont = ModMetocHelper::conTab();

        $module = JModuleHelper::getModule('mod_metoc');
        $params = new JRegistry($module->params);
        $lim = $params->get('NumberMonth');

        $sql = "SELECT id, title, hits FROM $cont WHERE DATE(publish_up)>CURDATE() - INTERVAL 1 MONTH ORDER BY hits DESC LIMIT $lim"; // most-read articles of month
        $result = $conn->query($sql);
        echo '<hr>';
        if ($result == NULL) {
            
        } else if ($result->num_rows > 0) {
            echo '<ol>';

            while ($row = $result->fetch_assoc()) {
                echo '<li>' . '<a href="/index.php?view=article&id=' . $row['id'] . '">' . $row['title'] . '</a> (' . $row['hits'] . 'x)</li>';
            } echo '</ol>';
        }
    }

    public static function ThreeMontsArticles() {

        $conn = ModMetocHelper::coToDa();
        $cont = ModMetocHelper::conTab();

        $module = JModuleHelper::getModule('mod_metoc');
        $params = new JRegistry($module->params);
        $lim = $params->get('NumberThreeMonts');

        $sql = "SELECT id, title, hits FROM $cont WHERE DATE(publish_up)>CURDATE() - INTERVAL 3 MONTH ORDER BY hits DESC LIMIT $lim"; // most-read articles of last three monts
        $result = $conn->query($sql);
        echo '<hr>';
        if ($result == NULL) {
            
        } else if ($result->num_rows > 0) {
            echo '<ol>';
            while ($row = $result->fetch_assoc()) {
                echo '<li>' . '<a href="/index.php?view=article&id=' . $row['id'] . '">' . $row['title'] . '</a> (' . $row['hits'] . 'x)</li>';
            } echo '</ol>';
        }
    }

    public static function LatestArticles() {

        $conn = ModMetocHelper::coToDa();
        $cont = ModMetocHelper::conTab();

        $module = JModuleHelper::getModule('mod_metoc');
        $params = new JRegistry($module->params);
        $lim = $params->get('NumberLatest');

        $sql = "SELECT id, title FROM $cont ORDER BY publish_up DESC LIMIT $lim"; // latest articles
        $result = $conn->query($sql);
        echo '<hr>';
        if ($result == NULL) {
            
        } else if ($result->num_rows > 0) {
            echo '<ol>';
            while ($row = $result->fetch_assoc()) {
                echo '<li>' . '<a href="/index.php?view=article&id=' . $row['id'] . '">' . $row['title'] . '</a></li>';
            } echo '</ol>';
        }
    }

}
