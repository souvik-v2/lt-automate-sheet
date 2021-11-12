function deleteConfirmSprint(id) {
    //console.log(id);
    if (confirm("Are you sure to delete this record?")) {
        location.href = "sprint_view.php?action=deletesprint&sprint_id=" + id;
    }
}
function deleteConfirmProject(id) {
    //console.log(id);
    if (confirm("Are you sure you want to delete this project? It will also delete all sprint data.")) {
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
            project_id: pid
        },
        success: function(response) {
            //Do Something
        }
    });
}
function rmInput(r) {
    $('#rmv_' + r).remove();
}
function callAjax(pid) {
    $.ajax({
        url: "export.php",
        type: "get", //send it through get method
        data: {
            project_id: pid
        },
        success: function(response) {
            //Do Something
            if (confirm("Are you want to download file?")) {
                var win = window.open('download/' + response + '.xlsx');
                if (!win) {
                    alert('Please allow popups for this website');
                }
            }
        }
    });
}
$(document).ready(function() {
    $("#b1").click(function(e) {
        e.preventDefault();
        var r = Math.floor(Math.random() * 99) + 1;
        $("#fieldList").append('<div class="rw" id="rmv_' + r + '"><input type="text" class="form-control developers mt-2" placeholder="Developers" name="developers[]" id="field' + r + '" /><span class="rm" id="d_' + r + '" onclick="rmInput(' + r + ')">-</span></div>');
    });
    //
    $('form').attr('autocomplete', 'off');
});
