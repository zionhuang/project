<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SwooleServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sw:op';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo "start\n";
        $server = new \swoole_websocket_server("0.0.0.0", 9501);

        $server->set(array(
            'worker' => 8,
        ));

        $server->on('open', function (swoole_websocket_server $server, $request) {
            echo "server: handshake success with fd{$request->fd}\n";
        });

        $server->on('message', function (swoole_websocket_server $server, $frame) {
            echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
            $server->push($frame->fd, "this is server");
        });

        $server->on('close', function ($ser, $fd) {
            echo "client {$fd} closed\n";
        });

        $server->start();

    }
}
