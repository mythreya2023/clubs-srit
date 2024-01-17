$(document).ready(() => {
  $(".custom-checkbox").click(() => {
    if ($("#cta").is(":checked")) {
      $("#CTA-link").show();
      $(".cta-checked").show();
    } else {
      $("#CTA-link").hide();
      $(".cta-checked").hide();
    }
  });
});
