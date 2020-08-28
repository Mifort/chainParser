<?php

/**
 * Class GenreParser
 */
class GenreParser extends ChainParser
{
    /**
     * @param $data
     * @return mixed
     */
    public function insertRelease()
    {
        $this->message("--------- Start inserting the genre data of release ".self::$releaseId." into the nl_releases_2_genre ---------");
        $mBRelease2Genre = MBRelease2Genre::model()->findAll('release_id=:release_id', array(':release_id'=>self::$releaseId));
        if($mBRelease2Genre){
            foreach ($mBRelease2Genre as $genre){
                $nLRelease2Genre = new NLRelease2Genre();
                $nLRelease2Genre->release_id  = self::$releaseIdNew;
                // проверка наличие жанра для релиза и если надо сохранить в нашу бд
                $genreId = self::getGenreId($genre->genre_id);
                if($genreId){
                    $nLRelease2Genre->genre_id  = $genreId;
                    if($nLRelease2Genre->save()){
                        $this->message("--------- The release ".self::$releaseId." data is saved in the table 'nl_releases_2_genre' ---------");
                        $this->i++;
                    }
                }
            }
            if(0 < $this->i){
                return parent::insertRelease();

            }
        }

        $this->message("--------- The release ".self::$releaseId." has no genres ---------");
        $this->message("----------The End-----------");
        $this->message("-----------------------------------------------------------------");
        return true;
    }
    public static function getGenreId($id){
        $nLGenre = NLGenre::model()->find('id = :id', array(':id'=>$id));
        if($nLGenre){
            return $nLGenre->id;
        }
        return false;
    }
}