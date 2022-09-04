<?php

require_once 'repository.php';
require_once 'service.php';

const MAIL_FROM = "example@mail.com";
const SUBJECT = "Subscription will be expired soon!";
const TEMPLATE = "Hello %s, your subscription will be expired on %s";

/**
 * Cronjob to collect expired soon subscriptions.
 *
 * @throws Exception
 */
function collect_cron()
{
  foreach (getValidEmails() as $row) {
    // Dispatch message to the queue in symfony context
    // $this->messageBus->dispatch(new Message($row));
    // Or save to the additional table
    // If need to save storage place we can save only user ids, and then join other data after - based on length

    addMailToQueue($row);
  }
}

/**
 * Cronjob to send mails to expired soon subscriptions.
 *
 * @throws Exception
 */
function send_cron()
{
  foreach (getQueuedEmails() as $row) {
    $result = send_email(TEMPLATE, MAIL_FROM, $row['email'], SUBJECT, [
      $row['username'],
      date('m/d/Y H:i:s', $row['validts'])
    ]);

    if ($result) {
      deleteExecutedMail($row['id']);
    }
  }
}

/**
 * Cronjob to check emails.
 *
 * @throws Exception
 */
function check_emails_cron()
{
  //Todo: better to split collecting and queue cron.
  foreach (getNotCheckedEmails() as $row) {
    try {
      $result = check_email($row['email']);
      updateCheckedMail($row['id'], $result);
    }
    catch (Exception $e) {
      // Do not update database on exception. Log $e->getMessage().
    }
  }
}

