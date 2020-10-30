<?php
$param = [];

// Check the requests
if(isset($_POST['action'])) $action = htmlspecialchars($_POST['action']); else $action = '';
if(isset($_POST['item'])) $item = htmlspecialchars($_POST['item']); else $item = '';
if(isset($_POST['id'])) $id = htmlspecialchars($_POST['id']); else $id = '';

// Set PDO parameters if needed
if($item) array_push($param, $item);
if($id) array_push($param, $id);

// MySQL
include 'config.php';

switch ($action) { // SQL Queries
  case 'getProjects':
    define('SQL', 'SELECT projects.p_id AS id, projects.name, GROUP_CONCAT(users.name SEPARATOR "<br>") AS assignments FROM projects LEFT JOIN assignments ON assignments.p_id = projects.p_id LEFT JOIN users ON users.u_id = assignments.u_id GROUP BY name ORDER BY projects.p_id DESC');
    break;

  case 'addProject':
    define('SQL', 'INSERT INTO projects SET name = ?');
    break;

  case 'delProject':
    define('SQL', 'DELETE FROM projects WHERE p_id = ?');
    break;

  case 'updProject':
    define('SQL', 'UPDATE projects SET name = ? WHERE p_id = ?');
    break;

  case 'getWorkers':
    define('SQL', 'SELECT users.u_id AS id, users.name, GROUP_CONCAT(projects.name SEPARATOR "<br>") AS assignments FROM users LEFT JOIN assignments ON assignments.u_id = users.u_id LEFT JOIN projects ON projects.p_id = assignments.p_id GROUP BY name ORDER BY users.u_id DESC');
    break;

  case 'addWorker':
    define('SQL', 'INSERT INTO users SET name = ?');
    break;
    
  case 'delWorker':
    define('SQL', 'DELETE FROM users WHERE u_id = ?');
    break;
    
  case 'updWorker':
    define('SQL', 'UPDATE users SET name = ? WHERE u_id = ?');
    break;
    
  case 'getAssignments':
    define('SQL', 'SELECT u_id AS id, name, (SELECT COUNT(id) FROM assignments WHERE assignments.u_id = users.u_id AND assignments.p_id = ?) AS assignments FROM users ORDER BY assignments DESC, name');
    break;
    
  case 'addAssignments':
    define('SQL', 'INSERT INTO assignments SET p_id = ?, u_id = ?');
    break;
    
  case 'delAssignments':
    define('SQL', 'DELETE FROM assignments WHERE p_id = ? AND u_id = ?');
    break;

  default: // Die if action is not recognised
    die('Task failed successfuly!');
}

try { // Connect to the database
  $pdo = new PDO('mysql:host=' . db_host . ';dbname=' . db_name . ';charset=' . db_charset, db_user, db_pass);
} catch (PDOException $exception) {
	die('Failed to connect to the database! Check config.php if credentials are correct.');
}

// Execute the SQL query
$stmt = $pdo->prepare(SQL);
$stmt->execute($param);
$data['data'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(!empty($data)) echo json_encode($data); // Response