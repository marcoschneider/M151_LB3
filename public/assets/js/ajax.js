let ajaxUrl = 'src/requestHandler.php';
$(document).ready(function () {
  $('#autocomplete-input').on('focus', function (e) {
    getAllPlaces();
  });

  $('#login').on("click", function () {
    login();
  });

  $(document).on("click", '.delete-student', function (e) {
    let studentId = $(e.target).data("id");
    deleteStudent(studentId);
  });

  $('#add-student').on("click", function () {
    addStudents();
  });

  $('#register').on("click", function () {
    register();
  })
});

let addStudents = function() {
  let firstname = $('#firstname').val();
  let lastname = $('#lastname').val();
  let place = $('#autocomplete-input-value').val();
  let values = {firstname, lastname, place};

  $.ajax({
    url: ajaxUrl,
    type: 'post',
    data: {json_data: JSON.stringify({
      trigger: 'addStudent',
      values: values
    })},
    success: function (res) {
      if (res === true) {
        loadStudents();
        M.updateTextFields();
        toastr.success("Student wurde hinzugefügt!");
      }else{
        for (let i = 0; i < res.length; i++) {
          toastr.error(res[i]);
        }
      }
    },
    error: function (e) {
      console.log(e);
      $('#output-error').html(e.responseText);
    }
  });
};

let getAllPlaces = function() {
  $.ajax({
    type: 'POST',
    url: ajaxUrl,
    data: {
      json_data: JSON.stringify({
        trigger: 'getAllPlaces',
      })
    },
    success: function(resp) {
      $('input.autocomplete').autocomplete({
        data: resp.data,
        onAutocomplete: function (value) {
          $('#autocomplete-input-value').val(value);
        }
      });
    },
    error: function (e) {
      console.log(e);
      $('#output-error').html(e.responseText);
    }
  });
};

let getAllStudents = function(callback) {
  $.ajax({
    url: ajaxUrl,
    type: 'post',
    data: {json_data: JSON.stringify({
      trigger: 'getAllStudents',
    })},
    success:function (res) {
      outputStudentsTable(res);
      callback(res);
    },
    error:function (e) {
      $('#output-error').html(e.responseText);
    }
  });
};

function outputStudentsTable(res) {
  let output = '';
  if (res.length === 0) {
    output = '<h4>Keine Studenten erfasst</h4>';
  }
  $.each(res, function (key, resp) {
    output += '<tr>';
    output += `<td>${resp.firstname}</td>`;
    output += `<td>${resp.lastname}</td>`;
    output += `<td>${resp.placeid} ${resp.placename}</td>`;
    output += `<td><i data-id="${resp.studentsid}" class="material-icons red-text delete-student">delete</i></td>`;
    output += `<td><i data-id="${resp.studentsid}" class="material-icons orange-text delete-student">edit</i></td>`;
    output += '</tr>';
  });
  $('#output-table').html(output);
}

function loadStudents() {
  window.setTimeout(function () {
    getAllStudents();
  }, 50);
}

let deleteStudent = function(studentId) {
  $.ajax({
    url: ajaxUrl,
    type: 'post',
    data: {json_data: JSON.stringify({
      trigger: 'deleteStudent',
      studentid: studentId,
    })},
    success: function (res) {
      if (res === true) {
        loadStudents();
        toastr.success("Student wurde gelöscht!");
      }else{
        toastr.error(res);
      }
    },
    error: function (e) {
      console.log(e);
      $('#output-error').html(e.responseText);
    }
  })
};

let login = function () {
  let email = $('#email').val();
  let password = $('#password').val();

  $.ajax({
    url: ajaxUrl,
    type: 'post',
    data: {json_data: JSON.stringify({
      trigger: 'login',
      email: email,
      password: password
    })},
    success:function (res) {
      if (res !== true) {
        toastr.error("Benutzername oder Passwort falsch");
      }
    },
    error:function (e) {
      $('#output-error').html(e.responseText);
    }
  })
};

let register = function () {
  let email = $('#email').val();
  let password = $('#password').val();

  $.ajax({
    url: ajaxUrl,
    type: 'post',
    data: {json_data: JSON.stringify({
      trigger: 'register',
      email: email,
      password: password
    })},
    success: function (res) {
      if (res === true) {
        toastr.success("Dein Konto wurde erstellt!");
      }else{
        for (let i = 0; i < res.length; i++) {
          toastr.error(res[i]);
        }
      }
    },
    error:function (e) {
      $('#output-error').html(e.responseText);
    }
  })
};