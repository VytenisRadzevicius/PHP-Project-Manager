var table = $('#table').DataTable({ // Initialize DataTable
  language: {
    search: "_INPUT_",
    searchPlaceholder: "Search...",
    zeroRecords: "No entries."
  },
  "info":     false,
  "paging":   false,
  "columnDefs": [
      { "orderable": false, "className": "text-center", "targets": [3] },
      { "className": "text-truncate", "targets": [1, 2] },
      { "className": "text-center text-truncate", "targets": [0] }
  ]
});

function execute(a, n = null, i = null) { // Manage data using AJAX

  $.ajax({
    type: "POST",
    url: "ajax.php",
    dataType: "JSON",
    cache: false,
    data: { 'action': a, 'item': n, 'id': i }
  }).done(function(json) {

    switch(a) {
      case 'getProjects':
        populateTable(json, a);
        break;

      case 'addProject':
        populateAlert('getProjects', n);
        break;

      case 'delProject':
        execute('getProjects');
        break;

      case 'updProject':
        populateupdAlert('getProjects', n);
        break;

      case 'getWorkers':
        populateTable(json, a);
        break;

      case 'addWorker':
        populateAlert('getWorkers', n);
        break;
        
      case 'delWorker':
        execute('getWorkers');
        break;
        
      case 'updWorker':
        populateupdAlert('getWorkers', n);
        break;
        
      case 'getAssignments':
        populateSelect(json);
        break;
        
      case 'addAssignments':
        populateassAlert('getWorkers');
        break;
        
      case 'delAssignments':
        populateassAlert('getWorkers');
        break;
    }
  }).fail(function(json){
    alert(json.responseText);
  });
}

execute('getProjects'); // Initial load of data

function populateAlert(a, n = 'Data') { // Populate the alert and refresh if needed
  $('#modalAlert').fadeOut();
  $('#modalAlert').text(n + ' added successfuly.');
  $('#modalAlert').fadeIn();
  if ($('input[type=radio][name=nav]:checked').val() == a) {
    execute(a);
  } else if(a == 'getProjects') {
    let i = parseInt($('#assign-projects option:nth-child(2)').val()) + 1;
    $("#assign-projects option:selected").after(new Option(n, i));
  }
}

function populateupdAlert(a, n = 'Data') { // Populate the alert and refresh if needed
  $('#update-modalAlert').fadeOut();
  $('#update-modalAlert').text(n + ' updated successfuly.');
  $('#update-modal span').text(n);
  $('#update-modal input[name=update-name]').val(n);
  $('#update-modalAlert').fadeIn();
  if ($('input[type=radio][name=nav]:checked').val() == a) {
    execute(a);
  }
}

function populateassAlert(a, n = 'Assignments') { // Populate the alert and refresh if needed
  $('#assign-modalAlert').fadeOut();
  $('#assign-modalAlert').text(n + ' updated successfuly.');
  $('#assign-modalAlert').fadeIn();
  
  execute($('input[type=radio][name=nav]:checked').val());
}

function populateTable(j = [], t = 'getProjects') { // Populate the table with data
  table.clear();
  if(t == 'getProjects') {
    $('#assign-projects').empty();
    $('#assign-projects').append('<option selected hidden>Select a project..</option>');
  }

  if(j.data.length > 0) {
    for (i = 0; i < j.data.length; i++) { // Format and add rows
      let arr = [];
      arr[0] = i + 1;
      arr[1] = j.data[i].name;
      arr[2] = j.data[i].assignments;
      arr[3] = '<div class="text-nowrap btn-group">' +
                 '<button class="btn btn-sm btn-outline-secondary py-0" data-toggle="modal" data-target="#update-modal" data-c="' + j.data[i].name + '" data-cid="' + j.data[i].id + '"><i class="fas fa-edit"></i></button>' +
                 '<button class="btn btn-sm btn-outline-danger py-0" data-toggle="modal" data-target="#sure-modal" data-c="' + j.data[i].name + '" data-cid="' + j.data[i].id + '"><i class="fas fa-trash"></i></button>' +
               '</div>';

      let rowNode = table.row.add(arr).draw().node();
      $(rowNode).css( 'opacity', '0' ).animate( { opacity: '1' }, 600 );
      if(t == 'getProjects') $('#assign-projects').append(new Option(j.data[i].name, j.data[i].id));
    }
  } else { // Table is empty
    table.draw();
    $('#' + t).toggleClass('blink');
  }
  
  $('th:nth-child(2)').text(t.substring(3));
}

