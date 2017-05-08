<?php
include_once 'psl-config.php';
function redirect($url)
{
    echo '<script type="text/javascript">'
           , 'window.location.replace("'.$url.'");'
       , '</script>';
}
function sec_session_start()
{
    $session_name = 'sec_session_id';
    $secure       = SECURE;
    $httponly     = true;
    if (ini_set('session.use_only_cookies', 1) === false) {
        header("Location: ../login.php?err=ini");
        exit();
    }
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params(60 * 60 * 2, $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
    session_name($session_name);
    session_start();
    session_regenerate_id(true);
}
function rememberLogin($id, $token, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT token
                             FROM remember_sessions
                             WHERE user = ?
                             AND expire >= CURDATE()")) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $results = $stmt->get_result();
        while ($value = $results->fetch_assoc()) {
            if ($token == $value['token']) {
                if ($stmt = $mysqli->prepare("SELECT firstName, lastName, role, password
          FROM members
          WHERE userID = ?
          LIMIT 1")) {
                    $stmt->bind_param('i', $id);
                    $stmt->execute();
                    $stmt->store_result();
                    $stmt->bind_result($fName, $lName, $role, $db_password);
                    $stmt->fetch();
                    $user_browser             = $_SERVER['HTTP_USER_AGENT'];
                    $user_id                  = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['user_id']      = $id;
                    $fName                    = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $fName);
                    $lName                    = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $lName);
                    $_SESSION['fName']        = $fName;
                    $_SESSION['lName']        = $lName;
                    $_SESSION['login_string'] = hash('sha512', $db_password . $user_browser);
                    if (isset($_SESSION['return'])) {
                        $return = $_SESSION['return'];
                        unset($_SESSION['return']);
                        header('Location: ..' . $return);
                    } else {
                        header('Location: ../home.php?p=home');
                    }
                }
            }
        }
    }
    echo $mysqli->error;
}
function login($email, $password, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT userID, firstName, lastName, role, password, salt
        FROM members
        WHERE email = ?
        LIMIT 1")) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id, $fName, $lName, $role, $db_password, $salt);
        $stmt->fetch();
        $password = hash('sha512', $password . $salt);
        if ($stmt->num_rows == 1) {
            if (checkbrute($user_id, $mysqli) == true) {
                return 2;
            } else {
                if ($db_password == $password) {
                    $user_browser             = $_SERVER['HTTP_USER_AGENT'];
                    $user_id                  = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['user_id']      = $user_id;
                    $fName                    = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $fName);
                    $lName                    = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $lName);
                    $_SESSION['fName']        = $fName;
                    $_SESSION['lName']        = $lName;
                    $_SESSION['login_string'] = hash('sha512', $password . $user_browser);
                    return 3;
                } else {
                    $now = time();
                    $mysqli->query("INSERT INTO login_attempts(user_id, time)
                                    VALUES ('$user_id', '$now')");
                    return 1;
                }
            }
        } else {
            return 5;
        }
    }
    return 6;
}
function getSectionPage($id, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT pageID
                                  FROM sections
                                  WHERE id = ?
                                  LIMIT 1")) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($page);
        $stmt->fetch();
        $stmt->close();
        return $page;
    } else {
        echo $mysqli->error;
        return false;
    }
}
function getParent($id, $type, $mysqli)
{

    /* Create a prepared statement */
    if ($stmt = $mysqli->prepare("SELECT parent FROM " . $type . " WHERE id=?")) {

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();

        /* Close statement */
        $stmt->close();
    } else {
        echo $mysqli->error;
    }

    /* Close connection */
    return $result;
}

function getFirstPage($id, $type, $mysqli)
{

    /* Create a prepared statement */
    if ($stmt = $mysqli->prepare("SELECT id FROM " . $type . " WHERE parent=? AND priority=1 LIMIT 1")) {

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();

        /* Close statement */
        $stmt->close();
    }
    if (!isset($result)) {
        $result = -1;
    }

    /* Close connection */
    return $result;
}

