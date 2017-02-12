<?php
// **********************************************************************
//
// Copyright (c) 2003-2013 ZeroC, Inc. All rights reserved.
//
// This copy of Ice is licensed to you under the terms described in the
// ICE_LICENSE file included in this distribution.
//
// **********************************************************************
//
// Ice version 3.5.1
//
// <auto-generated>
//
// Generated from file `Murmur.ice'
//
// Warning: do not edit this file.
//
// </auto-generated>
//

require_once 'Ice/SliceChecksumDict.php';

if(!isset($Murmur__t_NetAddress))
{
    $Murmur__t_NetAddress = IcePHP_defineSequence('::Murmur::NetAddress', $IcePHP__t_byte);
}

if(!class_exists('Murmur_User'))
{
    class Murmur_User
    {
        public function __construct($session=0, $userid=0, $online=false, $channel=0, $name='', $nick='')
        {
            $this->session = $session;
            $this->userid = $userid;
            $this->online = $online;
            $this->channel = $channel;
            $this->name = $name;
            $this->nick = $nick;
        }

        public function __toString()
        {
            global $Murmur__t_User;
            return IcePHP_stringify($this, $Murmur__t_User);
        }

        public $session;
        public $userid;
        public $online;
        public $channel;
        public $name;
        public $nick;
    }

    $Murmur__t_User = IcePHP_defineStruct('::Murmur::User', 'Murmur_User', array(
        array('session', $IcePHP__t_int), 
        array('userid', $IcePHP__t_int), 
        array('online', $IcePHP__t_bool), 
        array('channel', $IcePHP__t_int), 
        array('name', $IcePHP__t_string), 
        array('nick', $IcePHP__t_string)));
}

if(!isset($Murmur__t_IntList))
{
    $Murmur__t_IntList = IcePHP_defineSequence('::Murmur::IntList', $IcePHP__t_int);
}

if(!class_exists('Murmur_Channel'))
{
    class Murmur_Channel
    {
        public function __construct($id=0, $name='', $temporary=false, $members='')
        {
            $this->id = $id;
            $this->name = $name;
            $this->temporary = $temporary;
            $this->members = $members;
        }

        public function __toString()
        {
            global $Murmur__t_Channel;
            return IcePHP_stringify($this, $Murmur__t_Channel);
        }

        public $id;
        public $name;
        public $temporary;
        public $members;
    }

    $Murmur__t_Channel = IcePHP_defineStruct('::Murmur::Channel', 'Murmur_Channel', array(
        array('id', $IcePHP__t_int), 
        array('name', $IcePHP__t_string), 
        array('temporary', $IcePHP__t_bool), 
        array('members', $IcePHP__t_string)));
}

if(!defined('Murmur_PermissionWrite'))
{
    define('Murmur_PermissionWrite', 1);
}

if(!defined('Murmur_PermissionTraverse'))
{
    define('Murmur_PermissionTraverse', 2);
}

if(!defined('Murmur_PermissionEnter'))
{
    define('Murmur_PermissionEnter', 4);
}

if(!defined('Murmur_PermissionSpeak'))
{
    define('Murmur_PermissionSpeak', 8);
}

if(!defined('Murmur_PermissionMuteDeafen'))
{
    define('Murmur_PermissionMuteDeafen', 16);
}

if(!defined('Murmur_PermissionMove'))
{
    define('Murmur_PermissionMove', 32);
}

if(!defined('Murmur_PermissionMakeChannel'))
{
    define('Murmur_PermissionMakeChannel', 64);
}

if(!defined('Murmur_PermissionMakeTempChannel'))
{
    define('Murmur_PermissionMakeTempChannel', 1024);
}

if(!defined('Murmur_PermissionTextMessage'))
{
    define('Murmur_PermissionTextMessage', 512);
}

if(!defined('Murmur_PermissionKick'))
{
    define('Murmur_PermissionKick', 65536);
}

if(!defined('Murmur_PermissionRegister'))
{
    define('Murmur_PermissionRegister', 262144);
}

if(!defined('Murmur_PermissionRegisterSelf'))
{
    define('Murmur_PermissionRegisterSelf', 524288);
}

