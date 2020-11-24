<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Spatie\DbDumper\Databases\MySql;

class DBController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Spatie\DbDumper\Exceptions\CannotStartDump
     * @throws \Spatie\DbDumper\Exceptions\DumpFailed
     */
    public function download(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $file = 'dump.sql';

        MySql::create()
            ->setDbName(config('database.connections.mysql.database'))
            ->setUserName(config('database.connections.mysql.username'))
            ->setPassword(config('database.connections.mysql.password'))
            ->setPort(config('database.connections.mysql.port'))
            ->setHost(config('database.connections.mysql.host'))
            ->dumpToFile(Storage::path($file));

        return response()->download(Storage::path($file))->deleteFileAfterSend(true);
    }
}
