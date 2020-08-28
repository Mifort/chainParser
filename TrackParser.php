<?php

/**
 * Class TrackParser
 */
class TrackParser extends ChainParser
{
    /**
     * @param $data
     * @return mixed
     */
    public function checkIntegrity($data)
    {
        if(is_array($data)){
            foreach ($data as $id){
                $this->Message("--------- Check the availability of Release ".self::$releaseId." links with the TRACKS ---------");
                $mBTracks = MBTracks::model()->findAll('disc_id=:disc_id', array(':disc_id'=>$id));
                if($mBTracks){
                    $this->i++;
                }
            }
            if(0 < $this->i){
                $this->message("--------- The release ".self::$releaseId." has tracks---------");
                return parent::checkIntegrity();
            }else{
                $this->message("--------- Release ".self::$releaseId." has no tracks---------");
            }
        }
        return false;
    }

    public function insertRelease(){


        $this->message("--------- Start inserting the tracs data of release ".self::$releaseId." into the BD ---------");
        foreach (self::$discsOldNew as $idOld=>$idNew){
            $mBTracks = MBTracks::model()->findAll('disc_id=:disc_id', array(':disc_id'=>$idOld));
            if($mBTracks){
                foreach ($mBTracks as $track){
                    $nLTrack  = new NLTrack();
                    $nLTrack->attributes = $track->attributes;
                    $nLTrack->update_date = date("Y-m-d H:i:s");
                    $nLTrack->disc_id = $idNew;
                    if($nLTrack->save()){
                        $this->message("--------- The disc of track # ".$nLTrack->disc_id.'   '.self::$releaseId." data is saved in the table 'nl_track' ---------");
                        // блок трека с артистом
                            $mBTrack2Artist = MBTrack2Artist::model()->findAll('track_id=:track_id', array(':track_id'=>$track->id));
                            if($mBTrack2Artist){
                                foreach ($mBTrack2Artist as $track2Artist){
                                    $nlTrack2Artist = new NlTrack2Artist();

                                    // проверка наличие артиста для трека и если надо сохранить в нашу бд
                                    $artistId = ArtistParser::getArtistId($track2Artist->artist_id);
                                    // the end проверка наличие артиста для трека

                                    $nlTrack2Artist->artist_id =  $artistId;
                                    $nlTrack2Artist->track_id =  $nLTrack->id;
                                    if($nlTrack2Artist->save()){
                                        $this->message("--------- The artist ".self::$releaseId." data is saved in the table 'nl_track_2_artist' ---------");
                                    }
                                }
                            }
                            // The end блок трека с артистом
                            $this->i++;
                        }
                }
            }
        }
        self::$discsOldNew = array();
        if(0 < $this->i){
            $this->message("--------- The tracks ".self::$releaseId." data is saved in the table 'nl_track' ---------");
            return parent::insertRelease();
        }
        $this->message("--------- The tracks of release ".self::$releaseId." data is not saved in the table 'nl_track' ---------");
        $this->message("----------The End-----------");
        $this->message("-----------------------------------------------------------------");
        return false;
    }
}