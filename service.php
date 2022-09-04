<?php

/**
 * Check email.
 *
 * @param string $email
 *
 * @return bool
 * @throws Exception
 */
function check_email(string $email): bool
{
  // ...
  // Possible to throw Exception on API error to skip update.

  return true;
}


/**
 * Send email.
 *
 * @param string $email
 * @param string $from
 * @param string $to
 * @param string $subject
 * @param array $body
 *
 * @return bool
 */
function send_email(string $email, string $from, string $to, string $subject, array $body): bool
{
  //Send email and return result value
  // ...

  print sprintf("Message has been sent from %s to %s \n Subject: %s \n Body: %s \n\n",
    $from, $to, $subject, sprintf($email, ...$body)
  );

  return true;
}
