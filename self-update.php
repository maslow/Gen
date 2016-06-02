<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 6/1/16
 * Time: 9:46 AM
 */

const DS = DIRECTORY_SEPARATOR;
const gen_path = __DIR__ . '/gen';
const dashboard_path = __DIR__ . '/modules/dashboard';
const download_path = __DIR__ . '/runtime/.gen';
const backup_path = __DIR__ . '/runtime/.gen_backup';

if (!file_exists(download_path)) mkdir(download_path);
if (!file_exists(backup_path)) mkdir(backup_path);

function get_files_data($files, $base_path)
{
    $files_data = [];
    foreach ($files as $f)
        $files_data [$f] = md5_file($base_path . DS . $f);
    return $files_data;
}

function get_new_files($download, $old)
{
    $files = [];
    $new_data = get_files_data(get_sub_files($download), $download);
    $old_data = get_files_data(get_sub_files($old), $old);

    foreach ($old_data as $f => $hash) {
        if (isset($new_data[$f]) && $old_data[$f] !== $new_data[$f])
            $files [] = $f;
    }
    return array_merge($files, array_diff(get_sub_files(download_path . '/gen'), get_sub_files(gen_path)));
}

function get_sub_files($directory)
{
    $dirs = [];
    $dir = dir($directory);
    while ($file = $dir->read()) {
        if (is_dir($directory . DS . $file) && ($file != ".") && ($file != "..")) {
            $files = get_sub_files($directory . DS . $file);
            foreach ($files as $f)
                $dirs [] = $file . DS . $f;
        } elseif (($file != ".") AND ($file != ".."))
            array_push($dirs, $file);
    }
    $dir->close();
    return $dirs;
}

function recurse_copy($src, $dst)
{

    $dir = opendir($src);
    mkdir($dst);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                recurse_copy($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

function del_dir($dir)
{
    $str = strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' ? "rmdir /s/q " . $dir : "rm -Rf " . $dir;
    return system($str);
}

function download_gen()
{
    return system("git clone https://git.coding.net/maslow/Gen.git " . download_path);
}

function clear_downloaded()
{
    del_dir(download_path);
}

function clear_backup()
{
    del_dir(backup_path . DS . "*");
}

function backup_gen()
{
    recurse_copy(gen_path, backup_path . '/gen');
}

function backup_dashboard()
{
    recurse_copy(dashboard_path, backup_path . '/dashboard');
}

function update_gen()
{

    clear_backup();
    backup_gen();

    $new_files = get_new_files(download_path . '/gen', gen_path);
    foreach ($new_files as $f) {
        if (copy(download_path . '/gen/' . $f, gen_path . DS . $f))
            echo download_path . '/gen/' . $f . " => " . gen_path . DS . $f;
    }
}

function update_dashboard()
{
    clear_backup();
    backup_dashboard();

    $new_files = get_new_files(download_path . '/modules/dashboard', dashboard_path);
    foreach ($new_files as $f) {
        if (copy(download_path . '//modules/dashboard/' . $f, dashboard_path . DS . $f))
            echo download_path . '//modules/dashboard/' . $f . " => " . dashboard_path . DS . $f;
    }

}

function main()
{
    clear_downloaded();
    download_gen();

    if (!file_exists(download_path . '/gen'))
        throw new \Exception('Download gen failed');


    update_gen();

    update_dashboard();

    clear_downloaded();
}

main();