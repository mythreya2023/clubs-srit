$(document).ready(() => {
  $(".vote-cards").on("keyup", ".opt-txt", (e) => {
    $(e.target).siblings(".opt-radio-btn").val($(e.target).text());
  });
  $(".vote-cards").on("click", ".add-opt-btn", (e) => {
    $(e.target).parent("div").siblings(".card-options").append(`
                <div class="option-card">
                  <div class="option-select">
                    <div>
                      <label class="custom-radio-button">
                        <input type="radio" name="icon-radio" class="opt-radio-btn" value="option1" data-rollno1="" data-rollno2="" />
                        <span class="radio-icon"
                          ><i class="fa fa-circle"></i
                        ></span>
                        <span class="opt-txt" contenteditable="true"
                          >Option</span
                        >
                      </label>
                    </div>
                    <span class="drop-icon-btn animi-btn">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="20"
                        height="20"
                        viewBox="0 0 20 20"
                        fill="none"
                      >
                        <path
                          d="M5 7.5L10 12.5L15 7.5"
                          stroke="black"
                          stroke-width="2"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        />
                      </svg>
                    </span>
                  </div>
                  <div class="opt-roll-part">
                    <div class="opt-rollnos">
                        <input
                        type="text"
                        class="rollno-txt-box rollno1"
                        placeholder="Add Roll No 1"
                        style="border-bottom: 1px solid rgb(154, 140, 140)"
                        />
                        <input
                        type="text"
                        class="rollno-txt-box rollno2"
                        placeholder="Add Roll No 2"
                        />
                    </div>

                    <div
                        style="
                        display: flex;
                        justify-content: center;
                        margin-bottom: 10px;
                        "
                    >
                        <button class="done-opt-btn btn-mini animi-btn">
                        Done
                        </button>
                        
                      <button class="remove-opt-btn btn-mini animi-btn">
                      Remove
                    </button>
                    </div>
                  </div>
                </div>
        `);
  });
  let isdrop = false;
  $(".vote-cards").on("click", ".drop-icon-btn", (e) => {
    if (!isdrop) {
      isdrop = true;
      $(e.target)
        .closest(".drop-icon-btn")
        .parent(".option-select")
        .siblings(".opt-roll-part")
        .slideDown();
      $(e.target).closest(".drop-icon-btn").css("transform", "rotate(180deg)");
    } else {
      isdrop = false;
      $(e.target)
        .closest(".drop-icon-btn")
        .parent(".option-select")
        .siblings(".opt-roll-part")
        .slideUp();
      $(e.target).closest(".drop-icon-btn").css("transform", "rotate(0deg)");
    }
  });
  $(".vote-cards").on("click", ".done-opt-btn", (e) => {
    isdrop = false;
    $(e.target).parent("div").parent(".opt-roll-part").slideUp();
    $(e.target)
      .closest(".opt-roll-part")
      .siblings(".option-select")
      .children(".drop-icon-btn")
      .css("transform", "rotate(0deg)");
  });
  $(".vote-cards").on("click", ".remove-opt-btn", (e) => {
    isdrop = false;
    $(e.target)
      .parent("div")
      .parent(".opt-roll-part")
      .parent(".option-card")
      .remove();
  });
  $(".vote-cards").on("keyup", ".rollno-txt-box", (e) => {
    let roll_no = $(e.target).val();
    let rol_ntp = $(e.target).hasClass("rollno1")
      ? "data-rollno1"
      : "data-rollno2";
    $(e.target)
      .closest(".option-card")
      .find(".opt-radio-btn")
      .attr(rol_ntp, roll_no);
  });
  $(".vote-cards-container").on("click", ".rem-card-btn", (e) => {
    $(e.target).parent(".card-header").parent(".vote-card-box").remove();
    document.querySelectorAll(".vcard-id").forEach((ele, idx) => {
      $(ele).text(`Card ${idx + 1}`);
    });
  });
  $(".vote-cards-container").on("click", ".add-card-btn", (e) => {
    let vc_count = $(".vcard-id").length + 1;
    $(".vote-cards").append(`
    <div class="vote-card-box">
    <div class="card-header">
        <h3 class="sub-heading vcard-id">Card ${vc_count}</h3>
        <span class="rem-card-btn animi-btn text-btn-remove"
            style="margin-bottom: -10px">
            Remove
        </span>
    </div>
    <div class="vote-card">
    <input type="text" id="" placeholder="Write Question...?" class="text-box card-quest">
    <div class="card-options">
      <div class="option-card">
        <div class="option-select">
          <div>
            <label class="custom-radio-button">
              <input type="radio" name="icon-radio" class="opt-radio-btn" value="option" data-rollno1="" data-rollno2="">
              <span class="radio-icon"><i class="fa fa-circle"></i></span>
              <span class="opt-txt" contenteditable="true">Option</span>
            </label>
          </div>
          <span class="drop-icon-btn animi-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
              <path d="M5 7.5L10 12.5L15 7.5" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
          </span>
        </div>
        <div class="opt-roll-part">
          <div class="opt-rollnos">
            <input type="text" class="rollno-txt-box rollno1" placeholder="Add Roll No 1" style="border-bottom: 1px solid rgb(154, 140, 140)">
            <input type="text" class="rollno-txt-box rollno2" placeholder="Add Roll No 2">
          </div>

          <div style="
              display: flex;
              justify-content: center;
              margin-bottom: 10px;
            ">
            <button class="done-opt-btn btn-mini animi-btn">
              Done
            </button>

            <button class="remove-opt-btn btn-mini animi-btn">
              Remove
            </button>
          </div>
        </div>
      </div>
    </div>
    <div style="
        display: flex;
        justify-content: center;
        margin-bottom: 10px;
        margin-top: 10px;
      ">
      <button class="btn-mini animi-btn add-opt-btn">
        Add Option
      </button>
    </div>
  </div>  
  </div>  
    `);
  });

  $("#create-voting-btn,#update-voting-btn").click((e) => {
    let createUpdate =
      $(e.target).attr("id") == "create-voting-btn" ? "create" : "update";
    let cards = [];
    document.querySelectorAll(".vote-card-box").forEach((ele, idx) => {
      let q = $(ele).find(".card-quest").val();
      let cid = idx + 1;
      let opt_array = [];
      ele.querySelectorAll(".option-card").forEach((opts) => {
        let op_txt = $(opts).find(".opt-radio-btn").val();
        let rolno1 = $(opts).find(".opt-radio-btn").attr("data-rollno1");
        let rolno2 = $(opts).find(".opt-radio-btn").attr("data-rollno2");
        let optobj = {
          op: op_txt,
          rollno1: rolno1,
          rollno2: rolno2,
        };
        opt_array.push(optobj);
      });
      let cardobj = {
        card: cid,
        quest: q,
        options: opt_array,
      };
      cards.push(cardobj);
    });

    let cardsobj = {
      cards: cards,
    };
    const jsonObject = JSON.stringify(cardsobj, null, 4); // 4 spaces for indentation
    let file_name = "file_65929176def0c_ae2c6.json";
    fetch("php_scripts/voteManage.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        type: createUpdate,
        fname: file_name,
      },
      body: JSON.stringify(jsonObject),
    })
      .then((response) => response.text())
      .then((data) => console.log(data))
      .catch((error) => console.error("Error:", error));
  });
  $("#make-voting-live-btn").click((e) => {
    if (
      confirm("Once the voting is live, you can not edit the voting details.")
    ) {
      let cards = [];
      document.querySelectorAll(".vote-card-box").forEach((ele, idx) => {
        let q = $(ele).find(".card-quest").val();
        let cid = idx + 1;
        let opt_array = [];
        ele.querySelectorAll(".option-card").forEach((opts) => {
          let op_txt = $(opts).find(".opt-radio-btn").val();
          let optobj = {
            op: op_txt,
            count: 0,
          };
          opt_array.push(optobj);
        });
        let cardobj = {
          card: cid,
          quest: q,
          options: opt_array,
        };
        cards.push(cardobj);
      });

      let cardsobj = {
        cards: cards,
      };
      const jsonObject = JSON.stringify(cardsobj, null, 4); // 4 spaces for indentation
      fetch("php_scripts/voteManage.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          type: "make-live",
        },
        body: JSON.stringify(jsonObject),
      })
        .then((response) => response.text())
        .then((data) => console.log(data))
        .catch((error) => console.error("Error:", error));
    }
  });

  let mode = "edit";
  if (mode == "edit") {
    let file_name = "file_65994be744381_a020c.json";
    file_name = "file_65929176def0c_ae2c6.json";
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
        createVoteCards(JSON.parse(jsonData));
      })
      .catch((error) => {
        console.error(
          "There has been a problem with your fetch operation:",
          error
        );
      });
  }
});
function createVoteCards(jsonObj) {
  $(".vote-cards").html("");
  $("#update-voting-btn").show();
  $("#create-voting-btn").hide();
  let cardLen = jsonObj.cards.length;
  for (let i = 0; i < cardLen; i++) {
    let quest = jsonObj.cards[i].quest;
    $(".vote-cards").append(`
    <div class="vote-card-box">
    <div class="card-header">
        <h3 class="sub-heading vcard-id">Card ${i + 1}</h3>
        <span class="rem-card-btn animi-btn text-btn-remove"
            style="margin-bottom: -10px">
            Remove
        </span>
    </div>
    <div class="vote-card">
      <input type="text" id="" value="${quest}" placeholder="Write Question...?" class="text-box card-quest">
      <div class="card-options">
        
      </div>
      <div style="
          display: flex;
          justify-content: center;
          margin-bottom: 10px;
          margin-top: 10px;
        ">
        <button class="btn-mini animi-btn add-opt-btn">
          Add Option
        </button>
      </div>
    </div>  
    </div>  
    `);
  }
  document.querySelectorAll(".vote-card-box").forEach((ele, idx) => {
    jsonObj.cards[idx].options.forEach((opts) => {
      $(ele).find(".card-options").append(`
        <div class="option-card">
        <div class="option-select">
          <div>
            <label class="custom-radio-button">
              <input type="radio" name="icon-radio" class="opt-radio-btn" value="${opts.op}" data-rollno1="${opts.rollno1}" data-rollno2="${opts.rollno2}">
              <span class="radio-icon"><i class="fa fa-circle"></i></span>
              <span class="opt-txt" contenteditable="true">${opts.op}</span>
            </label>
          </div>
          <span class="drop-icon-btn animi-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
              <path d="M5 7.5L10 12.5L15 7.5" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
          </span>
        </div>
        <div class="opt-roll-part">
          <div class="opt-rollnos">
            <input type="text" class="rollno-txt-box rollno1" value="${opts.rollno1}" placeholder="Add Roll No 1" style="border-bottom: 1px solid rgb(154, 140, 140)">
            <input type="text" class="rollno-txt-box rollno2" value="${opts.rollno2}" placeholder="Add Roll No 2">
          </div>

          <div style="
              display: flex;
              justify-content: center;
              margin-bottom: 10px;
            ">
            <button class="done-opt-btn btn-mini animi-btn">
              Done
            </button>

            <button class="remove-opt-btn btn-mini animi-btn">
              Remove
            </button>
          </div>
        </div>
      </div>
        `);
    });
  });
}
