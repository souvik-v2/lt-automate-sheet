function deactiveConfirmDeveloper(id, status) {
  console.log(id, status);
  if (confirm("Are you sure you want to update developer status?")) {
    location.href =
      "developer.php?action=deactivedeveloper&developer_id=" +
      id +
      "&developer_status=" +
      status;
  }
}
function deleteConfirmSprint(id) {
  //console.log(id);
  if (
    confirm(
      "Are you sure to delete this record? It will also delete report data."
    )
  ) {
    location.href = "sprint_view.php?action=deletesprint&sprint_id=" + id;
  }
}
function deleteConfirmProject(id) {
  //console.log(id);
  if (
    confirm(
      "Are you sure you want to delete this project? It will also delete all sprint & report data."
    )
  ) {
    location.href = "project.php?action=deleteproject&project_id=" + id;
  }
}
function deleteConfirmUser(id) {
  //console.log(id);
  if (confirm("Are you sure to delete this user?")) {
    location.href = "user_management.php?action=deleteuser&id=" + id;
  }
}
function getID(pid) {
  $.ajax({
    url: "ajax_required.php",
    type: "get", //send it through get method
    data: {
      project_id: pid,
    },
    success: function (response) {
      //Do Something
    },
  });
}
function callAjax(pid) {
  $.ajax({
    url: "export.php",
    type: "get", //send it through get method
    data: {
      project_id: pid,
    },
    success: function (response) {
      //Do Something
      if (confirm("Are you want to download file?")) {
        var win = window.open("download/" + response + ".xlsx");
        if (!win) {
          alert("Please allow popups for this website");
        }
      }
    },
  });
}
$(document).ready(function () {
  $('#example').DataTable();
  //
  $("form").attr("autocomplete", "off");
  //
  $("#report_project_id").on("change", function () {  
    var projectID = $("#report_project_id").val();
    $.ajax({
      url: "ajax_required.php?action=reportcall",
      type: "get",
      async: false,
      data: {
        projectID: projectID,
      },
      success: function (response) {
        $("#sprint-div").html(response);
      },
    });
  });
  //
  $('form').submit(function(e) {
    //removes the disabled attribute from all elements on form submit.
		$(':disabled').each(function(e) {
			$(this).removeAttr('disabled');
		})
	});
  //
});