$('input[type=radio][name=nav]').click(function() { // Navigation
    $('#' + this.value).removeClass('blink');
    execute(this.value);
});

$('#add-modal').on('show.bs.modal', function (event) { // Add modal
  let button = $(event.relatedTarget);
  let recipient = button.data('content');

  $('#modalAlert').hide();
  $('#modalAlert').empty();
  $('#submit').prop('disabled', true);
  $('#add-modal input[name=name]').val('');
  $('#add-modal input[type=hidden]').val(recipient);
  $('#add-modal span').text(recipient);
});

$('#update-modal').on('show.bs.modal', function (event) { // Update modal
  let button = $(event.relatedTarget);
  let recipient = button.data('c');
  let id = button.data('cid');

  $('#update-modalAlert').hide();
  $('#update-modalAlert').empty();
  $('#update-submit').prop('disabled', true);
  $('#update-modal input[name=update-name]').val(recipient);
  $('#update-modal input[type=hidden][name=update-select]').val(id);
  $('#update-modal span').text(recipient);
});

$('#sure-modal').on('show.bs.modal', function (event) { // Sure modal
  let button = $(event.relatedTarget);
  let content = button.data('c');
  let id = button.data('cid');

  $('#sure-alert').hide();
  $('#sure-body').show();
  $('#sure-modal input[type=hidden][name=cid]').val(id);
  $('#sure-modal input[type=hidden][name=content]').val(content);
  $('#sure-modal span').text(content);
});

$('#assign-modal').on('show.bs.modal', function () { // Assignment modal
  $('#assign-modalAlert').hide();
  $('#assign-form')[0].reset();
  $('#assign-workers').prop('disabled', true);
  $('#assign-submit').prop('disabled', true);
});

$('#name').on('change input',function(e) { // Check for illegal characters
  let folder = $("#name").val();
  if (!/^(\w+ ?)+$/.test(folder)) $('#submit').prop('disabled', true); else $('#submit').prop('disabled', false);
});

$('#update-name').on('change input',function(e) { // Check for illegal characters
  let folder = $("#update-name").val();
  if (!/^(\w+ ?)+$/.test(folder)) $('#update-submit').prop('disabled', true); else $('#update-submit').prop('disabled', false);
});

$('#form').submit(function(e) { // Submit and reset the form
  e.preventDefault();

  let action = 'add' + $("#select").val();
  let name = $('#name').val();
  if (/^(\w+ ?)+$/.test(name)) {
    execute(action, name);
    $('#name').val('');
    $('#submit').prop('disabled', true);
  }
});

$('#update-form').submit(function(e) { // Submit and reset the form
  e.preventDefault();

  let uid = $('#update-select').val();
  let name = $('#update-name').val();
  if (/^(\w+ ?)+$/.test(name)) {
    execute('upd' + $('input[type=radio][name=nav]:checked').val().slice(3,-1), name, uid);
    $('#update-name').val('');
    $('#update-submit').prop('disabled', true);
  }
});

$('#assign-form').submit(function(e) { // Submit and reset the form
  e.preventDefault();

  let pid = $('#assign-projects').val();
  let uid = $('#assign-workers').val();
  let a = $('#assign-workers option:selected').data('act');
  if(a == 0) execute('addAssignments', pid, uid); else execute('delAssignments', pid, uid);
  $('#assign-form')[0].reset();
  $('#assign-workers').prop('disabled', true);
  $('#assign-submit').prop('disabled', true);
});

$('#sure-form').submit(function(e) { // Submit and reset the form
  e.preventDefault();

  let cid = $('#cid').val();
  let content = $('#content').val();

  execute('del' + $('input[type=radio][name=nav]:checked').val().slice(3,-1), cid);

  $('#sure-body').hide();
  $('#sure-alert').html('<div id="sure-alert" class="alert alert-warning fade show"><b>' + content + '</b> was successfuly deleted!</div>').fadeIn('slow');
});

$('#assign-projects').on('change',function(e) { // Assignment select fetch
  let p = $(this).val(); 
  execute('getAssignments', p);
  $('#assign-workers').prop('disabled', false);
});

$('#assign-workers').on('change',function(e) { // Assignment select fetch
  $('#assign-submit').prop('disabled', false);
});

function populateSelect(j) {
  $('#assign-workers').empty();
  $('#assign-workers').append('<option selected hidden>Select a person..</option>');
  j.data.forEach(e => {
    let i = parseInt(e.assignments) + 3;
    $('#assign-workers').append('<option value="' + e.id + '" data-act="' + e.assignments + '">&#1013' + i + '; ' + e.name + '</option>');
  });
}