let ajaxUrl = 'src/requestHandler.php';
$(document).ready(function () {
  $('#autocomplete-input').on('focus', function (e) {
    getAllPlaces();
  });

  $('#login').on("click", function () {
    login();
  });

  $('#save-edit').on("click", function (e) {
    console.log("clicked");
    let studentId = $(e.target).data("id");
    console.log(studentId);
  });

  $(document).on("click", '.edit-student', function (e) {
    setupForm(e);
    $('#save-edit').on("click", function (e) {
      let studentId = $(e.target).data("id");
      editStudent(studentId);
    });
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

let setupForm = function(e) {
  let target = $(e.target);
  let studentId = target.data("id");
  let firstname_value = target.parent().siblings()[0].innerText;
  let lastname_value = target.parent().siblings()[1].innerText;
  let place_value = target.parent().siblings()[2].innerText;
  let saveButton = $('#add-student');
  let firstname = $('#firstname');
  let lastname = $('#lastname');
  let place_hidden = $('#autocomplete-input-value');
  let place = $('#autocomplete-input');

  M.updateTextFields();

  saveButton.replaceWith('<a id="save-edit" class="waves-effect waves-light btn red lighten-1">Speichern</a>');
  $('#save-edit').attr('data-id', studentId);

  firstname.val(firstname_value);
  lastname.val(lastname_value);
  place_hidden.val(place_value);
  place.val(place_value);

  $([document.documentElement, document.body]).animate({
    scrollTop: $("#form-top").offset().top
  }, 1000);
};

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
      values,
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
    output += `<td><i data-id="${resp.studentsid}" class="material-icons orange-text edit-student">edit</i></td>`;
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

let editStudent = function(studentId) {
  let firstname = $('#firstname').val();
  let lastname = $('#lastname').val();
  let place = $('#autocomplete-input-value').val();
  console.log(place);
  let values = {firstname, lastname, place};
  $.ajax({
    url: ajaxUrl,
    type: 'post',
    data: {json_data: JSON.stringify({
        trigger: 'editStudent',
        studentId,
        values
      })},
    success: function (res) {
      if (res === true) {
        loadStudents();
        toastr.success("Student wurde erfolgreich bearbeitet!");
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