$(document).ready(() => {
  $(".updates-btn").click(() => {
    $(".events-btn")
      .css("background", "transparent")
      .css("border", "1px solid rgba(158, 141, 141, 0.38)");
    $(".updates-btn").css("background", "#CDE4E9").css("border", "none");
    $(".events-tab").hide();
    $(".updates-tab").show();
  });
  $(".events-btn").click(() => {
    $(".updates-btn")
      .css("background", "transparent")
      .css("border", "1px solid rgba(158, 141, 141, 0.38)");
    $(".events-btn").css("background", "#CDE4E9").css("border", "none");
    $(".updates-tab").hide();
    $(".events-tab").show();
  });
  $(".club-details-btn").click(() => {
    $(".club-esse-display").hide();
    $(".club-details-container").show();
  });
  $(".club-pg-btn").click(() => {
    $(".club-details-container").hide();
    $(".club-esse-display").show();
  });
});
