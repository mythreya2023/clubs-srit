$(document).ready(() => {
  $(".add-team-role-btn").click(() => {
    let role = $("#team-role-ipt").val();
    $(".added-team-roles").append(`<div class="text-box team-role">
        <span>${role}</span>
        <div class="text-btn-remove animi-btn rem-team-role-btn">
          Remove
        </div>
      </div>`);
    $("#team-role-ipt").val("");
  });
  $(".Team-roles-div").on("click", ".rem-team-role-btn", (e) => {
    $(e.target).parent().remove();
  });
});
