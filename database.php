<?php

const HOST = "mariadb";
const NAME = "drupal";
const USER = "drupal";
const PASS = "drupal";

/**
 * Get database PDO connection.
 *
 * @return PDO
 * @throws Exception
 */
function getConnection(): PDO
{
  try {
    return new \PDO(sprintf("mysql:host=%s;dbname=%s", HOST, NAME), USER, PASS);
  } catch (PDOException $e) {
    // Log $e->getMessage().
    throw new \Exception('Database problem.');
  }
}

/**
 * Execute query.
 *
 * @param string $queryString
 * @param array|null $data
 *
 * @return bool
 * @throws Exception
 */
function executeQuery(string $queryString, array $data = NULL): bool
{
  try {
    return getConnection()->prepare($queryString)->execute($data);
  } catch (PDOException $e) {
    // Log $e->getMessage().
    throw new \Exception('Database problem.');
  }
}

/**
 * Get query without placeholders.
 *
 * @throws Exception
 */
function getResult(string $queryString): iterable
{
  $result = [];
  $query = getConnection()->query($queryString);

  if ($query) {
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
  }

  return $result;
}
