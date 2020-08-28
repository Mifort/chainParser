<?php

/**
 * Class DiscParser
 */
class DiscParser extends ChainParser
{
    /**
     * @param $id
     * @return mixed
     */
    public function checkIntegrity()
    {
        $this->Message("--------- Check the availability of release ".self::$releaseId." links with the DISC ---------");
        $mBDisc = MBDisc::model()->findAll('release_id=:release_id', array(':release_id'=>self::$releaseId));
        if($mBDisc) {
            foreach ($mBDisc as $value) {
                $this->message("--------- Release ".self::$releaseId." found in the table mBDisc  ---------");
                $this->i++;
                $this->data[] = $value->id;
            }
            if(0 < $this->i){
                return parent::checkIntegrity($this->data);

            }else{
                $this->message("--------- There are no discs at the release id ".self::$releaseId."---------");
            }
        }else{
            $this->message("--------- No Release ".self::$releaseId." found in the table mBDisc---------");
        }
        return false;
    }

    public function insertRelease(){

        $this->message("--------- Start inserting the disc data of release ".self::$releaseId." into the BD ---------");
        $mBDiscs = MBDisc::model()->findAll('release_id=:release_id', array(':release_id'=>self::$releaseId));
        foreach ($mBDiscs as $disc){
            $nLDisc  = new NLDisc();
            $nLDisc->attributes = $disc->attributes;
            $nLDisc->release_id = self::$releaseIdNew;
            if($nLDisc->save()){
                self::$discsOldNew[$disc->id] = $nLDisc->id;
                    $this->i++;
                }
        }
        if(0 < $this->i){
            $this->message("--------- The discs ".self::$releaseId." data is saved in the table 'nl_disc' ---------");
            return parent::insertRelease();
        }


        $this->message("--------- The disc of release ".self::$releaseId." data is not saved in the table 'nl_disc' ---------");
        $this->message("----------The End-----------");
        $this->message("-----------------------------------------------------------------");
        return false;
    }
}