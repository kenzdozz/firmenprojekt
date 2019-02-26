<?php
require __DIR__ .'/../vendor/autoload.php';

try {
  $dotenv = \Dotenv\Dotenv::create(__DIR__.'/../');
  $dotenv->load();
} catch (\Throwable $th) {
  echo "NOTICE: '.env' file is required in the root folder to use Environment variables.";
}

class Migration {
  private $db;

  public function __construct() {
    try{
      $this->db = new \PDO('mysql:host='.$_ENV['DB_HOST'].';port='.$_ENV['DB_PORT'].';dbname='.$_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
      $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      $this->db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
    } catch (\PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
  }

  public function migrateUp() {
    $queryCompany = "CREATE TABLE company_bills (id INT AUTO_INCREMENT, company_name VARCHAR(100), company_id INT, bill_amount INT, bill_purpose VARCHAR(255), payment_date DATE NULL, updated_at TIMESTAMP DEFAULT NOW(), created_at TIMESTAMP DEFAULT NOW(), CONSTRAINT PRIMARY KEY (id))";
    return $this->db->query($queryCompany);
  }

  public function migrateDown() {
    $queryCompany = "DROP TABLE IF EXISTS company_bills";
    return $this->db->query($queryCompany);
  }

  public function seed()
  {
    $data = array(
      ['First Company Limited', 273, 20200, 'Purchase', '2018-08-09'],
      ['Atlantis Corporation', 343, 5200, 'Maintenance', '2008-02-12'],
      ['Second Invented Company', 1183, 6000, 'Building Design', '2005-08-03'],
      ['Dangote Refinery', 23, 400200, 'Equipments', '2019-08-08'],
      ['Third Global Limited', 1273, 20200, 'Purchase', '2010-01-10']
    );
    foreach($data as $item) {
      $prepare = $this->db->prepare('INSERT INTO company_bills (company_name, company_id, bill_amount, bill_purpose, payment_date) VALUES (?, ?, ?, ?, ?)');
      $prepare->execute($item);
    }
  }
}

function runMigrationUp()
{
  try {
    $migration = new Migration();
    $migration->migrateUp();
    echo 'Table created successfully' .PHP_EOL;
  } catch (\Throwable $th) {
    print $th->getMessage() .PHP_EOL;
  }
}

function runMigrationDown()
{
  try {
    $migration = new Migration();
    $migration->migrateDown();
    echo 'Table dropped successfully' .PHP_EOL;
  } catch (\Throwable $th) {
    print $th->getMessage() .PHP_EOL;
  }
}

function runSeeder()
{
  try {
    $migration = new Migration();
    $migration->seed();
    echo 'Seed successfully completed' .PHP_EOL;
  } catch (\Throwable $th) {
    print $th->getMessage() .PHP_EOL;
  }
}