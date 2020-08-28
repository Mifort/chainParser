<?php

/**
 * Class ReleaseParser
 */
class ReleaseParser extends ChainParser
{

    public function insertRelease($release)
    {

        if(is_object($release)){
            $this->message("--------- Start inserting the release ".self::$releaseId." into the BD ---------");
            $nolosyRelease = new NLReleases();
            $nolosyRelease->attributes = $release->attributes;
            $nolosyRelease->create_date = $nolosyRelease->update_date = date("Y-m-d H:i:s");
            $nolosyRelease->mB_id  = $release->id;
            if($nolosyRelease->save()){
                self::$releaseIdNew = $nolosyRelease->id;
                $this->message("--------- The release ".self::$releaseId." data is saved in the table 'nl_release' ---------");
                return parent::insertRelease();
            }
        }
        $this->message("--------- The release ".self::$releaseId." data is not saved in the table 'nl_release' ---------");
        $this->message("----------The End-----------");
        $this->message("-----------------------------------------------------------------");
        return false;
    }
}