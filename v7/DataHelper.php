<?php

class DataHelper
{

    private static $Format;
    private static $Data;



    public static function Data($Data)
    {
        self::$Format = explode(' ', $Data);
        self::$Data = explode('/', self::$Format[0]);

        if (!checkdate(self::$Data[1], self::$Data[0], self::$Data[2])) :
            return false;
        else :
            if (empty(self::$Format[1])) :
                self::$Format[1] = date('H:i:s');
            endif;

            self::$Data = self::$Data[2] . '-' . self::$Data[1] . '-' . self::$Data[0] . ' ' . self::$Format[1];
            return self::$Data;
        endif;
    }

    public static function DataBr($Data)
    {
        self::$Format = explode(' ', $Data);
        self::$Data = explode('-', self::$Format[0]);
        //  '2023-03-09                 21:40:00'
        
        if (!checkdate(self::$Data[1], self::$Data[2], self::$Data[0])) :
          
            return false;
        else :
            if (empty(self::$Format[1])) :
              
                //self::$Format[1] = date('H:i', strtotime(self::$Format[1]));
                self::$Format[1] = substr(self::$Format[1], 0, 5);
                
            endif;
             
            self::$Data = self::$Data[2] . '/' . self::$Data[1] . '/' . self::$Data[0] . ' ' . self::$Format[1];
             
            return self::$Data;
        endif;
    }



    public static function InsereMoeda($Valor)
    {

        $formattedPrice = number_format((int)$Valor, '2', '.', ',');
        return $formattedPrice;
    }
}
