<?php

class Gallery
{
    /**
     * @param $name
     * @param array $token
     * @param array $user
     */
    public static function addSession($name, $token, $user, $auth)
    {
        $_SESSION[$name] = [
            'tokenType' => $token['token_type'],
            'accessToken' => $token['access_token'],
            'expiresIn' => $token['expires_in'],
            'refreshToken' => $token['refresh_token'],
            'authorization' => $auth,
            'firstName' => $user['first_name'],
            'lastName' => $user['last_name'],
            'emails' => $user['emails'][0]
        ];
    }

    public static function getSessionName($name)
    {
        return $_SESSION[$name];
    }

    /**
     * @param $token
     * @param int $limit
     * @param string $type
     * @return mixed
     */
    public static function getYaDiskFile($token, $limit, $type, $sort)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://cloud-api.yandex.net/v1/disk/resources/files?' . 'media_type=' . $type . '&sort=' . $sort . '&limit=' . $limit = ($limit > 0) ? $limit : null);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $token));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $yaDiskFile = json_decode(curl_exec($curl), true);
        curl_close($curl);
        return $yaDiskFile["items"];
    }

    /**
     * @param $code
     * @param $client_id
     * @param $client_secret
     * @return mixed
     */
    public static function getYaToken($code, $client_id, $client_secret)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://oauth.yandex.ru/token');
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, 'grant_type=authorization_code&code=' . $code . '&client_id=' . $client_id . '&client_secret=' . $client_secret);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $yaTokenInfo = json_decode(curl_exec($curl), true);
        curl_close($curl);
        return $yaTokenInfo;
    }

    /**
     * @param $token
     * @param $db
     * @param $limit
     * @param $type
     * @param $sort
     * @return mixed
     */
    public static function getYaDiskFilesArray($token, $db, $limit, $type, $sort, $email)
    {
        $yaDiskFile = self::getYaDiskFile($token, $limit, $type, $sort);

        if (is_array(self::isFilledTable($db, 'rateit', $email))) {
            $rateitList = self::getRateit($db, $email);

            foreach ($rateitList as $rateit) {
                $seachValueInRateitList = $rateit['image_id'];
                $resultArrya = array_filter($yaDiskFile, function ($val) use ($seachValueInRateitList) {
                    return $val['md5'] == $seachValueInRateitList;
                });
                $arrayKeysResultArray = array_keys($resultArrya);
                foreach ($arrayKeysResultArray as $key) {
                    $yaDiskFile[$key]['rateit'] = $rateit['value'];
                }
            }
        }
        return $yaDiskFile;
    }

    protected static function sortArrayASC($a, $b)
    {
        return ($a['rateit'] < $b['rateit']);
    }

    protected static function sortArrayDESC($a, $b)
    {
        return ($a['rateit'] > $b['rateit']);
    }

    public static function sortRateit($array, $order)
    {
        $cmp_function = ($order === 'ask') ? 'self::sortArrayASC' : 'self::sortArrayDESC';
        uasort($array, $cmp_function);
        return $array;
    }

    public static function getAccessToken($session_name)
    {
        return $_SESSION[$session_name]['accessToken'];
    }

    /**
     * @param $db
     * @param $table
     * @return mixed
     */
    public static function isFilledTable($db, $table, $email)
    {
        $sql = 'SELECT id FROM ' . $table . ' WHERE email = :email';
        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();
        return $result->fetch();
    }

    public static function getRateit($db, $email)
    {

        $sql = 'SELECT id, image_id, value FROM rateit WHERE email = :email';
        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();

        $i = 0;
        $rateitList = array();

        while ($row = $result->fetch()) {
            $rateitList[$i]['id'] = $row['id'];
            $rateitList[$i]['image_id'] = $row['image_id'];
            $rateitList[$i]['value'] = $row['value'];
            $i++;
        }

        return $rateitList;
    }

    public static function saveRateit($db, $image_id, $value, $email)
    {

        $sql = 'INSERT INTO rateit (image_id, value, email) '
            . 'VALUES (:image_id, :value, :email)';
        $result = $db->prepare($sql);
        $result->bindParam(':image_id', $image_id, PDO::PARAM_STR);
        $result->bindParam(':value', $value, PDO::PARAM_INT);
        $result->bindParam(':email', $email, PDO::PARAM_STR);

        if ($result->execute()) {
            return true;
        };
    }

    public static function addDefaultRateit($array){
        foreach ($array as $key => $value){
            if(!isset($value['rateit'])) {
                $array[$key]['rateit'] = 0;
            }
        }

        return $array;
    }
}