function getTestPriority($parent, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT priority FROM sections WHERE pageID=?")) {
        $stmt->bind_param("i", $parent);
        $stmt->execute();
        $stmt->store_result();
        $numrows = $stmt->num_rows;
        $numrows++;

        /* Close statement */
        $stmt->close();
    } else {
        echo $mysqli->error;
    }

    if (isset($numrows)) {
        return $numrows;
    }
    return 0;
}

function getPriority($parent, $type, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT priority FROM " . $type . " WHERE parent=?")) {

        $stmt->bind_param("i", $parent);
        $stmt->execute();
        $stmt->store_result();
        $numrows = $stmt->num_rows;
        $numrows++;

        /* Close statement */
        $stmt->close();
    }

    if (isset($numrows)) {
        return $numrows;
    }
    return false;
}

function getSectionPriority($pageID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT priority FROM sections WHERE pageID = ?")) {
        $stmt->bind_param("i", $pageID);
        $stmt->execute();
        $stmt->store_result();
        $numrows = $stmt->num_rows;
        $numrows++;
        $stmt->close();
    }

    if (!empty($numrows)) {
        return $numrows;
    }
    return false;
}

function getAvailableSection($mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT id FROM pages ORDER BY id DESC LIMIT 1")) {
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();

        /* Close statement */
        $stmt->close();
        if (empty($result)) {
            $result = 0;
        }
        return ++$result;
    }

    return false;
}

function getAvailablePage($mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT id FROM pages ORDER BY id DESC LIMIT 1")) {
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();

        /* Close statement */
        $stmt->close();
        if (empty($result)) {
            $result = 0;
        }
        return ++$result;
    }

    return false;
}

function verifyID($id, $type, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT title FROM " . $type . " WHERE id=?")) {

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();
        $numrows = $stmt->num_rows;

        /* Close statement */
        $stmt->close();
    }

    if (isset($numrows)) {
        if ($numrows == 1) {
            return true;
        }
    }
    return false;
}

function getTitle($id, $type, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT title FROM " . $type . " WHERE id=?")) {

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();

        /* Close statement */
        $stmt->close();
    }

    /* Close connection */
    return $result;
}

function getSubject($id, $mysqli)
{
    return getParent(getParent($id, 'pages', $mysqli), 'units', $mysqli);
}

function updateLastViewed($page, $subject, $mysqli)
{
    if ($stmt = $mysqli->prepare("UPDATE lastViewed
                                  SET pageID = ?
                                  WHERE userID = ? AND subjectID = ?")) {
        $stmt->bind_param('iii', $page, $_SESSION['user_id'], $subject);
        $stmt->execute();
        return true;
    } else {
        echo $mysqli->error;
        return false;
    }
}

function insertFirstPage($subject, $mysqli)
{
    $page = getFirstPage(getFirstPage($subject, "units", $mysqli), "pages", $mysqli);
    if ($page == -1) {
        return false;
    }
    $mysqli->query("INSERT INTO lastViewed (userID, subjectID, pageID) VALUES ('" . $_SESSION['user_id'] . "','" . $subject . "','" . $page . "')");
    return $page;
}

function getLastViewed($subject, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT pageID
                             FROM lastViewed
                             WHERE UserID = ? AND subjectID = ? LIMIT 1")) {
        $stmt->bind_param('ii', $_SESSION['user_id'], $subject);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();
    }
    if (!isset($result)) {
        $result = insertFirstPage($subject, $mysqli);
    }
    return $result;
}

