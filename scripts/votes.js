$(document).ready(() => {
  let file_name = "file_65994be744381_a020c.json";
  // file_name = "file_65929176def0c_ae2c6.json";
  fetch("votings/" + file_name)
    .then((response) => {
      // Check if the request was successful
      if (!response.ok) {
        throw new Error("Network response was not ok " + response.statusText);
      }
      return response.json();
    })
    .then((jsonData) => {
      console.log(jsonData);
      createVoteBlocks(JSON.parse(jsonData));
    })
    .catch((error) => {
      console.error(
        "There has been a problem with your fetch operation:",
        error
      );
    });
});
function createVoteBlocks(jsonObj) {
  console.log(jsonObj);
  let cardLen = jsonObj.cards.length;
  for (let i = 0; i < cardLen; i++) {
    $(".vote-cards").append(`
    <div class="vote-card-box">
        <h3 class="sub-heading vcard-id">Card ${i + 1}/${cardLen}</h3>
        <div class="vote-card" style="padding-bottom: 15px">
        <div  class="text-box card-quest-div" >
        ${jsonObj.cards[i].quest}
        </div>
        <hr style="margin-top: -3px; width: 92%">
        <div class="card-options">
        
        </div>
        </div>
    </div>
`);
  }
  var myrollno = 563;
  document.querySelectorAll(".vote-card-box").forEach((ele, idx) => {
    jsonObj.cards[idx].options.forEach((opts) => {
      // console.log($(ele).find(".vcard-id").text());
      if (opts.rollno1 != myrollno && opts.rollno2 != myrollno) {
        $(ele).find(".card-options").append(`
            <div class="option-card">
            <div class="option-select">
                <div>
                <label class="custom-radio-button">
                    <input type="radio" name="icon-radio-${
                      idx + 1
                    }" class="opt-radio-btn" value="${opts.op}" data-rollno1="${
          opts.rollno1
        }" data-rollno2="${opts.rollno2}">
                    <span class="radio-icon"><i class="fa fa-circle"></i></span>
                    <span class="opt-txt">${opts.op}</span>
                </label>
                <span></span>
                </div>
            </div>
            </div>
        `);
      }
    });
  });

  var voteArray = [];
  $(".submit-vote-btn").click((e) => {
    voteArray = [];
    let cardCount = $(".vote-card-box").length;
    document.querySelectorAll(".vote-card-box").forEach((ele, idx) => {
      let quest = $(ele).find(".card-quest-div").text();
      let radioSelected = $(ele)
        .find(`input[name="icon-radio-${idx + 1}"]:checked`)
        .val();
      if (radioSelected != null && radioSelected != undefined) {
        voteArray.push(radioSelected);
      }
    });
    if (voteArray.length == cardCount) {
      fetch("php_scripts/voteCount.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          filename: "file_65994bbe85c30_b1163.json",
        },
        body: JSON.stringify(voteArray),
      })
        .then((response) => response.text())
        .then((data) => console.log(data))
        .catch((error) => console.error("Error:", error));
    } else {
      console.log("Select atleast one option in all cards");
    }
  });
}