if(!class_exists('Murmur_UserInfo'))
{
    class Murmur_UserInfo
    {
        const UserName = 0;
        const UserEmail = 1;
        const UserComment = 2;
        const UserHash = 3;
        const UserPassword = 4;
        const UserLastActive = 5;
        const UserCurrentChanId = 6;
    }

    $Murmur__t_UserInfo = IcePHP_defineEnum('::Murmur::UserInfo', array('UserName', 0, 'UserEmail', 1, 'UserComment', 2, 'UserHash', 3, 'UserPassword', 4, 'UserLastActive', 5, 'UserCurrentChanId', 6));
}

if(!isset($Murmur__t_UserMap))
{
    $Murmur__t_UserMap = IcePHP_defineDictionary('::Murmur::UserMap', $IcePHP__t_int, $Murmur__t_User);
}

if(!isset($Murmur__t_ChannelMap))
{
    $Murmur__t_ChannelMap = IcePHP_defineDictionary('::Murmur::ChannelMap', $IcePHP__t_int, $Murmur__t_Channel);
}

if(!isset($Murmur__t_ChannelList))
{
    $Murmur__t_ChannelList = IcePHP_defineSequence('::Murmur::ChannelList', $Murmur__t_Channel);
}

if(!isset($Murmur__t_UserList))
{
    $Murmur__t_UserList = IcePHP_defineSequence('::Murmur::UserList', $Murmur__t_User);
}

if(!isset($Murmur__t_IdList))
{
    $Murmur__t_IdList = IcePHP_defineSequence('::Murmur::IdList', $IcePHP__t_int);
}

if(!isset($Murmur__t_NameList))
{
    $Murmur__t_NameList = IcePHP_defineSequence('::Murmur::NameList', $IcePHP__t_string);
}

if(!isset($Murmur__t_NameMap))
{
    $Murmur__t_NameMap = IcePHP_defineDictionary('::Murmur::NameMap', $IcePHP__t_int, $IcePHP__t_string);
}

if(!isset($Murmur__t_IdMap))
{
    $Murmur__t_IdMap = IcePHP_defineDictionary('::Murmur::IdMap', $IcePHP__t_string, $IcePHP__t_int);
}

if(!isset($Murmur__t_Texture))
{
    $Murmur__t_Texture = IcePHP_defineSequence('::Murmur::Texture', $IcePHP__t_byte);
}

if(!isset($Murmur__t_ConfigMap))
{
    $Murmur__t_ConfigMap = IcePHP_defineDictionary('::Murmur::ConfigMap', $IcePHP__t_string, $IcePHP__t_string);
}

if(!isset($Murmur__t_UserInfoMap))
{
    $Murmur__t_UserInfoMap = IcePHP_defineDictionary('::Murmur::UserInfoMap', $Murmur__t_UserInfo, $IcePHP__t_string);
}

if(!class_exists('Murmur_MurmurException'))
{
    class Murmur_MurmurException extends Ice_UserException
    {
        public function __construct()
        {
        }

        public function ice_name()
        {
            return 'Murmur::MurmurException';
        }

        public function __toString()
        {
            global $Murmur__t_MurmurException;
            return IcePHP_stringifyException($this, $Murmur__t_MurmurException);
        }
    }

    $Murmur__t_MurmurException = IcePHP_defineException('::Murmur::MurmurException', 'Murmur_MurmurException', false, null, null);
}

if(!class_exists('Murmur_InvalidSessionException'))
{
    class Murmur_InvalidSessionException extends Murmur_MurmurException
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function ice_name()
        {
            return 'Murmur::InvalidSessionException';
        }

        public function __toString()
        {
            global $Murmur__t_InvalidSessionException;
            return IcePHP_stringifyException($this, $Murmur__t_InvalidSessionException);
        }
    }

    $Murmur__t_InvalidSessionException = IcePHP_defineException('::Murmur::InvalidSessionException', 'Murmur_InvalidSessionException', false, $Murmur__t_MurmurException, null);
}

