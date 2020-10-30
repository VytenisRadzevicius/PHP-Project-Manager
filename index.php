<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Project Manager</title>

    <!-- Styling -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
    <style>
      body { overflow-x: hidden; }
      .z-10 { z-index: 10; }
      .dataTables_filter { margin-right: 20px; }
      .blink { animation: blinker 1s linear 3; }
      @keyframes blinker { 50% { opacity: 0; }}
    </style>

  </head>
<body>

<!-- Navigation -->
<nav class="navbar sticky-top navbar-expand-sm navbar-dark bg-dark text-warning mb-2">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarText">
    <div class="navbar-nav mr-auto btn-group btn-group-toggle" data-toggle="buttons">
      <label class="btn btn-outline-warning font-weight-bold active">
        <input type="radio" name="nav" value="getProjects" checked><i class="fas fa-project-diagram"></i> Projects
      </label>
      <label class="btn btn-outline-warning font-weight-bold">
        <input type="radio" name="nav" value="getWorkers"><i class="fas fa-users"></i> Manpower
      </label>
    </div>

    <span class="h3">
      PHP Project Manager
    </span>

  </div>
</nav>

<div id="buttons" class="text-nowrap btn-group z-10 position-absolute pl-2">
  <button id="getProjects" class="btn btn-outline-secondary py-0" data-toggle="modal" data-target="#add-modal" data-content="Project"><i class="fas fa-folder-plus"></i> Add Project</button>
  <button id="getWorkers" class="btn btn-outline-secondary py-0" data-toggle="modal" data-target="#add-modal" data-content="Worker"><i class="fas fa-user-plus"></i> Add Worker</button>
  <button id="getAssignments" class="btn btn-outline-secondary py-0" data-toggle="modal" data-target="#assign-modal"><i class="fas fa-stream"></i> Assignments</button>
</div>

<!-- Table -->
<table id="table" class="table table-striped table-sm table-hover mb-5">
  <caption>&nbsp;End of list.</caption>
  <thead>
    <tr>
      <th>#</th>
      <th>Projects</th>
      <th>Assignments</th>
      <th>Edit / Delete</th>
    </tr>
  </thead>
</table>

<!-- Footer -->
<nav class="navbar fixed-bottom navbar-dark bg-dark text-warning">
  PHP Project Manager &copy; 2020
</nav>

<!-- Modals -->
<div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content bg-dark text-warning">
      <div class="card-header font-weight-bold text-capitalize"><i class="fas fa-plus"></i> Add New&nbsp;<span></span>
        <button type="button" class="close text-warning" data-dismiss="modal" aria-label="Close">
          <i aria-hidden="true">&times;</i>
        </button>
      </div>

      <div id="modalBody" class="modal-body">
        <input type="hidden" id="select" name="select" value="">
        <div id="modalAlert" class="alert alert-warning fade show"></div>
        Enter the name of the new <span class="text-lowercase"></span>:<br><br>
        <form id="form">
          <input type="text" id="name" name="name" class="form-control" autocomplete="off" required><br>
          <button class="btn btn-lg btn-warning btn-block font-weight-bold text-capitalize" id="submit" type="submit" disabled>Add <span></span> <i class="fas fa-plus"></i></button>
        </form>
    </div>

    </div>
  </div>
</div>

<div class="modal fade" id="update-modal" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content bg-dark text-warning">
      <div class="card-header font-weight-bold text-capitalize"><i class="fas fa-edit"></i> Edit&nbsp;<span></span>
        <button type="button" class="close text-warning" data-dismiss="modal" aria-label="Close">
          <i aria-hidden="true">&times;</i>
        </button>
      </div>

      <div id="update-modalBody" class="modal-body">
        <input type="hidden" id="update-select" name="update-select" value="">
        <div id="update-modalAlert" class="alert alert-warning fade show"></div>
        Edit the name of <span class="text-lowercase"></span>:<br><br>
        <form id="update-form">
          <input type="text" id="update-name" name="update-name" class="form-control" autocomplete="off" required><br>
          <button class="btn btn-lg btn-warning btn-block font-weight-bold text-capitalize" id="update-submit" type="submit" disabled>Update <span></span> <i class="fas fa-edit"></i></button>
        </form>
    </div>

    </div>
  </div>
</div>

<div class="modal fade" id="assign-modal" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content bg-dark text-warning">
      <div class="card-header font-weight-bold text-capitalize"><i class="fas fa-plus"></i> Assignments
        <button type="button" class="close text-warning" data-dismiss="modal" aria-label="Close">
          <i aria-hidden="true">&times;</i>
        </button>
      </div>

      <div id="assign-modalBody" class="modal-body">
        <div id="assign-modalAlert" class="alert alert-warning fade show"></div>
        Select a project and a person you wish to assign:<br><br>
        <form id="assign-form">
        <select id="assign-projects" name="assign-projects" class="custom-select mb-3">
        </select>
        <select id="assign-workers" name="assign-workers" class="custom-select mb-3" disabled>
          <option value="" selected hidden>Select a person..</option>
        </select>
          <button class="btn btn-lg btn-warning btn-block font-weight-bold text-capitalize" id="assign-submit" type="submit" disabled>Assign <span></span> <i class="fas fa-user-edit"></i></button>
        </form>
    </div>

    </div>
  </div>
</div>

<div class="modal fade" id="sure-modal" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content bg-dark text-warning">
      <div class="card-header font-weight-bold text-capitalize">Confirmation
        <button type="button" class="close text-warning" data-dismiss="modal" aria-label="Close">
          <i aria-hidden="true">&times;</i>
        </button>
      </div>

      <div id="sure-alert" class="modal-body"></div>
      <div id="sure-body" class="modal-body">
        Are you sure you want to delete <span class="font-weight-bold"></span>?<br><br>
        <form id="sure-form">
          <input type="hidden" id="cid" name="cid" value="">
          <input type="hidden" id="content" name="content" value="">
          <div class="text-nowrap btn-group btn-block">
            <button class="btn btn-lg btn-success font-weight-bold text-capitalize" id="sure-submit" type="submit"><i class="fas fa-check"></i> Yes</button>
            <button class="btn btn-lg btn-danger font-weight-bold text-capitalize" data-dismiss="modal"><i class="fas fa-times"></i> No</button>
          </div>
        </form>
    </div>

    </div>
  </div>
</div>

<!-- JavaScript -->
<script src="https://kit.fontawesome.com/686684acdc.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
<script src="javascript.js"></script>

</body>
</html>