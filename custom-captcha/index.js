import sanitizer from "./libs/sanitizer.js";
const sanitize = new sanitizer()

const params = new URLSearchParams(window.location.search);
let [data,type] = params.get("c").split('.').map(it=>atob(it))
let verify = false

data = JSON.parse(data);

if (type == "Textbox") {
    data.text = sanitize.html(data.text)

    document.getElementById("rcc").innerHTML = `
    <h1 style="
    font-family: cursive;
    height: 0;
    margin: 0;
">${data.text}</h1><h1 style="
    font-family: emoji;
    font-size: 40px;
    height: 0;
    margin: 0;
">${data.text}</h1><h1 style="
    font-family: &quot;roboto sans&quot;, sans-serif;
    font-size: 35px;
    margin: 0;
    margin-bottom: 30px;
">${data.text}</h1>
<input type="text" id="tb" style="
    width: 98%;
">`
}
document.getElementById("ctype").innerText = type + " Captcha"
document.getElementById("ctitle").innerText = data.title
document.getElementById("cdesc").innerText = data.desc
document.getElementById("cct").innerText = data.cct

// Function triggered when client clicks [Verify]
function verifyCaptcha() {
    if (verify != true) {
        if (type == "Textbox") {
            if (document.getElementById("tb").value == data.text) {
                document.getElementById("ct").innerText = data.ctext
                document.getElementById("ct").style.color = data.ccolor
            } else {
                document.getElementById("ct").innerText = data.wtext
                document.getElementById("ct").style.color = data.wcolor
            }
        }
        document.getElementById("ct").style.display = "block";
        document.getElementById("fkrc-verifywin-verify-button").innerText = "Close";
        verify = true
    } else {
        closeVerifyWindow();
        document.getElementById("tb").value = "";
        document.getElementById("ct").style.display = "none";
        document.getElementById("fkrc-verifywin-verify-button").innerText = "Verify";
        verify = false
    }

}





let checkboxWindow = document.getElementById("fkrc-checkbox-window");
let checkboxBtn = document.getElementById("fkrc-checkbox");
let checkboxBtnSpinner = document.getElementById("fkrc-spinner");
let verifyWindow = document.getElementById("fkrc-verifywin-window");
let verifyWindowArrow = document.getElementById("fkrc-verifywin-window-arrow");
let verifyBtn = document.getElementById("fkrc-verifywin-verify-button");

function addCaptchaListeners() {
    if (checkboxBtn && verifyBtn) {
        document.addEventListener("click", function (event) {
            if (!event.path.includes(verifyWindow) && isVerifyWindowVisible()) {
                closeVerifyWindow();
            }
        });
        verifyBtn.addEventListener("click", function (event) {
            event.preventDefault();
            verifyCaptcha();
        });
        checkboxBtn.addEventListener("click", function (event) {
            event.preventDefault();
            checkboxBtn.disabled = true;
            runClickedCheckboxEffects();
        });
    }
}
addCaptchaListeners();

function runClickedCheckboxEffects() {
    hideCaptchaCheckbox();
    setTimeout(function(){
        showCaptchaLoading();
    },500)
    setTimeout(function(){
        showVerifyWindow();
    },900)
}

function showCaptchaCheckbox() {
    checkboxBtn.style.width = "100%";
    checkboxBtn.style.height = "100%";
    checkboxBtn.style.borderRadius = "2px";
    checkboxBtn.style.margin = "21px 0 0 12px";
    checkboxBtn.style.opacity = "1";
}

function hideCaptchaCheckbox() {
    checkboxBtn.style.width = "4px";
    checkboxBtn.style.height = "4px";
    checkboxBtn.style.borderRadius = "50%";
    checkboxBtn.style.marginLeft = "25px";
    checkboxBtn.style.marginTop = "33px";
    checkboxBtn.style.opacity = "0";
}

function showCaptchaLoading() {
    checkboxBtnSpinner.style.visibility = "visible";
    checkboxBtnSpinner.style.opacity = "1";
}

function hideCaptchaLoading() {
    checkboxBtnSpinner.style.visibility = "hidden";
    checkboxBtnSpinner.style.opacity = "0";
}

function showVerifyWindow() {
    verifyWindow.style.display = "block";
    verifyWindow.style.visibility = "visible";
    verifyWindow.style.opacity = "1";
    verifyWindow.style.top = checkboxWindow.offsetTop - 80 + "px";
    verifyWindow.style.left =  checkboxWindow.offsetLeft + 54 + "px";

   if (verifyWindow.offsetTop < 5) {
       verifyWindow.style.top = "5px";
   }

   if (verifyWindow.offsetLeft + verifyWindow.offsetWidth > window.innerWidth-10 ) {
       verifyWindow.style.left =  checkboxWindow.offsetLeft - 8  + "px";
   } else {
       verifyWindowArrow.style.top = checkboxWindow.offsetTop + 24 + "px";
       verifyWindowArrow.style.left = checkboxWindow.offsetLeft + 45 + "px";
       verifyWindowArrow.style.visibility = "visible";
       verifyWindowArrow.style.opacity = "1";
   }
}

function closeVerifyWindow() {
    verifyWindow.style.display = "none";
    verifyWindow.style.visibility = "hidden";
    verifyWindow.style.opacity = "0";

    verifyWindowArrow.style.visibility = "hidden";
    verifyWindowArrow.style.opacity = "0";

    showCaptchaCheckbox();
    hideCaptchaLoading();
    checkboxBtn.disabled = false;
    verifyBtn.disabled = false;
}

function isVerifyWindowVisible() {
    return verifyWindow.style.display !== "none" && verifyWindow.style.display !== "";
}