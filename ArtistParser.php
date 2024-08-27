<?php

/**
 * Class ArtistParser
 */
class ArtistParser extends ChainParser
{

    /**
     * @param $id
     * @return mixed
     */
    public function checkIntegrity()
    {
        $this->Message("--------- Check the availability of Release ".self::$releaseId." links with the ARTIST ---------");
        $artistData = MBRelease2Artist::model()->findAll('release_id=:release_id', array(':release_id'=>self::$releaseId));
        if ($artistData) {
            foreach ($artistData as $value){
                $this->message("--------- Release ".self::$releaseId." found in the table mBRelease2Artist  ---------");
                if(MBArtist::model()->find('id = :id', array(':id'=>$value->artist_id))){
                    $this->message("--------- Release ".self::$releaseId." found in the table mBArtist  ---------");
                    $this->i++;
                }
            }
            if(0 < $this->i){
                return parent::checkIntegrity();
            } else {
                $this->message("--------- There are no artists at the Release id ".self::$releaseId."---------");
            }

        } else {
            $this->message("--------- No Release ".self::$releaseId." found in the table mBRelease2Artist---------");
        }
        return false;
    }

    public function insertRelease(){


       $this->message("--------- Start inserting the artist data of release ".self::$releaseId." into the BD ---------");
        $artistData = MBRelease2Artist::model()->findAll('release_id=:release_id', array(':release_id'=>self::$releaseId));
        if ($artistData) {
            foreach ($artistData as $value){
                $nlRelease2Artist = new NLRelease2Artist();
                $nlRelease2Artist->attributes = $value->attributes;
                $nlRelease2Artist->release_id = self::$releaseIdNew;
                // проверка наличие жанра для релиза и если надо сохранить в нашу бд
                $artistId = self::getArtistId($value->artist_id);

                $nlRelease2Artist->artist_id  = $artistId;
                if($nlRelease2Artist->save()){
                    $this->message("--------- The release ".self::$releaseId." data is saved in the table 'nl_releases_2_genre' ---------");
                    $this->i++;
                }
            }
        }
        if(0 < $this->i){
            $this->message("--------- The artist ".self::$releaseId." data is saved in the table 'nl_artist' and 'nl_release_2_artist' ---------");
            return parent::insertRelease();
        }
        $this->message("--------- The artist of release ".self::$releaseIdNew." data is not saved in the table 'nl_release_2_artist' ---------");
        $this->message("----------The End-----------");
        $this->message("-----------------------------------------------------------------");
        return false;
    }

    public static function getArtistId($id)
    {
        $mBArtist = MBArtist::model()->find('id = :id', array(':id' => $id));
        $nlArtist = NLArtist::model()->find('name = :name', array(':name' => $mBArtist->name));
        if(!$nlArtist){
            $nlArtist = new NLArtist();
            $nlArtist->attributes = $mBArtist->attributes;
            $nlArtist->save();
        }
        return $nlArtist->id;
    }

}
