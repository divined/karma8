<?php

require_once 'cron.php';

// Todo: CRON
// collect_cron() once a day. (59 23 * * *)
// check_emails_cron() once a day before collect_cron(). (59 22 * * *)
// send_cron() can be 1 time per hour, need to monitor real queue length. (0 */1 * * *)

try {
    check_emails_cron();
    collect_cron();
    send_cron();
} catch (Exception $e) {
    echo "Does not work! " . $e->getMessage();
}
