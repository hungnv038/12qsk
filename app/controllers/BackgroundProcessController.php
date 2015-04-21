<?php

class BackgroundProcessController extends BaseController {
    const NUMBER_RECORD = 6;
    public function process( $processId ) {
        $bg = new BackgroundProcess();
        try {
            //$runTime['start_time'] = microtime(true);
            $bg->process($processId);
            //Log::info("Run process: $processId", $runTime);
        } catch( Exception $e ) {
            return ResponseBuilder::error($e);
        }
        return;
    }

    public function cron() {
        try {
            // Run batch process
            //$runTime['start_time'] = microtime(true);
            $processBatch = BackgroundProcess::getBatchProcess(self::NUMBER_RECORD);
            foreach($processBatch as $process) {
                $this->process($process->id);
            }
        } catch (Exception $e ) {
            return ResponseBuilder::error($e);
        }
        return;
    }
}
