<?php

class System_Windows_Registry
{

    private static $_shell;

    const REG_SZ        = 'REG_SZ';
    const REG_BINARY    = 'REG_BINARY';
    const REG_DWORD     = 'REG_DWORD';
    const REG_EXPAND_SZ = 'REG_BINARY';

    private static $_dataTypes = array(
        self::REG_SZ, self::REG_BINARY, self::REG_DWORD, self::REG_EXPAND_SZ
    );

    private static function getShell()
    {
        if (!isset(self::$_shell)) {
            self::$_shell = new COM('WScript.Shell');
        }

        return self::$_shell;
    }

    public static function read($path)
    {
        $shell = self::getShell();

        try {
            $value = $shell->RegRead($path);
        } catch (COM_Exception $e) {
            throw new System_Windows_Registry_ReadException(
                $e->getMessage(),
                $e->getCode()
            );
        }

        return $value;
    }

    public static function delete($path)
    {
        $shell = self::getShell();

        try {
            $shell->RegDelete($path);
        } catch (COM_Exception $e) {
            throw new System_Windows_Registry_WriteException(
                $e->getMessage(),
                $e->getCode()
            );
        }

        return true;
    }

    public static function write($path, $value, $dataType = 'REG_SZ')
    {
        $shell = self::getShell();

        if (!in_array($dataType, self::$_dataTypes)) {
            throw new InvalidArgumentException(
                'Invalid data type: ' . $dataType
            );
        }
    
        try {
            $shell->RegWrite($path, $value, $dataType);
        } catch (COM_Exception $e) {
            throw new System_Windows_Registry_WriteException(
                $e->getMessage(),
                $e->getCode()
            );
        }

        return true;
    }

}

?>