if(!class_exists('Murmur_InvalidChannelException'))
{
    class Murmur_InvalidChannelException extends Murmur_MurmurException
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function ice_name()
        {
            return 'Murmur::InvalidChannelException';
        }

        public function __toString()
        {
            global $Murmur__t_InvalidChannelException;
            return IcePHP_stringifyException($this, $Murmur__t_InvalidChannelException);
        }
    }

    $Murmur__t_InvalidChannelException = IcePHP_defineException('::Murmur::InvalidChannelException', 'Murmur_InvalidChannelException', false, $Murmur__t_MurmurException, null);
}

if(!class_exists('Murmur_InvalidServerException'))
{
    class Murmur_InvalidServerException extends Murmur_MurmurException
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function ice_name()
        {
            return 'Murmur::InvalidServerException';
        }

        public function __toString()
        {
            global $Murmur__t_InvalidServerException;
            return IcePHP_stringifyException($this, $Murmur__t_InvalidServerException);
        }
    }

    $Murmur__t_InvalidServerException = IcePHP_defineException('::Murmur::InvalidServerException', 'Murmur_InvalidServerException', false, $Murmur__t_MurmurException, null);
}

if(!class_exists('Murmur_ServerBootedException'))
{
    class Murmur_ServerBootedException extends Murmur_MurmurException
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function ice_name()
        {
            return 'Murmur::ServerBootedException';
        }

        public function __toString()
        {
            global $Murmur__t_ServerBootedException;
            return IcePHP_stringifyException($this, $Murmur__t_ServerBootedException);
        }
    }

    $Murmur__t_ServerBootedException = IcePHP_defineException('::Murmur::ServerBootedException', 'Murmur_ServerBootedException', false, $Murmur__t_MurmurException, null);
}

if(!class_exists('Murmur_ServerFailureException'))
{
    class Murmur_ServerFailureException extends Murmur_MurmurException
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function ice_name()
        {
            return 'Murmur::ServerFailureException';
        }

        public function __toString()
        {
            global $Murmur__t_ServerFailureException;
            return IcePHP_stringifyException($this, $Murmur__t_ServerFailureException);
        }
    }

    $Murmur__t_ServerFailureException = IcePHP_defineException('::Murmur::ServerFailureException', 'Murmur_ServerFailureException', false, $Murmur__t_MurmurException, null);
}

if(!class_exists('Murmur_InvalidUserException'))
{
    class Murmur_InvalidUserException extends Murmur_MurmurException
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function ice_name()
        {
            return 'Murmur::InvalidUserException';
        }

        public function __toString()
        {
            global $Murmur__t_InvalidUserException;
            return IcePHP_stringifyException($this, $Murmur__t_InvalidUserException);
        }
    }

    $Murmur__t_InvalidUserException = IcePHP_defineException('::Murmur::InvalidUserException', 'Murmur_InvalidUserException', false, $Murmur__t_MurmurException, null);
}

if(!class_exists('Murmur_InvalidTextureException'))
{
    class Murmur_InvalidTextureException extends Murmur_MurmurException
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function ice_name()
        {
            return 'Murmur::InvalidTextureException';
        }

        public function __toString()
        {
            global $Murmur__t_InvalidTextureException;
            return IcePHP_stringifyException($this, $Murmur__t_InvalidTextureException);
        }
    }

    $Murmur__t_InvalidTextureException = IcePHP_defineException('::Murmur::InvalidTextureException', 'Murmur_InvalidTextureException', false, $Murmur__t_MurmurException, null);
}

if(!class_exists('Murmur_InvalidCallbackException'))
{
    class Murmur_InvalidCallbackException extends Murmur_MurmurException
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function ice_name()
        {
            return 'Murmur::InvalidCallbackException';
        }

        public function __toString()
        {
            global $Murmur__t_InvalidCallbackException;
            return IcePHP_stringifyException($this, $Murmur__t_InvalidCallbackException);
        }
    }

    $Murmur__t_InvalidCallbackException = IcePHP_defineException('::Murmur::InvalidCallbackException', 'Murmur_InvalidCallbackException', false, $Murmur__t_MurmurException, null);
}

