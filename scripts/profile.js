$(document).ready(() => {
  $(".saved-posts-btn").click(() => {
    $(".participations-tab").hide();
    $(".saved-posts").show();
    $(".saved-posts-btn")
      .children("svg")
      .children("path")
      .attr("fill", "#221E1E");
    $(".participations-tab-btn").css("background", "#d8e8ebab");
    $(".saved-posts-btn").css("background", "#CDE4E9");
  });
  $(".participations-tab-btn").click(() => {
    $(".saved-posts").hide();
    $(".participations-tab").show();
    $(".saved-posts-btn").css("background", "#d8e8ebab");
    $(".participations-tab-btn").css("background", "#CDE4E9");
    $(".saved-posts-btn").children("svg").children("path").attr("fill", "none");
  });
  let x = 0;
  $("#details-down").click(function () {
    if (x == 0) {
      $(".person-details").slideDown();
      $("#details-down").css("transform", "rotate(180deg)");
      x = 1;
    } else {
      x = 0;
      $(".person-details").slideUp();
      $("#details-down").css("transform", "rotate(0deg)");
    }
  });
});
