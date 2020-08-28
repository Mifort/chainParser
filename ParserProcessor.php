<?php


/**
 * ChainOfResponsibility
 *
 * Class ParserProcessor
 */
class ParserProcessor
{
    public static function runCheckIntegrity($id)
    {
        //check unit for all release dependencies
        if(self::setReleaseId($id)) {
            // Это и есть цепочка обязанностей
            $trackParser = new TrackParser();
            $discParser = new DiscParser($trackParser);
            $artistParser = new ArtistParser($discParser);
            return $artistParser->checkIntegrity();
        }
        return false;
    }
    public static function runInsertRelease($obj){
        if(self::setReleaseId($obj->id)) {
            $genreParser = new GenreParser();
            $trackParser = new TrackParser($genreParser);
            $discParser = new DiscParser($trackParser);
            $artistParser = new ArtistParser($discParser);
            $releaseParser = new  ReleaseParser($artistParser);
//            $releaseParser->insertRelease($obj); die;
            return $releaseParser->insertRelease($obj);
        }
        return false;
    }
    private function setReleaseId($id){
       return ChainParser::$releaseId = $id;
    }
}
