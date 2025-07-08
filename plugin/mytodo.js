jQuery(document).ready(function($) {
  const api = MyTodoData.api_url;
  const nonce = MyTodoData.nonce;

  function loadTasks() {
    $.ajax({ url: api, method: 'GET' }).done(renderTasks);
  }

  function renderTasks(tasks) {
    const list = $('#taskList').empty();
    tasks.forEach(task => {
      list.append(`
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <span contenteditable="true" data-id="${task.id}">${task.task}</span>
          <div>
            <button class="btn btn-sm btn-success save-btn" data-id="${task.id}">Save</button>
            <button class="btn btn-sm btn-danger delete-btn" data-id="${task.id}">Delete</button>
          </div>
        </li>
      `);
    });
  }

  $('#addTask').click(function() {
    const task = $('#taskInput').val().trim();
    if(task) {
      $.ajax({
        url: api,
        method: 'POST',
        data: { task },
        beforeSend: xhr => xhr.setRequestHeader('X-WP-Nonce', nonce),
        success: loadTasks
      });
      $('#taskInput').val('');
    }
  });

  $('#taskList').on('click', '.save-btn', function() {
    const id = $(this).data('id');
    const task = $(this).closest('li').find('span').text().trim();
    $.ajax({
      url: `${api}/${id}`,
      method: 'PUT',
      data: { task },
      beforeSend: xhr => xhr.setRequestHeader('X-WP-Nonce', nonce),
      success: loadTasks
    });
  });

  $('#taskList').on('click', '.delete-btn', function() {
    const id = $(this).data('id');
    $.ajax({
      url: `${api}/${id}`,
      method: 'DELETE',
      beforeSend: xhr => xhr.setRequestHeader('X-WP-Nonce', nonce),
      success: loadTasks
    });
  });

  loadTasks();
});
