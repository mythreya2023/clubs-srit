$(document).ready(() => {
  $(".btn-login").click(() => {
    $(".signup-container").hide();
    $(".login-container").show();
    $(".btn-signup").css("background", "#D9D9D9").css("font-weight", "normal");
    $(".btn-login").css("background", "#6CDE8C").css("font-weight", "700");
  });
  $(".btn-signup").click(() => {
    $(".login-container").hide();
    $(".signup-container").show();
    $(".btn-login").css("background", "#D9D9D9").css("font-weight", "normal");
    $(".btn-signup").css("background", "#6CDE8C").css("font-weight", "700");
  });
  $("#login-btn").click((e) => {
    e.preventDefault();
    if (
      $("#login-pwd").val().trim() == "" ||
      $("#login-mail").val().trim() == ""
    ) {
      alert("Please fill all the deatils.");
    } else {
      let dataToSend = {
        LoS: "l",
        mail_id: $("#login-mail").val(),
        pwd: $("#login-pwd").val(),
      };
      var urlEncodedData = Object.keys(dataToSend)
        .map(
          (key) =>
            encodeURIComponent(key) + "=" + encodeURIComponent(dataToSend[key])
        )
        .join("&");
      fetch("php_scripts/loginSignup.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: urlEncodedData,
      })
        .then((response) => response.text())
        .then((data) => console.log(data))
        .catch((error) => console.error("Error:", error));
    }
  });
  $("#signup-btn").click((e) => {
    e.preventDefault();
    if (
      $("#signup-pwd").val().trim() == "" ||
      $("#signup-fullname").val().trim() == "" ||
      $("#signup-mail").val().trim() == "" ||
      $("#signup-cpwd").val().trim() == ""
    ) {
      alert("Please fill all the deatils.");
    } else {
      if ($("#signup-pwd").val().length > 8) {
        if ($("#signup-pwd").val() == $("#signup-cpwd").val()) {
          let dataToSend = {
            LoS: "s",
            user_name: $("#signup-fullname").val(),
            mail_id: $("#signup-mail").val(),
            pwd: $("#signup-pwd").val(),
          };
          var urlEncodedData = Object.keys(dataToSend)
            .map(
              (key) =>
                encodeURIComponent(key) +
                "=" +
                encodeURIComponent(dataToSend[key])
            )
            .join("&");
          fetch("php_scripts/loginSignup.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: urlEncodedData,
          })
            .then((response) => response.text())
            .then((data) => console.log(data))
            .catch((error) => console.error("Error:", error));
        } else {
          alert("Passwords do not match!");
        }
      } else {
        alert("Password should be atleast 8 characters long!");
      }
    }
  });
});
