<?php

/**
 * Class ChainParser
 */
abstract class ChainParser
{
    /**
     * @var ChainParser
     */
    protected $successor;

    /**
     * @param releaseId
     */
    public static $releaseId = 0;

    /**
     * @param releaseIdNew
     */
    public static $releaseIdNew = 0;


    /**
     * @param discsOldNew
     */
    public static $discsOldNew = array();


    /**
     * @param tracksOldNew
     */
    public static $tracksOldNew = array();

    /**
     * @param counter
     */
    protected $i = 0;

    /**
     * @$arrayId array
     * массив данных для передачи по цепочке (при необходимости)
     */
    protected $data = array();

    /**
     * @param ChainParser $successor
     */
    public function __construct(ChainParser $successor = null)
    {
        $this->successor = $successor;
    }


    /**
     * @return ChainParser
     */
    public function getSuccessor()
    {
        return $this->successor;
    }


    /**
     * @param $id
     * @return mixed
     */
    public function checkIntegrity($data=0)
    {
        $successor = $this->getSuccessor();

        if (!is_null($successor)) {
            return $successor->checkIntegrity($data);

        } else {
            $this->message("--------- The result of the Release ".self::$releaseId." check is POSITIVE ---------");
            $this->message("-----------------------------------------------------------------");
            return true;
        }

    }


    /**
     * @param $id
     * @return mixed
     */
    public function insertRelease($data=0)
    {
        $successor = $this->getSuccessor();

        if (!is_null($successor)) {
            return $successor->insertRelease($data);

        } else {
            $this->message("--------- The release ".self::$releaseId." of the release and its dependencies was successful ---------");
            $this->message("----------The End------------------------------------------");
            $this->message("-----------------------------------------------------------------");
            return true;
        }

    }


    protected function message($message)
    {
        $now = date('[Y-m-d G:i:s]');
        echo $now . ' ' . $message . PHP_EOL;
    }
}