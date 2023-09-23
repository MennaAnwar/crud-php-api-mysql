$(document).ready(function () {
  $.ajax({
    url: "submit.php",
    method: "GET",
    dataType: "json",
    success: function (data) {
      data.forEach((user) => {
        $("#table").append(`
       <form
        action="submit.php"
        method="post"
        class="row justify-content-around mb-2 border-bottom"
      >
        <input id="action" name="action" value="edit" class="d-none" />
        <input id="action" name="id" value="${user.id}" class="d-none" />
        <div class="col-4">${user.id}</div>
        <input
          id="${user.id}"
          class="col-4"
          name="fullname"
          value="${user.fullname}"
          onChange="handleChange(this,'${user.fullname}')"
          disabled
        />
        <div class="col-4 mb-1">
        <div  class="btn btn-success edit" onclick="editField(this, ${user.id})">Edit</div>
        <button id="user_${user.id}" type="submit" class="btn btn-primary save hide" disabled>Save</button>
        <div class="btn btn-primary cancel hide" onClick="cancel()">Cancel</div>
        <button
          type="button"
          class="btn btn-danger delete"
          onclick="Delete_User(${user.id})"
        >
          Delete
        </button>
        </div>
      </form>
        `);
      });
    },
    error: {},
  });
});

function handleChange(element, username) {
  if ($(element).val() != username) {
    $("#user_" + element.id).prop("disabled", false);
  }
  console.log($(element).val() === username);
}

function cancel() {
  location.reload();
}

function editField(element, id) {
  $(element).toggleClass("hide");
  $(element).next().toggleClass("hide");
  $(element).next().next().toggleClass("hide");
  $("#" + id).prop("disabled", false);
  $(".delete").prop("disabled", true);
}

function Delete_User(id) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "submit.php",
        method: "POST",
        data: { id: id },
        success: function (data) {
          Swal.fire("Deleted!", "Your file has been deleted.", "success").then(
            () => {
              location.reload();
            }
          );
        },
        error: {},
      });
    }
  });
}
