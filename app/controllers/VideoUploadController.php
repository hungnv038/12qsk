<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 4/14/15
 * Time: 16:09
 */

class VideoUploadController  extends BaseController {
    public function upload() {
        try {
            $file_name=InputHelper::getInput('file_name',true);
            $title=InputHelper::getInput('title',true);
            $description=InputHelper::getInput('description',false,'');
            $chanel_id=InputHelper::getInput('chanel_id',true);

            $video_helper=new VideoHelper();
            $status=$video_helper->upload($file_name,$title,$description);

            /**
             *          * Video has successfully been upload, now lets perform some cleanup functions for this video
             *          */
            if ($status->status['uploadStatus'] == 'uploaded') {
                // Actions to perform for a successful upload
                // delete uploaded file
                $videoPath=Constants::SYS_DOWNLOAD_FOLDER."/".$file_name;
                unlink($videoPath);

                // insert video to table
                Movie::getInstance()->insert(array('id'=>$status['id'],'title'=>$title,'chanel_id'=>$chanel_id,'created_at'=>array('now()')));

                // throw process to check video is ready or not
                BackgroundProcess::getInstance()->throwProcess('/cron/video/status',array('video_id'=>$status['id']));
            }
        } catch(Exception $e) {
            return ResponseBuilder::error($e);
        }

    }
    public function download() {
        try {
            $type=InputHelper::getInput('type',true);
            $video_id=InputHelper::getInput('video_id',true);
            $title=InputHelper::getInput('title',true);
            $description=InputHelper::getInput('description',false,'');
            $chanel_id=InputHelper::getInput('chanel_id',true);

            $video_helper=new VideoHelper();
            $filename='';
            if($type==Constants::VIDEO_YOUTUBE) {
                $filename=$video_helper->downloadYoutubeVideo($video_id);
            } elseif($type==Constants::VIDEO_LINK) {
                $filename=$video_helper->downloadVideo($video_id);
            } else {
                throw new APIException("video type not support in downloader");
            }

            // create cron tab to upload video to youtube
            BackgroundProcess::getInstance()->throwProcess(
                '/cron/video/upload',
                array('file_name'=>$filename,
                    'title'=>$title,
                    'description'=>$description,
                    'chanel_id'=>$chanel_id));
        } catch(Exception $e) {
            return ResponseBuilder::error($e);
        }

    }
    public function checkVideoStatus() {
        try {
            $video_id=InputHelper::getInput('video_id',true);
            $video_helper=new VideoHelper();
            $video_status=$video_helper->isReadyForPublish($video_id);
            if($video_status==1) {
                // update is_ready=true on database
                Movie::getInstance()->update(array('is_ready'=>1),array('id'=>$video_id));
            } elseif($video_status==-1) {
                // delete blocked video
                Log::info("Video ".$video_id." is blocked");
                Movie::getInstance()->delete(array('id'=>$video_id));
            } else {
                // create process to check again
                BackgroundProcess::getInstance()->throwProcess('/cron/video/status',array('video_id'=>$video_id));
            }
        } catch(Exception $e) {
            return ResponseBuilder::error($e);
        }

    }
}