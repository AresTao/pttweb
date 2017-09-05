<?php
class SessionManager
{
    private static $instance;

    /**
     * starts a php session
     * @return void
     */
    public static function startSession()
    {
        if (!isset(self::$instance)) {
            self::$instance = new SessionManager_obj();
        }
    }

    /**
     * @return SessionManager_obj
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new SessionManager_obj();
        }
        return self::$instance;
    }


}

class SessionManager_obj
{
    public function __construct()
    {
        session_start();
    }

    /**
     * Checks if the visitor is logged in to a mumble user account (on a specific server)
     * @return boolean
     */
    public function isUser()
    {
        if (isset($_SESSION['userLoggedIn'])) {
            return true;
        }
        return false;
    }

    /**
     * Check if the visitor is logged in as an interface admin
     * @return boolean
     */
    public function isAdmin()
    {
        //return true;

        if (isset($_SESSION['adminLoggedIn'])) {
            return true;
        }
        return false;
    }

    public function isOperator()
    {
        if (isset($_SESSION['operatorLoggedIn'])) {
            return true;
        }
        return false;
    }
    /**
     * Checks if the visitor is logged in as enterprise user
     * @return boolean
     */
    public function isEnterprise()
    {
        if (isset($_SESSION['enterpriseLoggedIn'])) {
            return true;
        }
        return false;
    }


    /**
     * get language to use
     * @return string
     */
    public function getLanguage()
    {
        return isset($_SESSION['language'])?$_SESSION['language']:null;
    }

    /**
     *
     * @param string $name
     * @param string $pw
     * @return void
     * @throws Exception on failed login
     */
    public function loginAsAdmin($name, $pw)
    {
        if (DBManager::getInstance()->checkAdminLogin($_POST['username'], $_POST['password'])) {
            $_SESSION['adminLoggedIn'] = true;
            $admin = DBManager::getInstance()->getAdminByName($name);
            $_SESSION['adminLoggedInAs'] = $admin['id'];
            $_SESSION['loggedInName'] = $name;
            $_SESSION['loggedInId'] = $admin['id'];
            $_SESSION['level'] = 0;
        } else {
            Logger::log("[{$_SERVER['REMOTE_ADDR']}] failed to log in as admin $name.", Logger::LEVEL_SECURITY);
            throw new Exception('管理员账号或密码错误！');
        }
    }
    /**
     *
     * @param string $name
     * @param string $pw
     * @return void
     * @throws Exception on failed login
     */
    public function loginAsEnterprise($name, $pw)
    {
        if (MysqlInterface::checkEnterpriseLogin($name, $pw)) {
            $_SESSION['enterpriseLoggedIn'] = true;
            $enterprise = MysqlInterface::getEnterpriseByName($name);
            $_SESSION['enterpriseLoggedInAs'] = $enterprise['id'];
            $_SESSION['loggedInName'] = $name;
            $_SESSION['loggedInId'] = $enterprise['id'];
            $_SESSION['level'] = $enterprise['type'];
        } else {
            //Logger::log("[{$_SERVER['REMOTE_ADDR']}] failed to log in as admin $name.", Logger::LEVEL_SECURITY);
            throw new Exception('用户名或密码错误');
        }
    }
    public function loginAsEnterpriseById($id, $pw)
    {
        if (MysqlInterface::checkEnterpriseLoginById($id, $pw)) {
            $_SESSION['enterpriseLoggedIn'] = true;
            $enterprise = MysqlInterface::getEnterpriseById($id);
            $_SESSION['enterpriseLoggedInAs'] = $enterprise['id'];
            $_SESSION['loggedInName'] = $enterprise['account'];
            $_SESSION['loggedInId'] = $enterprise['id'];
            $_SESSION['level'] = $enterprise['type'];
        } else {
            //Logger::log("[{$_SERVER['REMOTE_ADDR']}] failed to log in as admin $name.", Logger::LEVEL_SECURITY);
            throw new Exception('用户名或密码错误');
        }
    }
    /**
     *
     * @param string $name
     * @param string $pw
     * @return void
     * @throws Exception on failed login
     */
    public function loginAsOperator($name, $pw)
    {
        if (MysqlInterface::checkOperatorLogin($_POST['username'], $_POST['password'])) {
            $_SESSION['operatorLoggedIn'] = true;
            $operator = MysqlInterface::getOperatorByName($name);
            $_SESSION['operatorLoggedInAs'] = $operator['id'];
            $_SESSION['loggedInName'] = $name;
            $_SESSION['loggedInId'] = $operator['id'];
            $_SESSION['level'] = $operator['type'];
        } else {
            //Logger::log("[{$_SERVER['REMOTE_ADDR']}] failed to log in as admin $name.", Logger::LEVEL_SECURITY);
            throw new Exception('代理商用户名或密码错误');
        }
    }

    public function adminLogOut()
    {
        unset($_SESSION['adminLoggedIn']);
        unset($_SESSION['adminLoggedInAs']);
        unset($_SESSION['loggedInName']);
        unset($_SESSION['loggedInId']);
    }

    public function operatorLogOut()
    {
        unset($_SESSION['operatorLoggedIn']);
        unset($_SESSION['operatorLoggedInAs']);
        unset($_SESSION['loggedInName']);
        unset($_SESSION['loggedInId']);
    }
    public function enterpriseLogOut()
    {
        unset($_SESSION['enterpriseLoggedIn']);
        unset($_SESSION['enterpriseLoggedInAs']);
        unset($_SESSION['loggedInName']);
        unset($_SESSION['loggedInId']);
    }
    public function getAdminID()
    {
        if (isset($_SESSION['adminLoggedInAs']))
            return $_SESSION['adminLoggedInAs'];
        throw new Exception('Tried to get admin id when not logged in.');
    }
    public function getOperatorID()
    {
        if (isset($_SESSION['operatorLoggedInAs']))
            return $_SESSION['operatorLoggedInAs'];
        throw new Exception('Tried to get operator id when not logged in.');
    }
    public function getEnterpriseID()
    {
        if (isset($_SESSION['enterpriseLoggedInAs']))
            return $_SESSION['enterpriseLoggedInAs'];
        throw new Exception('Tried to get enterprise id when not logged in.');
    }
    public function getLoginName()
    {
        if (isset($_SESSION['loggedInName']))
            return $_SESSION['loggedInName'];
    }
    public function getLoginId()
    {
        if (isset($_SESSION['loggedInId']))
            return $_SESSION['loggedInId'];
    }
    public function getLevel()
    {
        if (isset($_SESSION['level']))
            return $_SESSION['level'];
    }
}
