<?php


class SiteController extends Controller
{

    public function actionIndex()
    {

        if(!isset($_SESSION[$this->config->getSessionName()])){
            $yaAuthorization = $this->config->getAuthorization();
            $client_id = $this->config->getClientId();
        }else {
            $firstName = $_SESSION[$this->config->getSessionName()]['firstName'];
            $lastName = $_SESSION[$this->config->getSessionName()]['lastName'];
            $email = $_SESSION[$this->config->getSessionName()]['emails'];
            $yaDiskFileLimit = $this->config->getLimit();
            $yaDiskFileType = $this->config->getType();
            $yaDiskFileSort = $this->config->getSort();

            $accessToken = Gallery::getAccessToken($this->config->getSessionName());

            if(isset($accessToken)) {
                $db = Db::getConnection();
                $yaAuthorization = $_SESSION[$this->config->getSessionName()]['authorization'];
                $yaDiskFileRateit = (is_array(Gallery::isFilledTable($db, 'rateit', $email)))? true : false;
                $array = Gallery::getYaDiskFilesArray($accessToken, $db, $yaDiskFileLimit, $yaDiskFileType, $yaDiskFileSort, $email);
                $yaDiskFile = Gallery::addDefaultRateit($array);
            }
        }

        require_once(ROOT . '/views/site/index.php');
        return true;
    }

    public function actionGalleryAjax(){

        if (isset($_POST)) {
            $yaDiskFileType = $this->config->getType();
            $yaDiskFileLimit = (isset($_POST['page']) ? $_POST['page'] : $this->config->getLimit());
            $yaDiskFileSort = (isset($_POST['sortname'])? $_POST['sortname'] : $this->config->getSort());
            $email = $_SESSION[$this->config->getSessionName()]['emails'];
            if (isset($_POST['rateit'])) $yaDiskFileRateit = $_POST['rateit'];

            if (isset($_SESSION[$this->config->getSessionName()])) {
                $db = Db::getConnection();
                $accessToken = Gallery::getAccessToken($this->config->getSessionName());
                if (isset($accessToken)) {

                    if(isset($_POST['page']) && !isset($_POST['rateit']) || empty($_POST['rateit'])) {
                        $array = Gallery::getYaDiskFilesArray($accessToken, $db, $yaDiskFileLimit, $yaDiskFileType, $yaDiskFileSort, $email);
                        $yaDiskFile = Gallery::addDefaultRateit($array);

                        echo json_encode($yaDiskFile);
                        return true;
                    }

                    if(isset($_POST['page']) && isset($_POST['rateit']) && !empty($_POST['rateit'])) {

                        $newArrayRateit = [];
                        $array = Gallery::getYaDiskFilesArray($accessToken, $db, $yaDiskFileLimit, $yaDiskFileType, $yaDiskFileSort, $email);
                        $arrayRateit = Gallery::addDefaultRateit($array);
                        $yaDiskFile = Gallery::sortRateit($arrayRateit, $yaDiskFileRateit);

                        foreach ($yaDiskFile as $value) {
                            $newArrayRateit[] = $value;
                        }
                        echo json_encode($newArrayRateit);
                        return true;

                    }
                }
            }
        }
        return true;
    }

    public function actionRateitSave(){
        $db = Db::getConnection();
        $image_id = $_POST['image_id'];
        $value = $_POST['value'];
        $email = $_SESSION[$this->config->getSessionName()]['emails'];
        if(isset($image_id) && isset($value)){
            Gallery::saveRateit($db, $image_id, $value, $email);
        }
        return true;
    }


    public function actionExit(){
        session_destroy();
        header('Location: /');
    }


}