function checkbrute($user_id, $mysqli)
{
    $now            = time();
    $valid_attempts = $now - (2 * 60 * 60);
    if ($stmt = $mysqli->prepare("SELECT time
                             FROM login_attempts
                             WHERE userID = ?
                            AND time > '$valid_attempts'")) {
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 5) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }
}
function toggleActive($id, $type, $mysqli)
{
    if ($stmt = $mysqli->prepare("UPDATE " . $type . "
                             SET active = !active
                             WHERE id = ?")) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return true;
    }
    return false;
}
function getSubjectTitle($id, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT title
                             FROM subjects
                             WHERE id = ?")) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        return $result;
    }
    return false;
}
function enrollmentExists($id, $approved, $mysqli)
{
    $fetchStudents = $mysqli->prepare("SELECT userID FROM enrollments WHERE subjectID=? and approved=?");
    $fetchStudents->bind_param("ii", $id, $approved);
    $fetchStudents->execute();
    $studentResults = $fetchStudents->get_result();
    while ($students = $studentResults->fetch_assoc()) {
        return true;
    }
    return false;
}
function validateSectionID($id, $parent, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT Id FROM sections WHERE Id = ? AND pageID = ?")) {
        $stmt->bind_param('ii', $id, $parent);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows==1){
            $stmt->close();
            return true;
        }
        echo "Invalid Data";
        $stmt->close();
        return false;
    } else {
        echo "MySQLi error: validateSectionID";
        echo $mysqli->error;
        return false;
    }
}
function getSubjectClassCompletitions($subject, $mysqli)
{
    $units = array();
    $stmt = $mysqli->prepare("SELECT id,title 
                              FROM units
                              WHERE parent=? 
                              AND active=1
                              ORDER BY priority ASC");
    $stmt->bind_param('i', $subject);
    $stmt->execute();
    $unitResults = $stmt->get_result();
    while ($value = $unitResults->fetch_assoc()) {
        array_push($units, $value['id']);
        array_push($units, $value['title']);
    }
    $stmt->close();

    $result = array();
    //index by 2, no foreach
    // LOL, i changed the style so it no longer indexes by 2, but im too lazy to change it to a for loop
    for ($i = 0; $i < count($units); $i+=2) {
        $unitTotal = 0;
        $result[$i/2][0] = $units[$i];
        $result[$i/2][1] = $units[$i+1];
        $result[$i/2][2] = array();
        $stmt = $mysqli->prepare("SELECT id,title 
                                  FROM pages 
                                  WHERE parent=? 
                                  AND active=1 
                                  AND type=3
                                  ORDER BY priority ASC");
        $stmt->bind_param('i', $units[$i]);
        $stmt->execute();
        $pageResults = $stmt->get_result();
        $a = 0;
        while ($value = $pageResults->fetch_assoc()) {
            $result[$i/2][2][$a][0] = $value['id'];
            $result[$i/2][2][$a][1] = $value['title'];
            $result[$i/2][2][$a][2] = array();
            $a++;
        }
        $stmt->close();
        
        foreach ($result[$i/2][2] as $master => $key) {
            $pageTotal = 0;
            if ($stmt = $mysqli->prepare("SELECT id, type, title
                                      FROM sections
                                      WHERE pageID = ?
                                      AND active = 1
                                      ORDER BY priority ASC")) {
                $stmt->bind_param('i', $key[0]);
                $stmt->execute();
                $sectionResults = $stmt->get_result();
                $ind = 0;
                while ($value = $sectionResults->fetch_assoc()) {
                    $result[$i/2][2][$master][2][$ind][0] = $value['id'];
                    $result[$i/2][2][$master][2][$ind][1] = $value['title'];
                    $result[$i/2][2][$master][2][$ind][2] = $value['type'];
                    $ind++;
                }
                $stmt->close();
                foreach ($result[$i/2][2][$master][2] as $secID => $secDat) {
                    $stmt = $mysqli->prepare("SELECT id
                                              FROM completions
                                              WHERE pageID = ?
                                              AND graded=0
                                              AND retake=0");
                    $stmt->bind_param('i', $secDat['0']);
                    $stmt->execute();
                    $stmt->store_result();
                    $result[$i/2][2][$master][2][$secID][3] = $stmt->num_rows;
                    $pageTotal += $stmt->num_rows;
                    $stmt->close();
                }
            } else {
                echo $mysqli->error;
            }
            $result[$i/2][2][$master][3]=$pageTotal;
            $unitTotal+=$pageTotal;
        }
        $result[$i/2][3]=$unitTotal;
    }
    return $result;
}
function getTeacherClasses($id, $role, $mysqli)
{
    $classArray = array();
    if ($role == 2) {
        $fetchClass = $mysqli->prepare("SELECT title,id FROM subjects WHERE active=1");
    } else {
        $fetchClass = $mysqli->prepare("SELECT title,id FROM subjects WHERE teacherID=? AND active=1");
        $fetchClass->bind_param('i', $id);
    }
    $fetchClass->execute();
    $classResults = $fetchClass->get_result();
    while ($value = $classResults->fetch_assoc()) {
        array_push($classArray, $value['id']);
        array_push($classArray, $value['title']);
    }
    $fetchClass->close();
    return $classArray;
}
/*
function getNextSection($priority, $parent, $mysqli)
{

    if ($stmt = $mysqli->prepare("SELECT Id FROM sections WHERE priority = ? AND pageID = ?")) {
        $stmt->bind_param('ii', $priority, $parent);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows==1){
            $stmt->bind_result($return);
            $stmt->fetch();
            $stmt->close();
            return $return;
        }
        $stmt->close();
        return -1;
    } else {
        echo $mysqli->error;
        return false;
    }
}*/
function getNextSection($priority, $parent, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT id
                                  FROM sections
                                  WHERE pageID = ?
                                  AND priority >= ?
                                  ORDER BY priority ASC")) {
        $stmt->bind_param('ii', $parent, $priority);
        $stmt->execute();
        $resultArray = array();
        $results = $stmt->get_result();
        while ($value = $results->fetch_assoc()) {
            array_push($resultArray, $value['id']);
        }
        $stmt->close();
        foreach ($resultArray as $value => $id) {
            if ($stmt = $mysqli->prepare("SELECT retake
                                          FROM completions
                                          WHERE userID = ?
                                          AND pageID = ?
                                          ORDER BY timestamp DESC
                                          LIMIT 1")) {
                $stmt->bind_param('ii', $_SESSION['user_id'], $id);
                $stmt->execute();

                $stmt->store_result();
                if ($stmt->num_rows == 0) {
                    $stmt->close();
                    return $id;
                }
                $stmt->bind_result($testRetake);
                $stmt->fetch();
                if ($testRetake) {
                    return $id;
                }
                $stmt->close();
            } else {
                echo $mysqli->error;
            }
        }
        return -1;
    } else {
        echo $mysqli->error;
    }
}
function getKeyID($subjectID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT id
                             FROM announcementKeys
                             WHERE subjectID = ?")) {
        $stmt->bind_param('i', $subjectID);
        $stmt->execute();
        $results = $stmt->get_result();
        $result  = array();
        while ($value = $results->fetch_assoc()) {
            array_push($result, $value['id']);
        }
        $stmt->close();
        return $result;
    }
    return false;
}
function keyExists($annID, $subjectID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT subjectID
                             FROM announcementKeys
                             WHERE id = ?")) {
        $stmt->bind_param('i', $annID);
        $stmt->execute();
        $results = $stmt->get_result();
        $result  = false;
        while ($value = $results->fetch_assoc()) {
            if ($value['subjectID'] == $subjectID) {
                $result = true;
            }
        }
        $stmt->close();
        return $result;
    }
    return false;
}
function getClasses($id, $mysqli)
{
    $classArray = array();
    $fetchClass = $mysqli->prepare("SELECT subjectID FROM enrollments WHERE userID=?");
    $fetchClass->bind_param('i', $id);
    $fetchClass->execute();
    $classResults = $fetchClass->get_result();
    while ($value = $classResults->fetch_assoc()) {
        array_push($classArray, $value['subjectID']);
        array_push($classArray, getSubjectTitle($value['subjectID'], $mysqli));
    }
    return $classArray;
}
function getTeacher($id, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT teacherID
                             FROM subjects
                             WHERE id = ?")) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        return $result;
    }
    return false;
}
function getName($id, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT firstName, lastName
                             FROM members
                             WHERE userID = ?")) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($result1, $result2);
        $stmt->fetch();
        $stmt->close();
        return $result1 . " " . $result2;
    }
    return false;
}
function updateTitle($id, $title, $type, $mysqli)
{
    if ($stmt = $mysqli->prepare("UPDATE " . $type . "
                             SET title = ?
                             WHERE id = ?")) {
        $stmt->bind_param('si', $title, $id);
        $stmt->execute();
        $stmt->close();
        return true;
    }
    return false;
}
function submitEnroll($userID, $classID, $mysqli)
{
    if ($stmt = $mysqli->prepare("INSERT INTO enrollments
                             (userID, subjectID)
                              VALUES (?, ?)")) {
        $stmt->bind_param('ii', $userID, $classID);
        $stmt->execute();
        $stmt->close();
        return true;
    }
    return false;
}
function submitCompletion($userID, $pageID, $data, $type, $time, $mysqli)
{
    if ($stmt = $mysqli->prepare("INSERT INTO completions
                             (userID, pageID, data, type, time)
                              VALUES (?, ?, ?, ?, ?)")) {
        $stmt->bind_param('iisii', $userID, $pageID, $data, $type, $time);
        $stmt->execute();
        $stmt->close();
        return true;
    } else {
        echo $mysqli->error;
    }
    return false;
}
function checkCompletion($userID, $pageID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT id 
                                  FROM completions
                                  WHERE userID = ?
                                  AND pageID = ?")) {
        $stmt->bind_param('ii', $userID, $pageID);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows>=1){
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }
    echo $mysqli->error;
    return false;
}
function checkCompletionRetake($userID, $pageID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT id, retake FROM `completions` WHERE userID = ? and pageID = ? ORDER BY timestamp DESC LIMIT 1")) {
        $stmt->bind_param('ii', $userID, $pageID);
        $stmt->execute();
        $stmt->bind_result($id, $retake);
        $stmt->fetch();
        if ($retake) {
            $stmt->close();
            return true;
        } 
        $stmt->close();
        return false;
    } else {
        echo $mysqli->error;
    }
}
function getProfilePic($id, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT profilePic
                             FROM members
                             WHERE userID = ?")) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();
        if ($result == null) {
            $result = "/content/user/profilePics/null.png";
        }
        return $result;
    }
    return "/content/user/profilePics/null.png";
}
function getRole($mysqli)
{
    if (login_check($mysqli)) {
        if ($stmt = $mysqli->prepare("SELECT role
                             FROM members
                             WHERE userID = ?
               LIMIT 1")) {
            $stmt->bind_param('i', $_SESSION['user_id']);
            $stmt->execute();
            $stmt->bind_result($role);
            $stmt->fetch();
            $stmt->close();
            return $role;
        }
        return "ERROR";
    }
    return "NOT LOGGED IN";
}
function login_check($mysqli)
{
    if (isset($_SESSION['user_id'], $_SESSION['fName'], $_SESSION['lName'], $_SESSION['login_string'])) {
        $user_id      = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
        if ($stmt = $mysqli->prepare("SELECT password
                                      FROM members
                                      WHERE userID = ? LIMIT 1")) {
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($password);
                $stmt->fetch();
                $stmt->close();
                $login_check = hash('sha512', $password . $user_browser);
                if ($login_check == $login_string) {
                    return true;
                } else {
                    return false;
                }
            } else {
                $stmt->close();
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}
function esc_url($url)
{
    if ('' == $url) {
        return $url;
    }
    $url   = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url   = (string) $url;
    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }
    $url = str_replace(';//', '://', $url);
    $url = htmlentities($url);
    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);
    if ($url[0] !== '/') {
        return '';
    } else {
        return $url;
    }
}