if(!class_exists('Murmur_InvalidSecretException'))
{
    class Murmur_InvalidSecretException extends Murmur_MurmurException
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function ice_name()
        {
            return 'Murmur::InvalidSecretException';
        }

        public function __toString()
        {
            global $Murmur__t_InvalidSecretException;
            return IcePHP_stringifyException($this, $Murmur__t_InvalidSecretException);
        }
    }

    $Murmur__t_InvalidSecretException = IcePHP_defineException('::Murmur::InvalidSecretException', 'Murmur_InvalidSecretException', false, $Murmur__t_MurmurException, null);
}

if(!interface_exists('Murmur_ServerCallback'))
{
    interface Murmur_ServerCallback
    {
        public function userConnected($state);
        public function userDisconnected($state);
        public function userStateChanged($state);
        public function channelCreated($state);
        public function channelRemoved($state);
        public function channelStateChanged($state);
    }

    class Murmur_ServerCallbackPrxHelper
    {
        public static function checkedCast($proxy, $facetOrCtx=null, $ctx=null)
        {
            return $proxy->ice_checkedCast('::Murmur::ServerCallback', $facetOrCtx, $ctx);
        }

        public static function uncheckedCast($proxy, $facet=null)
        {
            return $proxy->ice_uncheckedCast('::Murmur::ServerCallback', $facet);
        }
    }

    $Murmur__t_ServerCallback = IcePHP_defineClass('::Murmur::ServerCallback', 'Murmur_ServerCallback', -1, true, false, $Ice__t_Object, null, null);

    $Murmur__t_ServerCallbackPrx = IcePHP_defineProxy($Murmur__t_ServerCallback);

    IcePHP_defineOperation($Murmur__t_ServerCallback, 'userConnected', 2, 2, 0, array(array($Murmur__t_User, false, 0)), null, null, null);
    IcePHP_defineOperation($Murmur__t_ServerCallback, 'userDisconnected', 2, 2, 0, array(array($Murmur__t_User, false, 0)), null, null, null);
    IcePHP_defineOperation($Murmur__t_ServerCallback, 'userStateChanged', 2, 2, 0, array(array($Murmur__t_User, false, 0)), null, null, null);
    IcePHP_defineOperation($Murmur__t_ServerCallback, 'channelCreated', 2, 2, 0, array(array($Murmur__t_Channel, false, 0)), null, null, null);
    IcePHP_defineOperation($Murmur__t_ServerCallback, 'channelRemoved', 2, 2, 0, array(array($Murmur__t_Channel, false, 0)), null, null, null);
    IcePHP_defineOperation($Murmur__t_ServerCallback, 'channelStateChanged', 2, 2, 0, array(array($Murmur__t_Channel, false, 0)), null, null, null);
}

if(!defined('Murmur_ContextServer'))
{
    define('Murmur_ContextServer', 1);
}

if(!defined('Murmur_ContextChannel'))
{
    define('Murmur_ContextChannel', 2);
}

if(!defined('Murmur_ContextUser'))
{
    define('Murmur_ContextUser', 4);
}

