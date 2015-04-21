<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 4/16/15
 * Time: 11:52
 */

class VideoHelper {
    private function curlGet($URL) {
        $ch = curl_init();
        $timeout = 3;
        curl_setopt( $ch , CURLOPT_URL , $URL );
        curl_setopt( $ch , CURLOPT_RETURNTRANSFER , 1 );
        curl_setopt( $ch , CURLOPT_CONNECTTIMEOUT , $timeout );
        /* if you want to force to ipv6, uncomment the following line */
        //curl_setopt( $ch , CURLOPT_IPRESOLVE , 'CURLOPT_IPRESOLVE_V6');
        $tmp = curl_exec( $ch );
        curl_close( $ch );
        return $tmp;
    }
    /*
     * function to use cUrl to get the headers of the file
     */
    private function get_location($url) {
        $my_ch = curl_init();
        curl_setopt($my_ch, CURLOPT_URL,$url);
        curl_setopt($my_ch, CURLOPT_HEADER,         true);
        curl_setopt($my_ch, CURLOPT_NOBODY,         true);
        curl_setopt($my_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($my_ch, CURLOPT_TIMEOUT,        10);
        $r = curl_exec($my_ch);
        foreach(explode("\n", $r) as $header) {
            if(strpos($header, 'Location: ') === 0) {
                return trim(substr($header,10));
            }
        }
        return '';
    }
    private function get_size($url) {
        $my_ch = curl_init();
        curl_setopt($my_ch, CURLOPT_URL,$url);
        curl_setopt($my_ch, CURLOPT_HEADER,         true);
        curl_setopt($my_ch, CURLOPT_NOBODY,         true);
        curl_setopt($my_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($my_ch, CURLOPT_TIMEOUT,        10);
        $r = curl_exec($my_ch);
        foreach(explode("\n", $r) as $header) {
            if(strpos($header, 'Content-Length:') === 0) {
                return trim(substr($header,16));
            }
        }
        return '';
    }
    private function get_description($url) {
        $fullpage = $this->curlGet($url);
        $dom = new DOMDocument();
        @$dom->loadHTML($fullpage);
        $xpath = new DOMXPath($dom);
        $tags = $xpath->query('//div[@class="info-description-body"]');
        $my_description='';
        foreach ($tags as $tag) {
            $my_description .= (trim($tag->nodeValue));
        }

        return utf8_decode($my_description);
    }
    public function downloadYoutubeVideo($video_id) {
        $my_video_info = 'http://www.youtube.com/get_video_info?&video_id='.$video_id; //video details fix *1
        $my_video_info = $this->curlGet($my_video_info);

        $thumbnail_url = $title = $url_encoded_fmt_stream_map = $type = $url = '';

        parse_str($my_video_info);

        if(isset($url_encoded_fmt_stream_map)) {
            /* Now get the url_encoded_fmt_stream_map, and explode on comma */
            $my_formats_array = explode(',',$url_encoded_fmt_stream_map);

        } else {
            throw new APIException("Can not get video content from Youtube");
        }
        if (count($my_formats_array) == 0) {
            throw new APIException("No valid Youtube video format");
        }
        /* create an array of available download formats */
        $avail_formats[] = '';
        $i = 0;
        $ipbits = $ip = $itag = $sig = $quality = '';
        $expire = time();
        foreach($my_formats_array as $format) {
            parse_str($format);
            $avail_formats[$i]['itag'] = $itag;
            $avail_formats[$i]['quality'] = $quality;
            $type = explode(';',$type);
            $avail_formats[$i]['type'] = $type[0];
            $avail_formats[$i]['url'] = urldecode($url) . '&signature=' . $sig;
            parse_str(urldecode($url));
            $avail_formats[$i]['expires'] = date("G:i:s T", $expire);
            $avail_formats[$i]['ipbits'] = $ipbits;
            $avail_formats[$i]['ip'] = $ip;
            $i++;
        }

        $format=$this->getMp4Url($avail_formats);
        if($format==null) {
            throw new APIException("Do not exist Mp4 format on youtube file");
        } else {
            $url=$format['url'];

            return $this->downloadVideo($url);
        }
    }
    public function downloadVideo($video_url) {
        // folder to save downloaded files to. must end with slash
        set_time_limit (1 * 60 * 60);

        $destination_folder = Constants::SYS_DOWNLOAD_FOLDER;

        $file_name=time().'_'.rand(0,10000).'.mp4';

        $newfname = $destination_folder.'/'.$file_name;

        $file = fopen ($video_url, "rb");
        if ($file) {
            $newf = fopen ($newfname, "wb");

            if ($newf)
                while(!feof($file)) {
                    fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
                }
        }

        if ($file) {
            fclose($file);
        }

        if ($newf) {
            fclose($newf);
        }
        return $file_name;

    }
    private function getMp4Url($avaiable_formats) {
        $first=null;
        foreach ($avaiable_formats as $format) {
            if($format['type']=='video/mp4' ) {
                $first = $format;
                if ($format['quality'] == 'medium') {
                    return $format;
                }
            }
        }
        return $first;
    }
    public function upload($file_name,$title,$description) {

        $videoPath=Constants::SYS_DOWNLOAD_FOLDER."/".$file_name;
        $key = file_get_contents('token.txt');

        $client_secret = Constants::YOUTUBE_CLIENT_SECRET;
        $client_id = Constants::YOUTUBE_CLIENT_ID;
        $scope = array(Constants::YOUTUBE_SCOPE1,Constants::YOUTUBE_SCOPE2,Constants::YOUTUBE_SCOPE3);

        $videoCategory = Constants::YOUTUBE_VIDEO_CATEGORY;
        $videoTags = array(Constants::YOUTUBE_VIDEO_TAG1, Constants::YOUTUBE_VIDEO_TAG2);

        try {
            // Client init
            $client = new Google_Client();
            $client->setClientId($client_id);
            $client->setAccessType(Constants::YOUTUBE_ACCESS_TYPE);
            $client->setAccessToken($key);
            $client->setScopes($scope);
            $client->setClientSecret($client_secret);

            if ($client->getAccessToken()) {

                /**
                 *          * Check to see if our access token has expired. If so, get a new one and save it to file for future use.
                 *          */
                if ($client->isAccessTokenExpired()) {
                    $newToken = json_decode($client->getAccessToken());
                    $client->refreshToken($newToken->refresh_token);
                    file_put_contents('token.txt', $client->getAccessToken());
                }
                $youtube = new Google_Service_YouTube($client);

                // Create a snipet with title, description, tags and category id
                $snippet = new Google_Service_YouTube_VideoSnippet();
                $snippet->setTitle($title);
                $snippet->setDescription($description);
                $snippet->setCategoryId($videoCategory);
                $snippet->setTags($videoTags);
                // Create a video status with privacy status. Options are "public", "private" and "unlisted".
                $status = new Google_Service_YouTube_VideoStatus();
                $status->setPrivacyStatus(Constants::YOUTUBE_VIDEO_PRIVACY);
                // Create a YouTube video with snippet and status
                $video = new Google_Service_YouTube_Video();
                $video->setSnippet($snippet);
                $video->setStatus($status);

                // Size of each chunk of data in bytes. Setting it higher leads faster upload (less chunks,
                // for reliable connections). Setting it lower leads better recovery (fine-grained chunks)
                $chunkSizeBytes = 1 * 1024 * 1024;

                // Setting the defer flag to true tells the client to return a request which can be called
                // with ->execute(); instead of making the API call immediately.
                $client->setDefer(true);

                // Create a request for the API's videos.insert method to create and upload the video.
                $insertRequest = $youtube->videos->insert("status,snippet", $video);

                // Create a MediaFileUpload object for resumable uploads.
                $media = new Google_Http_MediaFileUpload(
                    $client,
                    $insertRequest,
                    'video/*',
                    null,
                    true,
                    $chunkSizeBytes
                );
                $media->setFileSize(filesize($videoPath));


                // Read the media file and upload it chunk by chunk.
                $status = false;
                $handle = fopen($videoPath, "rb");
                while (!$status && !feof($handle)) {
                    $chunk = fread($handle, $chunkSizeBytes);
                    $status = $media->nextChunk($chunk);
                }

                fclose($handle);

                // If you want to make other calls after the file upload, set setDefer back to false
                $client->setDefer(true);
                return $status;

            } else {
                // @TODO Log error
                throw new Exception("Have not access token");
            }

        } catch (Exception $e) {
            throw $e;
        }
    }
    public function isReadyForPublish($video_id) {
        $key = file_get_contents('token.txt');
        $client_secret = Constants::YOUTUBE_CLIENT_SECRET;
        $client_id = Constants::YOUTUBE_CLIENT_ID;
        $scope = array(Constants::YOUTUBE_SCOPE1,Constants::YOUTUBE_SCOPE2,Constants::YOUTUBE_SCOPE3);

        try {
            // Client init
            $client = new Google_Client();
            $client->setClientId($client_id);
            $client->setAccessType(Constants::YOUTUBE_ACCESS_TYPE);
            $client->setAccessToken($key);
            $client->setScopes($scope);
            $client->setClientSecret($client_secret);

            if ($client->getAccessToken()) {

                /**
                 *          * Check to see if our access token has expired. If so, get a new one and save it to file for future use.
                 *          */
                if ($client->isAccessTokenExpired()) {
                    $newToken = json_decode($client->getAccessToken());
                    $client->refreshToken($newToken->refresh_token);
                    file_put_contents('token.txt', $client->getAccessToken());
                }
                $youtube = new Google_Service_YouTube($client);
                $video_status=$youtube->videos->listVideos('processingDetails',array('id'=>$video_id));
                if(count($video_status->getItems())>0) {
                    $item=$video_status->getItems()[0];
                    if(isset($item->processingDetails)) {
                        $processing_detail=$item->processingDetails;
                        if(array_key_exists('processingStatus',$processing_detail) && $processing_detail['processingStatus']=='succeeded') {
                            //ready for publish on client.
                            return true;
                        }
                    }
                }
                return false;
            }
        } catch(Exception $e) {
            throw $e;
        }
    }

}