<?php

require_once 'database.php';

const QUEUE_LENGTH = 60;

/**
 * Insert new mail to a queue.
 *
 * @param array $row
 *
 * @return bool
 * @throws Exception
 */
function addMailToQueue(array $row): bool
{
  $queryString = "INSERT INTO queue2 (username, email, validts) VALUES (:username, :email, :validts)";

  return executeQuery($queryString, $row);
}

/**
 * Update mails.
 *
 * @param int $id
 * @param bool $valid
 *
 * @return bool
 * @throws Exception
 */
function updateCheckedMail(int $id, bool $valid): bool
{
  $queryString = "UPDATE emails2 e SET checked = true, valid = :valid WHERE e.id = :id";
  return executeQuery($queryString, ['id' => $id, 'valid' => $valid]);
}

/**
 * Delete sent mails from a queue.
 *
 * @param int $id
 *
 * @return bool
 * @throws Exception
 */
function deleteExecutedMail(int $id): bool
{
  $queryString = "DELETE FROM queue2 WHERE id = :id";
  return executeQuery($queryString, ['id' => $id]);
}

/**
 * Get all valid emails. Confirmed or validated by external API service.
 *
 * @return iterable
 * @throws Exception
 */
function getValidEmails(): iterable
{
  $queryString = "SELECT u.username, u.email, u.validts FROM users2 u
                  INNER JOIN emails2 e ON e.email=u.email
                  WHERE validts <= UNIX_TIMESTAMP() + 3 * 24 * 60 * 60 && (u.confirmed || (e.checked && e.valid))";

  return getResult($queryString);
}

/**
 * Get all not checked emails.
 *
 * @return iterable
 * @throws Exception
 */
function getNotCheckedEmails(): iterable
{
  $queryString = "SELECT e.id, e.email FROM emails2 e
                  INNER JOIN users2 u ON e.email=u.email
                  WHERE (!u.confirmed && !e.checked)";

  return getResult($queryString);
}

/**
 * Get all added to a queue data.
 *
 * @return iterable
 * @throws Exception
 */
function getQueuedEmails(): iterable
{
  $queryString = sprintf("SELECT id, username, email, validts FROM queue2 LIMIT %d", QUEUE_LENGTH);

  return getResult($queryString);
}