if(!interface_exists('Murmur_ServerAuthenticator'))
{
    interface Murmur_ServerAuthenticator
    {
        public function authenticate($name, $pw);
        public function getInfo($id, $info);
        public function nameToId($name);
        public function idToName($id);
        public function idToTexture($id);
    }

    class Murmur_ServerAuthenticatorPrxHelper
    {
        public static function checkedCast($proxy, $facetOrCtx=null, $ctx=null)
        {
            return $proxy->ice_checkedCast('::Murmur::ServerAuthenticator', $facetOrCtx, $ctx);
        }

        public static function uncheckedCast($proxy, $facet=null)
        {
            return $proxy->ice_uncheckedCast('::Murmur::ServerAuthenticator', $facet);
        }
    }

    $Murmur__t_ServerAuthenticator = IcePHP_defineClass('::Murmur::ServerAuthenticator', 'Murmur_ServerAuthenticator', -1, true, false, $Ice__t_Object, null, null);

    $Murmur__t_ServerAuthenticatorPrx = IcePHP_defineProxy($Murmur__t_ServerAuthenticator);

    IcePHP_defineOperation($Murmur__t_ServerAuthenticator, 'authenticate', 2, 2, 0, array(array($IcePHP__t_string, false, 0), array($IcePHP__t_string, false, 0)), null, array($IcePHP__t_int, false, 0), null);
    IcePHP_defineOperation($Murmur__t_ServerAuthenticator, 'getInfo', 2, 2, 0, array(array($IcePHP__t_int, false, 0)), array(array($Murmur__t_UserInfoMap, false, 0)), array($IcePHP__t_bool, false, 0), null);
    IcePHP_defineOperation($Murmur__t_ServerAuthenticator, 'nameToId', 2, 2, 0, array(array($IcePHP__t_string, false, 0)), null, array($IcePHP__t_int, false, 0), null);
    IcePHP_defineOperation($Murmur__t_ServerAuthenticator, 'idToName', 2, 2, 0, array(array($IcePHP__t_int, false, 0)), null, array($IcePHP__t_string, false, 0), null);
    IcePHP_defineOperation($Murmur__t_ServerAuthenticator, 'idToTexture', 2, 2, 0, array(array($IcePHP__t_int, false, 0)), null, array($Murmur__t_Texture, false, 0), null);
}

if(!interface_exists('Murmur_ServerUpdatingAuthenticator'))
{
    interface Murmur_ServerUpdatingAuthenticator extends Murmur_ServerAuthenticator
    {
        public function registerUser($info);
        public function unregisterUser($id);
        public function getRegisteredUsers($filter);
        public function setInfo($id, $info);
        public function setTexture($id, $tex);
    }

    class Murmur_ServerUpdatingAuthenticatorPrxHelper
    {
        public static function checkedCast($proxy, $facetOrCtx=null, $ctx=null)
        {
            return $proxy->ice_checkedCast('::Murmur::ServerUpdatingAuthenticator', $facetOrCtx, $ctx);
        }

        public static function uncheckedCast($proxy, $facet=null)
        {
            return $proxy->ice_uncheckedCast('::Murmur::ServerUpdatingAuthenticator', $facet);
        }
    }

    $Murmur__t_ServerUpdatingAuthenticator = IcePHP_defineClass('::Murmur::ServerUpdatingAuthenticator', 'Murmur_ServerUpdatingAuthenticator', -1, true, false, $Ice__t_Object, array($Murmur__t_ServerAuthenticator), null);

    $Murmur__t_ServerUpdatingAuthenticatorPrx = IcePHP_defineProxy($Murmur__t_ServerUpdatingAuthenticator);

    IcePHP_defineOperation($Murmur__t_ServerUpdatingAuthenticator, 'registerUser', 0, 0, 0, array(array($Murmur__t_UserInfoMap, false, 0)), null, array($IcePHP__t_int, false, 0), null);
    IcePHP_defineOperation($Murmur__t_ServerUpdatingAuthenticator, 'unregisterUser', 0, 0, 0, array(array($IcePHP__t_int, false, 0)), null, array($IcePHP__t_int, false, 0), null);
    IcePHP_defineOperation($Murmur__t_ServerUpdatingAuthenticator, 'getRegisteredUsers', 2, 2, 0, array(array($IcePHP__t_string, false, 0)), null, array($Murmur__t_NameMap, false, 0), null);
    IcePHP_defineOperation($Murmur__t_ServerUpdatingAuthenticator, 'setInfo', 2, 2, 0, array(array($IcePHP__t_int, false, 0), array($Murmur__t_UserInfoMap, false, 0)), null, array($IcePHP__t_int, false, 0), null);
    IcePHP_defineOperation($Murmur__t_ServerUpdatingAuthenticator, 'setTexture', 2, 2, 0, array(array($IcePHP__t_int, false, 0), array($Murmur__t_Texture, false, 0)), null, array($IcePHP__t_int, false, 0), null);
}

