<?php

namespace App\Listeners;

use App\Events\StartCrawling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log; // Log 파사드 추가
use Illuminate\Support\Facades\File; // File 파사드 추가

class ProcessCrawling implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(StartCrawling $event): void
    {
        $eventSourceId = $event->eventSourceId;
        $logFilePath = storage_path('logs/crawler_output_' . uniqid() . '.log'); // 임시 로그 파일 경로

        $command = 'node ' . base_path('crawler/index.js');
        if ($eventSourceId) {
            $command .= ' ' . escapeshellarg($eventSourceId);
        }
        // Node.js 크롤러의 stdout과 stderr를 임시 파일로 리다이렉션
        $command .= ' > ' . escapeshellarg($logFilePath) . ' 2>&1';

        $returnVar = 0; // exec의 반환 코드 초기화
        exec($command, $output, $returnVar); // $output은 이제 사용되지 않음 (파일로 리다이렉션)

        // 크롤링 완료 후 last_crawled_at 업데이트
        if ($eventSourceId) {
            $eventSource = \App\Models\EventSource::find($eventSourceId);
            if ($eventSource) {
                $eventSource->update(['last_crawled_at' => now()]);
            }
        } else {
            // 전체 크롤링의 경우, 모든 활성 소스의 last_crawled_at을 업데이트
            \App\Models\EventSource::where('is_active', true)->update(['last_crawled_at' => now()]);
        }

        // 임시 로그 파일 내용 읽어서 crawler 채널로 로깅
        if (File::exists($logFilePath)) {
            $crawlerOutput = File::get($logFilePath);
            if ($returnVar === 0) {
                Log::channel('crawler')->info('Node.js crawler executed successfully.', [
                    'eventSourceId' => $eventSourceId,
                    'command' => $command,
                    'output_log' => $crawlerOutput,
                ]);
            } else {
                Log::channel('crawler')->error('Node.js crawler execution failed.', [
                    'eventSourceId' => $eventSourceId,
                    'command' => $command,
                    'output_log' => $crawlerOutput,
                    'return_code' => $returnVar,
                ]);
            }
            File::delete($logFilePath); // 임시 로그 파일 삭제
        } else {
            Log::channel('crawler')->warning('Node.js crawler log file not found.', [
                'eventSourceId' => $eventSourceId,
                'command' => $command,
            ]);
        }
    }
}