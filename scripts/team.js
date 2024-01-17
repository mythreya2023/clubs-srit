$(document).ready(() => {
  $(".open-add-member-btn").click(() => {
    $(".add-team-member-container").show();
    $(".team-members-container").hide();
  });
  $(".team-back-btn").click(() => {
    $(".add-team-member-container").hide();
    $(".team-members-container").show();
  });
  $(".add-team-member-btn").click(() => {
    let img = $("#searched-us-img").attr("src");
    let mem_name = $(".us-srch-name").text();
    let role = $('input[name="role-radio"]:checked').val();
    if (role != undefined && mem_name != "" && img != "") {
      $(".team-studs").append(`
            <div class="team-member">
              <div class="mem-details">
                <img
                  src="${img}"
                  alt=""
                  class="img-sq userImage"
                />
                <div class="mem-txt-det">
                  <p class="mem-name">${mem_name}</p>
                  <p class="mem-role">${role}</p>
                </div>
              </div>
              <div class="text-btn-remove animi-btn">Remove</div>
            </div>
    `);
      $(".team-back-btn").click();
    }
  });
});