if(!interface_exists('Murmur_Server'))
{
    interface Murmur_Server
    {
        public function isRunning();
        public function start();
        public function stop();
        public function delete();
        public function id();
        public function addCallback($cb);
        public function removeCallback($cb);
        public function setAuthenticator($auth);
        public function getConf($key);
        public function getAllConf();
        public function setConf($key, $value);
        public function setSuperuserPassword($pw);
        public function getUsers();
        public function getChannels();
        public function getState($session);
        public function setState($state);
        public function hasPermission($session, $channelid, $perm);
        public function effectivePermissions($session, $channelid);
        public function getChannelState($channelid);
        public function setChannelState($state);
        public function removeChannel($channelid);
        public function addChannel($name, $parent);
        public function getUserNames($ids);
        public function getUserIds($names);
        public function registerUser($info);
        public function unregisterUser($userid);
        public function updateRegistration($userid, $info);
        public function getRegistration($userid);
        public function getRegisteredUsers($filter);
        public function verifyPassword($name, $pw);
        public function getTexture($userid);
        public function setTexture($userid, $tex);
        public function getUptime();
    }

    class Murmur_ServerPrxHelper
    {
        public static function checkedCast($proxy, $facetOrCtx=null, $ctx=null)
        {
            return $proxy->ice_checkedCast('::Murmur::Server', $facetOrCtx, $ctx);
        }

        public static function uncheckedCast($proxy, $facet=null)
        {
            return $proxy->ice_uncheckedCast('::Murmur::Server', $facet);
        }
    }

    $Murmur__t_Server = IcePHP_defineClass('::Murmur::Server', 'Murmur_Server', -1, true, false, $Ice__t_Object, null, null);

    $Murmur__t_ServerPrx = IcePHP_defineProxy($Murmur__t_Server);

    IcePHP_defineOperation($Murmur__t_Server, 'isRunning', 2, 2, 0, null, null, array($IcePHP__t_bool, false, 0), array($Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'start', 0, 0, 0, null, null, null, array($Murmur__t_ServerBootedException, $Murmur__t_ServerFailureException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'stop', 0, 0, 0, null, null, null, array($Murmur__t_ServerBootedException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'delete', 0, 0, 0, null, null, null, array($Murmur__t_ServerBootedException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'id', 2, 2, 0, null, null, array($IcePHP__t_int, false, 0), array($Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'addCallback', 0, 0, 0, array(array($Murmur__t_ServerCallbackPrx, false, 0)), null, null, array($Murmur__t_ServerBootedException, $Murmur__t_InvalidCallbackException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'removeCallback', 0, 0, 0, array(array($Murmur__t_ServerCallbackPrx, false, 0)), null, null, array($Murmur__t_ServerBootedException, $Murmur__t_InvalidCallbackException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'setAuthenticator', 0, 0, 0, array(array($Murmur__t_ServerAuthenticatorPrx, false, 0)), null, null, array($Murmur__t_ServerBootedException, $Murmur__t_InvalidCallbackException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'getConf', 2, 2, 0, array(array($IcePHP__t_string, false, 0)), null, array($IcePHP__t_string, false, 0), array($Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'getAllConf', 2, 2, 0, null, null, array($Murmur__t_ConfigMap, false, 0), array($Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'setConf', 2, 2, 0, array(array($IcePHP__t_string, false, 0), array($IcePHP__t_string, false, 0)), null, null, array($Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'setSuperuserPassword', 2, 2, 0, array(array($IcePHP__t_string, false, 0)), null, null, array($Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'getUsers', 2, 2, 0, null, null, array($Murmur__t_UserMap, false, 0), array($Murmur__t_ServerBootedException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'getChannels', 2, 2, 0, null, null, array($Murmur__t_ChannelMap, false, 0), array($Murmur__t_ServerBootedException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'getState', 2, 2, 0, array(array($IcePHP__t_int, false, 0)), null, array($Murmur__t_User, false, 0), array($Murmur__t_ServerBootedException, $Murmur__t_InvalidSessionException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'setState', 2, 2, 0, array(array($Murmur__t_User, false, 0)), null, null, array($Murmur__t_ServerBootedException, $Murmur__t_InvalidSessionException, $Murmur__t_InvalidChannelException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'hasPermission', 0, 0, 0, array(array($IcePHP__t_int, false, 0), array($IcePHP__t_int, false, 0), array($IcePHP__t_int, false, 0)), null, array($IcePHP__t_bool, false, 0), array($Murmur__t_ServerBootedException, $Murmur__t_InvalidSessionException, $Murmur__t_InvalidChannelException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'effectivePermissions', 2, 2, 0, array(array($IcePHP__t_int, false, 0), array($IcePHP__t_int, false, 0)), null, array($IcePHP__t_int, false, 0), array($Murmur__t_ServerBootedException, $Murmur__t_InvalidSessionException, $Murmur__t_InvalidChannelException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'getChannelState', 2, 2, 0, array(array($IcePHP__t_int, false, 0)), null, array($Murmur__t_Channel, false, 0), array($Murmur__t_ServerBootedException, $Murmur__t_InvalidChannelException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'setChannelState', 2, 2, 0, array(array($Murmur__t_Channel, false, 0)), null, null, array($Murmur__t_ServerBootedException, $Murmur__t_InvalidChannelException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'removeChannel', 0, 0, 0, array(array($IcePHP__t_int, false, 0)), null, null, array($Murmur__t_ServerBootedException, $Murmur__t_InvalidChannelException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'addChannel', 0, 0, 0, array(array($IcePHP__t_string, false, 0), array($IcePHP__t_int, false, 0)), null, array($IcePHP__t_int, false, 0), array($Murmur__t_ServerBootedException, $Murmur__t_InvalidChannelException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'getUserNames', 2, 2, 0, array(array($Murmur__t_IdList, false, 0)), null, array($Murmur__t_NameMap, false, 0), array($Murmur__t_ServerBootedException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'getUserIds', 2, 2, 0, array(array($Murmur__t_NameList, false, 0)), null, array($Murmur__t_IdMap, false, 0), array($Murmur__t_ServerBootedException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'registerUser', 0, 0, 0, array(array($Murmur__t_UserInfoMap, false, 0)), null, array($IcePHP__t_int, false, 0), array($Murmur__t_ServerBootedException, $Murmur__t_InvalidUserException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'unregisterUser', 0, 0, 0, array(array($IcePHP__t_int, false, 0)), null, null, array($Murmur__t_ServerBootedException, $Murmur__t_InvalidUserException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'updateRegistration', 2, 2, 0, array(array($IcePHP__t_int, false, 0), array($Murmur__t_UserInfoMap, false, 0)), null, null, array($Murmur__t_ServerBootedException, $Murmur__t_InvalidUserException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'getRegistration', 2, 2, 0, array(array($IcePHP__t_int, false, 0)), null, array($Murmur__t_UserInfoMap, false, 0), array($Murmur__t_ServerBootedException, $Murmur__t_InvalidUserException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'getRegisteredUsers', 2, 2, 0, array(array($IcePHP__t_string, false, 0)), null, array($Murmur__t_NameMap, false, 0), array($Murmur__t_ServerBootedException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'verifyPassword', 2, 2, 0, array(array($IcePHP__t_string, false, 0), array($IcePHP__t_string, false, 0)), null, array($IcePHP__t_int, false, 0), array($Murmur__t_ServerBootedException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'getTexture', 2, 2, 0, array(array($IcePHP__t_int, false, 0)), null, array($Murmur__t_Texture, false, 0), array($Murmur__t_ServerBootedException, $Murmur__t_InvalidUserException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'setTexture', 2, 2, 0, array(array($IcePHP__t_int, false, 0), array($Murmur__t_Texture, false, 0)), null, null, array($Murmur__t_ServerBootedException, $Murmur__t_InvalidUserException, $Murmur__t_InvalidTextureException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Server, 'getUptime', 2, 2, 0, null, null, array($IcePHP__t_int, false, 0), array($Murmur__t_ServerBootedException, $Murmur__t_InvalidSecretException));
}

if(!interface_exists('Murmur_MetaCallback'))
{
    interface Murmur_MetaCallback
    {
        public function started($srv);
        public function stopped($srv);
    }

    class Murmur_MetaCallbackPrxHelper
    {
        public static function checkedCast($proxy, $facetOrCtx=null, $ctx=null)
        {
            return $proxy->ice_checkedCast('::Murmur::MetaCallback', $facetOrCtx, $ctx);
        }

        public static function uncheckedCast($proxy, $facet=null)
        {
            return $proxy->ice_uncheckedCast('::Murmur::MetaCallback', $facet);
        }
    }

    $Murmur__t_MetaCallback = IcePHP_defineClass('::Murmur::MetaCallback', 'Murmur_MetaCallback', -1, true, false, $Ice__t_Object, null, null);

    $Murmur__t_MetaCallbackPrx = IcePHP_defineProxy($Murmur__t_MetaCallback);

    IcePHP_defineOperation($Murmur__t_MetaCallback, 'started', 0, 0, 0, array(array($Murmur__t_ServerPrx, false, 0)), null, null, null);
    IcePHP_defineOperation($Murmur__t_MetaCallback, 'stopped', 0, 0, 0, array(array($Murmur__t_ServerPrx, false, 0)), null, null, null);
}

if(!isset($Murmur__t_ServerList))
{
    $Murmur__t_ServerList = IcePHP_defineSequence('::Murmur::ServerList', $Murmur__t_ServerPrx);
}

if(!interface_exists('Murmur_Meta'))
{
    interface Murmur_Meta
    {
        public function getServer($id);
        public function newServer();
        public function getBootedServers();
        public function getAllServers();
        public function getDefaultConf();
        public function getVersion($major, $minor, $patch, $text);
        public function addCallback($cb);
        public function removeCallback($cb);
        public function getUptime();
        public function getSlice();
        public function getSliceChecksums();
    }

    class Murmur_MetaPrxHelper
    {
        public static function checkedCast($proxy, $facetOrCtx=null, $ctx=null)
        {
            return $proxy->ice_checkedCast('::Murmur::Meta', $facetOrCtx, $ctx);
        }

        public static function uncheckedCast($proxy, $facet=null)
        {
            return $proxy->ice_uncheckedCast('::Murmur::Meta', $facet);
        }
    }

    $Murmur__t_Meta = IcePHP_defineClass('::Murmur::Meta', 'Murmur_Meta', -1, true, false, $Ice__t_Object, null, null);

    $Murmur__t_MetaPrx = IcePHP_defineProxy($Murmur__t_Meta);

    IcePHP_defineOperation($Murmur__t_Meta, 'getServer', 2, 2, 0, array(array($IcePHP__t_int, false, 0)), null, array($Murmur__t_ServerPrx, false, 0), array($Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Meta, 'newServer', 0, 0, 0, null, null, array($Murmur__t_ServerPrx, false, 0), array($Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Meta, 'getBootedServers', 2, 2, 0, null, null, array($Murmur__t_ServerList, false, 0), array($Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Meta, 'getAllServers', 2, 2, 0, null, null, array($Murmur__t_ServerList, false, 0), array($Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Meta, 'getDefaultConf', 2, 2, 0, null, null, array($Murmur__t_ConfigMap, false, 0), array($Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Meta, 'getVersion', 2, 2, 0, null, array(array($IcePHP__t_int, false, 0), array($IcePHP__t_int, false, 0), array($IcePHP__t_int, false, 0), array($IcePHP__t_string, false, 0)), null, null);
    IcePHP_defineOperation($Murmur__t_Meta, 'addCallback', 0, 0, 0, array(array($Murmur__t_MetaCallbackPrx, false, 0)), null, null, array($Murmur__t_InvalidCallbackException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Meta, 'removeCallback', 0, 0, 0, array(array($Murmur__t_MetaCallbackPrx, false, 0)), null, null, array($Murmur__t_InvalidCallbackException, $Murmur__t_InvalidSecretException));
    IcePHP_defineOperation($Murmur__t_Meta, 'getUptime', 2, 2, 0, null, null, array($IcePHP__t_int, false, 0), null);
    IcePHP_defineOperation($Murmur__t_Meta, 'getSlice', 2, 2, 0, null, null, array($IcePHP__t_string, false, 0), null);
    IcePHP_defineOperation($Murmur__t_Meta, 'getSliceChecksums', 2, 2, 0, null, null, array($Ice__t_SliceChecksumDict, false, 0), null);
}
?>
